<?php

include_once('settings.php');

$store = ARC2::getStore($config);
if (!$store->isSetup()) {
    $store->setUp();
}

$store->reset();
$rs = $store->query('BASE <.> LOAD <lcco.rdf>');

print_r($rs);
