<?php 
require('includes/top.php');
$full_page = true;
$page_info['title'] = "Login";
require('includes/header.php'); 
?> 

  <form class="form-signin" method="POST">
    <img class="mb-4" src="/img/icon-volleyball.svg" alt="" width="72" height="72">
    <h1 class="h3 mb-3 font-weight-normal"><?php echo $page_info['title']; ?></h1>
    <label for="password" class="sr-only">Password</label>
    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
    <div class="checkbox mb-3">
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <p class="mt-5 mb-3 text-muted">Login kræves for opdatering.</p>
  </form>   

<?php require('includes/footer.php'); ?>