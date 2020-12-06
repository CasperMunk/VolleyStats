<?php require_once('includes/top.php'); 
$full_page = true;
$current_page_title = "VolleyStats - Login";
require('includes/header.php'); ?> 

  <form class="form-signin" method="POST">
    <img class="mb-4" src="img/icon-volleyball-large.png" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal"><?php echo $current_page_title; ?></h1>
    <label for="password" class="sr-only">Password</label>
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
    <div class="checkbox mb-3">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted">Login kræves for opdatering.</p>
  </form>   

<?php require('includes/footer.php'); ?>