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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $current_page_title ?> - VolleyStats</title>
    <!-- <link rel="stylesheet" href="css/bootstrap.darkly.min.css" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css" />

    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="img/icon-volleyball.svg">
    <link rel="mask-icon" href="img/icon-volleyball.svg" color="#ffffff">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="manifest" href="img/manifest.json">
</head>
<body class="d-flex flex-column h-100<?php if ($full_page) echo ' fullpage' ?>">
    <?php if ($full_page == false): ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="img/icon-volleyball.svg" alt="Volleyball Icon" height="25" border="none" / class="me-1">VolleyStats
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php 
                        foreach ($navigation as $url => $name){ 
                            if (!is_array($name)){
                                echo '
                                    <li class="nav-item">
                                        <a class="nav-link '.(($current_page == $url)?' active':'').'" aria-current="page" href="'.$url.'">'.$name.'</a>
                                    </li>
                                ';
                            }else{
                                echo '
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown'.$url.'" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        '.$url.'
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">';

                                    foreach ($name as $dropdown_item_url => $dropdown_item_name) {
                                        if ($dropdown_item_url == "<divider>"){
                                            echo '<li><hr class="dropdown-divider"></li>';    
                                        }else{
                                            echo '<li><a class="dropdown-item" href="'.$dropdown_item_url.'">'.$dropdown_item_name.'</a></li>';
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
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <h1><?php echo $current_page_title ?></h1>
    <?php endif; ?>