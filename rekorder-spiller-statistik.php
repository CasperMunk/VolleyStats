<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");
require('includes/header.php');

$VolleyStats->setRecordType('player');
$VolleyStats->printRecords();

require('includes/footer.php');