    <?php if ($full_page == false): ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="float-start text-muted text-start small">Lavet af Casper Munk<span class="d-none d-md-inline"> Christiansen</span></div>
            <div class="float-end text-muted text-end small"><a href="betingelser" class="text-muted">Betingelser<span class="d-none d-md-inline"> for brug</span></a></div>
        </div>
    </footer>
    <?php endif; ?>

    <script async src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <?php if (in_array('jQuery',$loadElements)): ?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <?php endif; ?>

    <?php if (in_array('DataTables',$loadElements)): ?>
        <link rel="preload" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/fh-3.1.8/r-2.2.7/datatables.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/fh-3.1.8/r-2.2.7/datatables.min.css"></noscript>

        <link rel="preload" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css"></noscript>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/b-1.6.5/b-colvis-1.6.5/b-html5-1.6.5/fh-3.1.8/r-2.2.7/datatables.min.js"></script>
        <script async type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>

        <script src="https://kit.fontawesome.com/61eb357e22.js" crossorigin="anonymous"></script>
        
        <?php if (isset($dataTable)) echo $dataTable->jsFile; ?>
    <?php endif; ?>

    <?php if (in_array('updater.js',$loadElements)): ?>
        <script async type="text/javascript" src="js/updater.js"></script>
    <?php endif; ?>

    <?php if (in_array('records.js',$loadElements)): ?>
        <script type="text/javascript" src="js/records.js"></script>
    <?php endif; ?>
    
    <?php if (!is_local()): ?>
    <script async id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="8e413f14-cb38-4f21-b272-ef107fbe4387" data-blockingmode="auto" type="text/javascript"></script>
    <?php endif; ?>
    
</body>
</html>