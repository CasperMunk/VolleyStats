<?php 
require('includes/top.php'); 
$loadElements = array("jQuery","records.js");
require('includes/header.php');

$records = array(
    array(
        "id" => "points_total",
        "title" => "Antal point i en kamp",
        "measurement" => "point"
    ),
    array(
        "id" => "break_points",
        "title" => "Antal BP i en kamp",
        "measurement" => "BP"
    ),
    array(
        "id" => "win_loss",
        "title" => "Højeste V-T i en kamp",
        "measurement" => "V-T"
    )
);

require('includes/footer.php');