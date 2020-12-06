<?php require_once('functions.php'); ?>
<?php require_once('protect.php'); ?>
<?php require_once('secrets.php'); ?>
<?php require_once('volleystats.php'); ?>



<?php
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
    'Data' => array(
        'data_players.php' => 'Spillere',
        'data_games.php' => 'Kampe'
        ),
    'updater.php' => 'Opdatering'
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