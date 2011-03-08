<?php

/* ARC2 static class inclusion */ 
include_once('../arc/arc/ARC2.php');

/* MySQL and endpoint configuration */ 
$config = array(
  /* db */
  'db_host' => 'localhost', /* optional, default is localhost */
  'db_name' => 'arc',
  'db_user' => 'arc',
  'db_pwd' => 'arcness',

  /* store name */
  'store_name' => 'lcco',

  /* endpoint */
  'endpoint_features' => array(
    'select', 'construct', 'ask', 'describe'
  ),
  'endpoint_timeout' => 60, /* not implemented in ARC2 preview */
  'endpoint_read_key' => '', /* optional */
  'endpoint_write_key' => 'somekey', /* optional */
  'endpoint_max_limit' => 250, /* optional */
);

/* instantiation */
$ep = ARC2::getStoreEndpoint($config);

if (!$ep->isSetUp()) {
  $ep->setUp(); /* create MySQL tables */
}

/* request handling */
$ep->go();

?>
