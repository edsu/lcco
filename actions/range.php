<?php

$range = $_REQUEST['range'];
$concept = "<http://inkdroid.org/lcco/$range>";

function get_last_part($label) {
    $parts = split('--', $label);
    return array_pop($parts);
}

# get the prefLabel for the parent concept

$q = "
    PREFIX skos: <http://www.w3.org/2008/05/skos#> .
    SELECT ?pref_label 
    WHERE {
        $concept skos:prefLabel ?pref_label .
    }
";

$result = $store->query($q, 'rows');
$pref_label = get_last_part($result[0]['pref_label']);

# now get all the narrower concepts

$q = "
    PREFIX skos: <http://www.w3.org/2008/05/skos#> .
    SELECT ?s ?pref_label ?notation 
    WHERE {
        $concept skos:narrower ?s .
        ?s skos:prefLabel ?pref_label .
        ?s skos:notation ?notation .
    }
    ORDER BY ?notation
";

$rows = $store->query($q, 'rows');

function has_children($uri) {
    $q = "SELECT ?s {WHERE <$uri> skos:narrower ?o .}";
    $rows = $store->query($q, 'rows');
    return sizeof($rows);
}

# munge the pref label removing the stuff that will be repeated in the outline

$ranges = Array();
foreach ($rows as $row) {
    $ranges[] = Array(
        "notation" => $row['notation'], 
        "pref_label" => get_last_part($row['pref_label']),
    );
}

include('views/range.html');
