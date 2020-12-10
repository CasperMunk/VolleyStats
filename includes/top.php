<?php 
require_once('functions.php');
require_once('protect.php');
require_once('secrets.php');
require_once('volleystats.class.php'); 
require_once('datatable.class.php'); 

$mode = get('mode');
$game_id = get('game_id');
$gender = get('gender');
$competition_id = get('competition_id');
$update_type = get('update_type');
if ($update_type == 'competition_and_games'){
    $update_type = true;
}else{
    $update_type = false;
}

$full_page = false;

$navigation = array(
    'index.php' => 'Velkommen',
    'Spillere' => array(
        'players_total.php' => 'Spillere totalt',
        'players_per_game.php' => 'Spillere pr. kamp',
        // '<divider>' => '',
        // '#' => 'Serv',
        // '#1' => 'Angreb',
        // '#2' => 'Blok',
        // '#3' => 'Modtagning'
    ),
    'Hold' => array(
        'teams_total.php' => 'Hold totalt',
        'teams_per_game.php' => 'Hold pr. kamp'
    ),
    'Rekorder spillere' => array(
        'records_players_general.php' => 'Generelt',
        'records_players_point.php' => 'Point',
        'records_players_serve.php' => 'Serv',
        'records_players_receive.php' => 'Modtagning',
        'records_players_spike.php' => 'Angreb',
        'records_players_block.php' => 'Blok'
    ),
    // 'Rekorder kampe' => array(
    //     'records_games.php' => 'Kampe'
    // ),  
);

$current_page = basename($_SERVER['SCRIPT_NAME']);
foreach ($navigation as $key => $value){
    if ($key == $current_page){
        $current_page_title = $navigation[$current_page];        
    }elseif (is_array($value)){
        foreach ($value as $subkey => $subvalue){
            if ($subkey == $current_page){
                $current_page_title = $value[$current_page];
            }
        }
    }
}


$VolleyStats = new VolleyStats();
$VolleyStats->initializeMysql($secrets['mysql_host'],$secrets['mysql_username'],$secrets['mysql_password'],$secrets['mysql_database']);

?>