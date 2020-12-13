<?php 
require('includes/top.php');
$loadElements = array();
require('includes/header.php'); 

// $records = array(
//     array(
//         "id" => "games_played",
//         "title" => "Antal kampe spillet",
//         "measurement" => "kampe"
//     ),
// );

?>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
    <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
    <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">...</div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...</div>
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
</div>

<?php

// require('includes/records_include.php');

require('includes/footer.php'); 
?>