<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('functions.php');
require('secrets.php');
include('autoload.php');

$full_page = false;
$loadElements = array();

$script_name = basename($_SERVER['SCRIPT_NAME']);

$VolleyStats = new VolleyStats();
$VolleyStats->initializeMysql($secrets['mysql_host'],$secrets['mysql_username'],$secrets['mysql_password'],$secrets['mysql_database']);
$VolleyStats->setSecrets($secrets);