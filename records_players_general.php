<?php 
require('includes/top.php');
$loadElements = array("jQuery");
require('includes/header.php');

$records = array(
    array(
        "id" => "games_played",
        "title" => "Antal kampe spillet",
        "measurement" => "kampe"
    ),
);

require('includes/records_include.php');

require('includes/footer.php'); 
?>