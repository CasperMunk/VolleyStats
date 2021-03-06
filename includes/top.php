<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('functions.php');
require('secrets.php');
include('autoload.php');

$full_page = false;
$loadElements = array();

$pages = 
array(
    array(
        "url" => "/",
        "filename" => "index.php",
        "navi_title" => "Hjem",
        "title" => "Statistik og rekorder fra VolleyLigaen",
        "meta_description" => "Denne lille side er lavet for at kunne dykke ned i statistik og rekorder fra VolleyLigaen. Denne side indsamler kamp-statistik fra alle tilgængelige sæsoner og viser dem på forskellige måder. Det betyder at du finde finde rekorder for enkelte volleyball kampe i VolleyLigaen.",
    ),
    array(
        "navi_title" => "Spillere",
        "items" => 
        array(
            array(
                "url" => "rekorder-spiller-statistik",
                "navi_title" => "Rekorder for spillere",
                "title" => "Rekorder for spiller-statistik",
                "meta_description" => "",
            ),
            array(
                "navi_title" => "<divider>",
                "url" => "",
            ),
            array(
                "url" => "data-analyse-spiller-totalt",
                "navi_title" => "Data: Spillere totalt",
                "title" => "Data analyse: Spiller totalt",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-spiller-per-kamp",
                "navi_title" => "Data: Spillere pr. kamp",
                "title" => "Data analyse: Spiller pr. kamp",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-spiller-per-set",
                "navi_title" => "Data: Spillere pr. sæt",
                "title" => "Data analyse: Spiller pr. sæt",
                "meta_description" => "",
            ),
        ),
    ),
    array(
        "navi_title" => "Hold",
        "items" => 
        array(
            array(
                "url" => "data-analyse-hold-totalt",
                "navi_title" => "Data: Hold totalt",
                "title" => "Data analyse: Hold totalt",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-hold-per-kamp",
                "navi_title" => "Data: Hold pr. kamp",
                "title" => "Data analyse: Hold pr. kamp",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-hold-per-set",
                "navi_title" => "Data: Hold pr. sæt",
                "title" => "Data analyse: Hold pr. sæt",
                "meta_description" => "",
            ),
        ),
    ),
    array(
        "navi_title" => "Kampe",
        "items" => 
        array(
            array(
                "url" => "rekorder-kampe",
                "navi_title" => "Rekorder for kampe",
                "title" => "Rekorder for kampe",
                "meta_description" => "",
            ),
        )
    ),
    array(
        "url" => "betingelser",
        "navi_title" => "",
        "title" => "Betingelser for brug",
        "meta_description" => "",
        "exclude_from_navi" => true,
    ),
    array(
        "url" => "opdatering",
        "navi_title" => "",
        "title" => "Opdatering af data",
        "meta_description" => "",
        "exclude_from_navi" => true,
    ),
    array(
        "url" => "login",
        "navi_title" => "",
        "title" => "Login",
        "meta_description" => "",
        "exclude_from_navi" => true,
    ),
    array(
        "url" => "faq",
        "filename" => "faq.php",
        "navi_title" => "FAQ",
        "title" => "FAQ",
        "meta_description" => "",
    ),
);

$script_name = basename($_SERVER['SCRIPT_NAME']);

foreach ($pages as $key => $array){
    if (isset($array['items'])){
        foreach ($array['items'] as $subkey => $subarray){
            if (!isset($subarray['filename'])){
                $pages[$key]['items'][$subkey]['filename'] = $subarray['url'].".php";
            }
            if ($pages[$key]['items'][$subkey]['filename'] == $script_name) $current_page = $subarray;    
        }
    }else{
        if (!isset($array['filename'])){
            $pages[$key]['filename'] = $array['url'].".php";
        }
        if ($pages[$key]['filename'] == $script_name) $current_page = $array;
    }
}

// echo '<pre>';
// print_r($pages);

$VolleyStats = new VolleyStats();
$VolleyStats->initializeMysql($secrets['mysql_host'],$secrets['mysql_username'],$secrets['mysql_password'],$secrets['mysql_database']);
$VolleyStats->setSecrets($secrets);