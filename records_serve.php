<?php 
require('includes/top.php'); 
require('includes/header.php');

$records = array(
    array(
        "id" => "serve_total",
        "title" => "Antal server i en kamp",
        "measurement" => "server"
    ),
    array(
        "id" => "serve_ace",
        "title" => "Antal esser i en kamp",
        "measurement" => "esser"
    )
);

require('includes/records_include.php');

require('includes/footer.php'); 
?>