#!/usr/bin/env python

# munge lc_class.txt text file into SKOS/RDF and output as a single rdf/xml 
# document
#
# Eventually it would be nice to read in some actual machine readable data
# and generate the RDF, but this will work for now to hopefully bootstrap
# the idea.
#
# Basically we read through the document tracking the number of 
# tabs used to indent parts of the outline, in order to determine
# the hierarchy. As we go we maintain our state to in an array 
# lc_class that ends up looking something like:
#
# [('BL1-2790', 'Religions.  Mythology.  Rationalism'), ('BL660-2680', 
# 'History and principles of religions'), ('BL689-980', 
# 'European.  Occidental'), ('BL700-820', 'Classical (Etruscan, Greek, Roman)')]
# 
# This variable can be used to generate a full prefLabel while keeping track 
# of the classification range, which is used to mint URIs for SKOS concepts.

import codecs
import re

from rdflib import ConjunctiveGraph, Namespace, URIRef, Literal, RDF

LCCO = Namespace('http://inkdroid.org/lcco/')
SKOS = Namespace('http://www.w3.org/2008/05/skos#')
LCC = URIRef('http://www.loc.gov/catdir/cpso/lcco/')

def range_uri(r):
    return URIRef(LCCO[r])

if __name__ == '__main__':

    g = ConjunctiveGraph('Sleepycat')
    g.open('store', create=True)
    g.bind('skos', SKOS)
    g.bind('lcco', LCCO)
    g.add((LCCO, RDF.type, SKOS.ConceptScheme))

    lc_class = []
    for line in codecs.open('lc_class.txt', 'rb', 'latin-1'):
        line = line.strip()

        if ("\t" not in line or line.startswith('Subclass')) \
            and not line.startswith("CLASS"):
            continue

        class_match = re.match(r'CLASS (.+) - (.+)', line)
        if class_match:
            range = class_match.group(1)
            label = class_match.group(2)
            parts = re.split(r' +', label)
            label = ' '.join(l.lower().capitalize() for l in parts).strip()
            position = 0
        else:
            parts = line.split("\t")
            label = parts.pop().strip()
            range = parts.pop(0).strip()
            position = len(parts) + 1

        # if there's no range then we've got a chunk of text that needs 
        # to be added to the last concept we added to the graph 
        if not range:
            uri = range_uri(lc_class[-1][0])
            old_label = list(g.objects(uri, SKOS.prefLabel))[0]
            new_label = "%s %s" % (old_label, label)
            g.remove((uri, SKOS.prefLabel, old_label))
            g.add((uri, SKOS.prefLabel, Literal(new_label, 'en')))
            continue

        lc_class = lc_class[0:position]
        lc_class.insert(position, (range, label))

        label = '--'.join([c[1] for c in lc_class])
        uri = range_uri(range)

        g.add((uri, RDF.type, SKOS.Concept))
        g.add((uri, SKOS.prefLabel, Literal(label, 'en')))
        g.add((uri, SKOS.notation, Literal(range, datatype=LCC)))

        if position == 0:
            g.add((LCCO, SKOS.hasTopConcept, uri))

        # link the concepts
        if len(lc_class) > 1:
            last_uri = range_uri(lc_class[-2][0])
            g.add((uri, SKOS.broader, last_uri))
            g.add((last_uri, SKOS.narrower, uri))

    print g.serialize()
