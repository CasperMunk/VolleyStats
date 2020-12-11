<?php 
require('includes/top.php');
Protect\with('login.php', $secrets['password'],'updater.php');
$loadElements = array("jQuery","updater.js");


if ($mode == 'update'){
    foreach($VolleyStats->getCompetitions() as $competition){
        //Check if competition should be updated!!!
        
        echo '<h3>'.$competition['year'].' ('.$competition['gender'].')</h3>';
        echo '<div id="'.$competition['id'].'" class="competition">';
        
        foreach($VolleyStats->getGames($competition['id'],$update_type) as $game){
            echo '  <div id="'.$game.'" class="game not-updated" competition_id="'.$competition['id'].'" gender="'.$competition['gender'].'">
                        <span class="title">Game '.$game.':</span> 
                        <span class="result"></span>
                    </div>';
        }
        echo '</div>';
    }
    exit;
}elseif ($mode == "loadgame"){
    if ($result = $VolleyStats->getGameData($game_id,$competition_id,$gender)){
        if ($result === true){
            echo '<span class="badge badge-success">Updated</span>';        
        }elseif ($result == 'skipped'){
            echo '<span class="badge badge-info">Could not get data</span>';
        }else{
            echo '<span class="badge badge-warning">Unknown error</span>';
        }
    }else{
            echo '<span class="badge badge-warning">Error in calling getGameData</span>';
    }
    exit;
}

$current_page_title = "Opdatering";
require('includes/header.php'); ?> 

            

            <form>

              <fieldset class="form-group">
                <div class="row">
                  <legend class="col-form-label col-sm-2 pt-0">Opdatering type</legend>
                  <div class="col-sm-10">

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="updateType" id="updateType1" value="competition_and_games" checked>
                        <label class="form-check-label" for="updateType1">
                        Turnering og kampe
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="updateType" id="updateType2" value="only_games">
                        <label class="form-check-label" for="updateType2">
                        Kun kampe
                        </label>
                    </div>
                  </div>
                </div>
              </fieldset>
              <div class="form-group row">
                <div class="col-sm-2 text-nowrap"><label for="competitions">Turneringer</label></div>
                <div class="col-sm-10">
                  <div class="form-check">
                    <select multiple class="form-select" id="competitions">
                      <?php foreach($VolleyStats->getCompetitions() as $comp): ?>
                        <option value="<?php echo $comp['id']; ?>"><?php echo $comp['year']; ?> - <?php echo $comp['gender']; ?></option>
                      <?php endforeach; ?>
                    </select>
                        
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-2 text-nowrap"></div>
                <div class="col-sm-10">
                    <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="debug-mode">
                    <label class="form-check-label" for="debug-mode">Debug mode</label>
                    </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-10">
                  <button id="update-button" class="btn btn-primary mt-2 mb-2" type="button">
                        <span class="icon spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="text">Opdater</span>
                    </button>
                    <span id="cancel" class="m-2" style="display: none;"><a href="#">Annuller</a></span>
                </div>
              </div>
            </form>
            
            <div class="form-group" id="status-field" style="display: none;">
                Status:
                <input class="form-control" type="text" id="status" disabled placeholder="Ready..">
            </div>

            <div class="progress" style="height: 20px; display: none;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
            </div>


            

            <div id="result" style="display:none;"></div>

    <?php require('includes/footer.php'); ?>