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
		        "id" => "points_total",
		        "title" => "Antal point i en kamp",
		        "measurement" => "point"
	    	)
	    );
	    $VolleyStats->printRecordTable(
			array(
		        "id" => "break_points",
		        "title" => "Antal BP i en kamp",
		        "measurement" => "BP"
		    )
	    );
	    $VolleyStats->printRecordTable(
			array(
		        "id" => "win_loss",
		        "title" => "Højeste V-T i en kamp",
		        "measurement" => "V-T"
		    )
	    );
		?>
	</div>
	<div class="tab-pane fade" id="nav-serve" role="tabpanel" aria-labelledby="nav-serve-tab">
		<?php 
		$VolleyStats->printRecordTable(
			array(
		        "id" => "serve_total",
		        "title" => "Antal server i en kamp",
		        "measurement" => "server"
		    )
		);
		$VolleyStats->printRecordTable(
			array(
		        "id" => "serve_ace",
		        "title" => "Antal esser i en kamp",
		        "measurement" => "esser"
		    )
		);
		?>
	</div>
	<div class="tab-pane fade" id="nav-receive" role="tabpanel" aria-labelledby="nav-receive-tab">
		<?php 
		$VolleyStats->printRecordTable(
			array(
		        "id" => "receive_total",
		        "title" => "Antal modtagninger i en kamp",
		        "measurement" => "modtagninger"
		    )
		);
		$VolleyStats->printRecordTable(
			array(
		        "id" => "receive_perfect",
		        "title" => "Antal perfekte modtagning i en kamp",
		        "measurement" => "modtagninger"
		    )
		);
		?>
	</div>
	<div class="tab-pane fade" id="nav-spike" role="tabpanel" aria-labelledby="nav-spike-tab">
		<?php 
		$VolleyStats->printRecordTable(
			array(
		        "id" => "spike_total",
		        "title" => "Antal hævninger i en kamp",
		        "measurement" => "hævninger"
		    )
		);
		$VolleyStats->printRecordTable(
			array(
		        "id" => "spike_win",
		        "title" => "Antal vundne angreb i en kamp",
		        "measurement" => "angreb"
		    )
		);
		?>
	</div>
	<div class="tab-pane fade" id="nav-block" role="tabpanel" aria-labelledby="nav-block-tab">
		<?php 
		$VolleyStats->printRecordTable(
			array(
		        "id" => "block_win",
		        "title" => "Antal bloks i en kamp",
		        "measurement" => "bloks"
		    )
		);
		?>
	</div>
</div>

<?php require('includes/footer.php');