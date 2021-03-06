<!doctype html>
<html lang="da">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CB8VY69MBD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-CB8VY69MBD');
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $current_page['title'] ?> - VolleyStats.dk</title>
    <meta name="description" content="<?php echo $current_page['meta_description'] ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css" />

    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="img/icon-volleyball.svg">
    <link rel="mask-icon" href="img/icon-volleyball.svg" color="#ffffff">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="manifest" href="img/manifest.json">
</head>
<body class="d-flex flex-column min-vh-100<?php if ($full_page) echo ' fullpage' ?>">
    <?php if ($full_page == false): ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand<?php if (is_local()) echo ' text-warning';?>" href="/">
                    <img src="img/icon-volleyball.svg" alt="Volleyball Icon" height="25" width="25" class="me-1">
                    <?php if (is_local()){ echo 'VolleyStats.local';}else{echo 'VolleyStats';}?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php 
                        // echo '<pre>';
                        // print_r($pages);
                        foreach ($pages as $key => $naviarray){ 
                            if (isset($naviarray['exclude_from_navi'])) continue;
                            if (!isset($naviarray['items'])){
                                echo '
                                    <li class="nav-item">
                                        <a class="nav-link'.(($script_name == $naviarray['filename'])?' active':'').'" href="'.$naviarray['url'].'">'.$naviarray['navi_title'].'</a>
                                    </li>
                                ';
                            }else{
                                echo '
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        '.$naviarray['navi_title'].'
                                    </a>
                                    <ul class="dropdown-menu">';
                                    foreach ($naviarray['items'] as $subnaviarray) {
                                        if ($subnaviarray['navi_title'] == "<divider>"){
                                            echo '<li><hr class="dropdown-divider"></li>';    
                                        }else{
                                            echo '<li><a class="dropdown-item" href="'.$subnaviarray['url'].'">'.$subnaviarray['navi_title'].'</a></li>';
                                        }
                                    }
                                    echo '
                                    </ul>
                                </li>
                                ';
                            }
                        }
                        ?>                        
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Begin page content -->
    <main class="flex-shrink-0">
        <div class="container">
            <h1 class="h2"><?php echo $current_page['title'] ?></h1>
    <?php endif; ?>