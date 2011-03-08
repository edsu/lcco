<?php

$q = '
PREFIX skos: <http://www.w3.org/2008/05/skos#> .
SELECT ?concept ?range ?pref_label
WHERE {
    <http://inkdroid.org/lcco/> skos:hasTopConcept ?concept .
    ?concept skos:prefLabel ?pref_label .
    ?concept skos:notation ?range .
}
ORDER BY ?range
';

$rows = $store->query($q, 'rows');
include('views/outline.html');
