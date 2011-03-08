<?php

include_once('settings.php');

$store = ARC2::getStore($config);

switch ($_REQUEST['action']) {
case 'outline':
    include('actions/outline.php');
    break;
case 'range':
    include('actions/range.php');
    break;
default:
    include('actions/outline.php');
    break;
}

?>


  
