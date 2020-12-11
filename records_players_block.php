<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");
require('includes/header.php');

$records = array(
    array(
        "id" => "block_win",
        "title" => "Antal bloks i en kamp",
        "measurement" => "bloks"
    )
);

require('includes/records_include.php');

require('includes/footer.php'); 
?>