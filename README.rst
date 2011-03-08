====
lcco
====

This minimal project converts a visually hierarhical text representation 
(lc_class.txt) of the Library of Congress Classification Outline (`scraped
<http://www.archive.org/details/LcClassificationA-z>`_ 
from PDFs by Karen Coyle) to SKOS RDF (lcco.rdf). 
The SKOS RDF is then published on the Web using "PHP's ARC2
<http://arc.semsol.org/>"_.

Since Python is used to convert the text file to RDF, and PHP is used to 
publish the RDF, lcco is also a little demonstration of how RDF can be 
used as an neutral interchange format between databases.

Installation
============

If you don't want to regenerate the lcco.rdf file installation of 
Python and rdflib and steps 2 and 3 are not necessary.

1. install Apache, MySQL and PHP, Python and rdflib
2. adjust the LCCO Namespace appropriately in skosify.py
3. ./skosify.py > lcco.rdf
4. create a MySQL database and adjust settings.php appropriately
3. php -e load.php 

License
=======

`Public Domain <http://wiki.creativecommons.org/CC0>`_

Author
======

Ed Summers <ehs@pobox.com>
