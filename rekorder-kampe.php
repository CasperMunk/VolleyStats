<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");
require('includes/header.php');

$tabs = 
array(
    "games" => "Kampe",
    "sets" => "Sæt",
    "point-score" => "Point-score",
    "spectators" => "Tilskuere",
    "streaks" => "Streaks",
);
?>

<div class="row">
    <div class="col-md">
        <nav class="nav nav-pills btn-group record-tabs mb-2 mb-md-0">
            <?php 
            foreach ($tabs as $key => $tab){
                echo '<a class="btn btn-outline-primary text-nowrap'.($key == 'games' ? ' active' : '').'" id="nav-'.$key.'-tab" data-bs-toggle="tab" href="#nav-'.$key.'" role="tab" aria-controls="nav-point">'.$tab.'</a>';
            }
            ?>
        </nav>
    </div>
    <div class="col-md text-md-end">
        <?php include("includes/gender_picker.php") ?>
    </div>
</div>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-games" role="tabpanel" aria-labelledby="nav-games-tab">
        <?php 
        $VolleyStats->printRecordTable(
            array(
                "id" => "games_played",
                "title" => "Antal kampe spillet",
                "measurement" => "kampe"
            )
        );
        ?>
    </div>
    <div class="tab-pane fade" id="nav-sets" role="tabpanel" aria-labelledby="nav-sets-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-point-score" role="tabpanel" aria-labelledby="nav-point-score-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-spectators" role="tabpanel" aria-labelledby="nav-spectators-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-streaks" role="tabpanel" aria-labelledby="nav-streaks-tab">
        Kommer snart..
    </div>
</div>

<?php require('includes/footer.php');