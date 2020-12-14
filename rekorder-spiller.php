<?php 
require('includes/top.php');
$loadElements = array("jQuery","records.js");
require('includes/header.php');
?>
<div class="float-end">
	<?php include("includes/gender_picker.php") ?>
</div>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">
		<a class="nav-link active" id="nav-point-tab" data-bs-toggle="tab" href="#nav-point" role="tab" aria-controls="nav-point" aria-selected="false">Point</a>
		<a class="nav-link" id="nav-serve-tab" data-bs-toggle="tab" href="#nav-serve" role="tab" aria-controls="nav-serve" aria-selected="false">Serv</a>
		<a class="nav-link" id="nav-receiving-tab" data-bs-toggle="tab" href="#nav-receiving" role="tab" aria-controls="nav-receiving" aria-selected="false">Modtagning</a>
		<a class="nav-link" id="nav-spike-tab" data-bs-toggle="tab" href="#nav-spike" role="tab" aria-controls="nav-spike" aria-selected="false">Angreb</a>
		<a class="nav-link" id="nav-block-tab" data-bs-toggle="tab" href="#nav-block" role="tab" aria-controls="nav-block" aria-selected="false">Blok</a>
	</div>
</nav>
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
	<div class="tab-pane fade" id="nav-receiving" role="tabpanel" aria-labelledby="nav-receiving-tab">
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