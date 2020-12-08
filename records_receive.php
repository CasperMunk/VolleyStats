<?php 
require('includes/top.php'); 
require('includes/header.php');

$records = array(
    array(
        "id" => "receive_total",
        "title" => "Antal modtagninger i en kamp",
        "measurement" => "modtagninger"
    ),
    array(
        "id" => "receive_perfect",
        "title" => "Antal perfekte modtagning i en kamp",
        "measurement" => "modtagninger"
    )
);

require('includes/records_include.php');

require('includes/footer.php'); 
?>