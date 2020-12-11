<?php 
require('includes/top.php');
$loadElements = array("jQuery");
require('includes/header.php');

$records = array(
    array(
        "id" => "spike_total",
        "title" => "Antal hævninger i en kamp",
        "measurement" => "hævninger"
    ),
    array(
        "id" => "spike_win",
        "title" => "Antal vundne angreb i en kamp",
        "measurement" => "angreb"
    )
);

require('includes/records_include.php');

require('includes/footer.php'); 
?>