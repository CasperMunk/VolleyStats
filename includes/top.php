<?php 
require_once('functions.php');
require('secrets.php');
include('autoload.php');

$mode = get('mode');
$game_id = get('game_id');
$gender = get('gender');
$competition_id = get('competition_id');
$key = get('key');

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
        "navi_title" => "Data analyse",
        "items" => 
        array(
            array(
                "url" => "data-analyse-spiller-totalt",
                "navi_title" => "Spiller totalt",
                "title" => "Data analyse: Spiller totalt",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-spiller-per-kamp",
                "navi_title" => "Spiller pr. kamp",
                "title" => "Data analyse: Spiller pr. kamp",
                "meta_description" => "",
            ),
            array(
                "navi_title" => "<divider>",
                "url" => "",
            ),
            array(
                "url" => "data-analyse-hold-totalt",
                "navi_title" => "Hold totalt",
                "title" => "Data analyse: Hold totalt",
                "meta_description" => "",
            ),
            array(
                "url" => "data-analyse-hold-per-kamp",
                "navi_title" => "Hold pr. kamp",
                "title" => "Data analyse: Hold pr. kamp",
                "meta_description" => "",
            ),
        ),
    ),
    array(
        "navi_title" => "Rekorder",
        "items" => 
        array(
            // array(
            //     "url" => "rekorder-tidslinje",
            //     "navi_title" => "Tidslinje",
            //     "title" => "Tidslinje over rekorder",
            //     "meta_description" => "",
            // ),
            // array(
            //     "navi_title" => "<divider>",
            //     "url" => "",
            // ),
            array(
                "url" => "rekorder-spiller",
                "navi_title" => "Spillere",
                "title" => "Rekorder for spillere",
                "meta_description" => "",
            ),
            array(
                "url" => "rekorder-kampe",
                "navi_title" => "Kampe",
                "title" => "Rekorder for kampe",
                "meta_description" => "",
            ),
        ),
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