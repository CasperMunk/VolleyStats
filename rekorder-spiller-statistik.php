<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");

$page_info = array(
    'title' => 'Rekorder for spiller-statistik',
);

require('includes/header.php');

$VolleyStats->setRecordType('player');
$VolleyStats->printRecords();

require('includes/footer.php');