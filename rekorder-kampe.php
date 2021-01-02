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
                echo '<a class="btn btn-outline-primary text-nowrap'.($key == 'games' ? ' active' : '').'" id="nav-'.$key.'-tab" data-bs-toggle="tab" href="#nav-'.$key.'">'.$tab.'</a>';
            }
            ?>
        </nav>
    </div>
    <div class="col-md text-md-end">
        <?php include("includes/gender_picker.php") ?>
    </div>
</div>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-games">
        <?php 
        
        ?>
    </div>
    <div class="tab-pane fade" id="nav-sets">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-point-score">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-spectators">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-streaks">
        Kommer snart..
    </div>
</div>

<?php require('includes/footer.php');