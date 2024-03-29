<?php 
require('includes/top.php');

$update = get('update');
$game_id = get('game_id');
$gender = get('gender');
$competition_id = get('competition_id');
$update_game_list = get('update_game_list');
$update_game_result = get('update_game_result');
$update_game_stats = get('update_game_stats');
$cronjob_key = get('cronjob_key');
$page_info['title'] = "Admin";

if ($cronjob_key != $secrets['cronjob_key']){
    require('includes/protect.php');
    Protect\with('login.php', $secrets['password'],"VolleyStats");    
}
$loadElements = array("jQuery","updater.js");

if ($update == 'competition'){
    //Ajax response for update competitions
    if (empty($competition_id)) exit();

    $competition = $VolleyStats->getCompetition($competition_id);
    
    echo '<h3>'.$competition['year'].' ('.$competition['gender'].')</h3>';
    
    foreach($VolleyStats->getGames($competition['id'],$update_game_list) as $game){
        echo '  <div class="game not-updated" game-id="'.$game.'" competition_id="'.$competition['id'].'" gender="'.$competition['gender'].'" update_game_result="'.$update_game_result.'" update_game_stats="'.$update_game_stats.'">
                    <span class="title">Kamp '.$game.':</span> 
                    <span class="result"></span>
                </div>';
    }
    exit;
}elseif ($update == "game"){
    //Ajax response for update game

    $result = $VolleyStats->getGameData($game_id,$competition_id,$gender,$update_game_stats);

    if ($result === true){
        echo '<span class="badge bg-success">Opdateret</span>';        
    }else{
        echo '<span class="badge bg-warning">'.$result.'</span>';
    }

    exit;
}elseif ($update == 'competitions_cronjob'){
    //Non-Ajax updates for cronjobs
    foreach ($VolleyStats->getCompetitions(true) as $comp){
        foreach ($VolleyStats->getGames($comp['id'],true) as $game){
            if ($result = $VolleyStats->getGameData($game,$comp['id'],$comp['gender'])){
                //echo $result;
            }
        }
    }

    $VolleyStats->updateRecords();
    echo 'Updated';
    exit;
}elseif ($update == 'todays_games_cronjob'){
    //Non-Ajax updates for cronjobs
    if ($games = $VolleyStats->getGamesToday()){
        foreach ($games as $game){
            if ($result = $VolleyStats->getGameData($game['id'],$game['competition_id'],$game['gender'])){
                //echo $result;
            }
        }
        $VolleyStats->updateRecords();
        echo 'Updated';
    }else{
        echo 'No games today';
    }
    exit;
}elseif ($update == 'records'){
    if ($VolleyStats->updateRecords()){
        echo '<span class="badge bg-success">Opdateret</span>';
    }else{
        echo '<span class="badge bg-warning">Fejl</span>';
    };

    exit;
}

require('includes/header.php'); ?>

<form method="get" action="?mode=update">
    <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Turneringer</label>
        <div class="col-sm-4">
            <select multiple class="form-select" id="competitions" size="8">
              <?php foreach($VolleyStats->getCompetitions() as $comp): ?>
                <option value="<?php echo $comp['id']; ?>" <?php echo ($comp['current'] ? 'selected' : '')?>>
                    <?php echo $comp['year']; ?> - <?php echo $VolleyStats->translateText($comp['gender']); ?> <?php echo ($comp['current'] ? '(auto-opdateres)' : '')?>
                </option>
              <?php endforeach; ?>
            </select>
        </div>
    </div>
    <fieldset class="row mb-3">
        <legend class="col-form-label col-sm-2 pt-0">Indstillinger</legend>
        <div class="col-sm-10">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="update_game_list" name="update_game_list" />
                <label class="form-check-label" for="update_game_list">Opdater kampliste</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="update_game_result" name="update_game_result" checked />
                <label class="form-check-label" for="update_game_result">Opdater kampresultat og info</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="update_game_stats" name="update_game_stats" checked />
                <label class="form-check-label" for="update_game_stats">Opdater kampstatistik</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="update_records" name="update_records" checked />
                <label class="form-check-label" for="update_records">Opdater rekorder</label>
            </div>
        </div>
    </fieldset>
    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <div class="form-check form-switch">
                <input class="form-check-input always-on" type="checkbox" id="debug-mode">
                <label class="form-check-label" for="debug-mode">Debug mode</label>
            </div>
        </div>
    </div>
    <button id="update-button" class="btn btn-primary mt-2 mb-2" type="submit">
        <span class="icon spinner-border spinner-border-sm d-none"></span>
        <span class="text">Opdater</span>
    </button>
    <span id="cancel" class="m-2" style="display: none;"><a href="#">Annuller</a></span>
</form>
            

<div class="mb-2">
    <div class="progress" style="height: 20px; display: none;">
        <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
    </div>
</div>

<div class="alert alert-secondary" id="status" style="display: none;">
    <span>Klar...</span>
</div>
            

<div id="result" style="display:none;"></div>

<p></p>
    
<?php require('includes/footer.php'); ?>