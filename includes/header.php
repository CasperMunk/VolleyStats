<?php
if (!is_array($page_info)) $page_info = array();
$page_header_title = isset($page_info['title']) ? $page_info['title'] : 'NO TITLE';
$page_title_tag = isset($page_info['title_tag']) ? $page_info['title_tag'] : $page_info['title'];
$page_meta_description = isset($page_info['meta_description']) ? $page_info['meta_description'] : '';
?>
<!doctype html>
<html lang="da">

<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CB8VY69MBD"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-CB8VY69MBD');
    </script>

    <title><?php echo $page_title_tag; ?> - VolleyStats.dk</title>
    <meta name="description" content="<?php echo $page_meta_description; ?>">

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/css/styles.css" />

    <meta name="theme-color" content="#ffffff" />
    <link rel="icon" href="/img/icon-volleyball.svg" />
    <link rel="mask-icon" href="/img/icon-volleyball.svg" color="#ffffff" />
    <link rel="apple-touch-icon" href="/img/apple-touch-icon.png" />
    <link rel="manifest" href="/img/manifest.json" />
</head>

<body class="d-flex flex-column min-vh-100<?php if ($full_page) echo ' fullpage' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="people-circle" fill="currentColor" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
        </symbol>
        <symbol id="people-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
        </symbol>
        <symbol id="list-ol" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z" />
            <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338v.041zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635V5z" />
        </symbol>
        <symbol id="file-person" fill="currentColor" viewBox="0 0 16 16">
            <path d="M12 1a1 1 0 0 1 1 1v10.755S12 11 8 11s-5 1.755-5 1.755V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z" />
            <path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
        </symbol>
    </svg>
    <?php if ($full_page == false) : ?>

        <header class="bg-dark">
            <div class="px-3 py-2 text-white">
                <div class="container">
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        <a href="/" class="d-flex fs-4 align-items-center my-2 my-lg-0 me-lg-auto text-decoration-none text-white">
                            <img src="/img/icon-volleyball.svg" alt="Volleyball Icon" height="25" width="25" class="me-1">
                            VolleyStats<?php if (is_local()) echo '<span class="text-warning">.local</span>'; ?>
                        </a>
                        </a>

                        <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg class="bi d-block mx-auto mb-1 text-white" width="24" height="24">
                                        <use xlink:href="#list-ol"></use>
                                    </svg>
                                    Rekorder
                                </a>
                                <ul class="dropdown-menu" data-bs-popper="none">
                                    <li><a class="dropdown-item" href="/rekorder-spiller-statistik">Rekorder for spillere</a></li>
                                    <li><a class="dropdown-item" href="/rekorder-kampe">Rekorder for kampe</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg class="bi d-block mx-auto mb-1 text-white" width="24" height="24">
                                        <use xlink:href="#people-circle"></use>
                                    </svg>
                                    Spiller-data
                                </a>
                                <ul class="dropdown-menu" data-bs-popper="none">
                                    <li><a class="dropdown-item" href="/data-spiller-totalt">Spillere totalt</a></li>
                                    <li><a class="dropdown-item" href="/data-spiller-per-kamp">Spillere pr. kamp</a></li>
                                    <li><a class="dropdown-item" href="/data-spiller-per-set">Spillere pr. sæt</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg class="bi d-block mx-auto mb-1 text-white" width="24" height="24">
                                        <use xlink:href="#people-fill"></use>
                                    </svg>
                                    Hold-data
                                </a>
                                <ul class="dropdown-menu" data-bs-popper="none">
                                    <li><a class="dropdown-item" href="/data-hold-totalt">Hold totalt</a></li>
                                    <li><a class="dropdown-item" href="/data-hold-per-kamp">Hold pr. kamp</a></li>
                                    <li><a class="dropdown-item" href="/data-hold-per-set">Hold pr. sæt</a></li>
                                </ul>
                            </li>
                            <!-- <li class="nav-item dropdown">
                                <a href="#" class="nav-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                                    <svg class="bi d-block mx-auto mb-1 text-white" width="24" height="24">
                                        <use xlink:href="#file-person"></use>
                                    </svg>
                                    Dommer-data
                                </a>
                                <ul class="dropdown-menu" data-bs-popper="none">
                                    <li><a class="dropdown-item" href="/data-dommer-totalt">Dommere totalt</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>

        </header>

        <!-- Begin page content -->
        <main class="flex-shrink-0">
            <div class="container">
                <h1 class="h2"><?php echo $page_header_title; ?></h1>
            <?php endif; ?>