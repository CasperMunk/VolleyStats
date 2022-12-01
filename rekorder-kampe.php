<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");

$page_info = array(
    'title' => 'Rekorder for kampe',
);

require('includes/header.php');

$VolleyStats->setRecordType('game');
$VolleyStats->printRecords();

require('includes/footer.php');