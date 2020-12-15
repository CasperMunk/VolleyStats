<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");
require('includes/header.php');

$tabs = 
array(
    "point" => "Point",
    "serve" => "Serv",
    "receive" => "Modtagning",
    "spike" => "Angreb",
    "block" => "Blok",
);
?>

<div class="row">
    <div class="col-md">
        <nav class="nav nav-pills btn-group record-tabs mb-2 mb-md-0">
            <?php 
            foreach ($tabs as $key => $tab){
                echo '<a class="btn btn-outline-primary text-nowrap'.($key == 'point' ? ' active' : '').'" id="nav-'.$key.'-tab" data-bs-toggle="tab" href="#nav-'.$key.'" role="tab" aria-controls="nav-point">'.$tab.'</a>';
            }
            ?>
        </nav>
    </div>
    <div class="col-md text-md-end">
        <?php include("includes/gender_picker.php") ?>
    </div>
</div>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-point" role="tabpanel" aria-labelledby="nav-point-tab">
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
    <div class="tab-pane fade" id="nav-serve" role="tabpanel" aria-labelledby="nav-serve-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-receive" role="tabpanel" aria-labelledby="nav-receive-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-spike" role="tabpanel" aria-labelledby="nav-spike-tab">
        Kommer snart..
    </div>
    <div class="tab-pane fade" id="nav-block" role="tabpanel" aria-labelledby="nav-block-tab">
        Kommer snart..
    </div>
</div>

<?php require('includes/footer.php');