    <?php if ($full_page == false): ?>
        </div>
    </main>

    <!-- <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="float-start text-muted text-start small">Lavet af Casper Munk<span class="d-none d-md-inline"> Christiansen</span></div>
            <div class="float-end text-muted text-end small"><a href="/betingelser" class="text-muted">Betingelser<span class="d-none d-md-inline"> for brug</span></a></div>
        </div>
    </footer> -->

    <footer class="mt-5 py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="/betingelser" class="nav-link px-2 text-muted">Betingelser</a></li>
      <li class="nav-item"><a href="/faq" class="nav-link px-2 text-muted">FAQ</a></li>
      <li class="nav-item"><a href="/kontakt" class="nav-link px-2 text-muted">Kontakt</a></li>
    </ul>
    <p class="text-center text-muted small">
        <img src="/img/icon-volleyball.svg" alt="Volleyball Icon" height="25" width="25" class="mb-2"><br>
        VolleyStats.dk
    </p>
  </footer>

    <?php endif; ?>

    
    <?php if (in_array('jQuery',$loadElements)): ?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (in_array('DataTables',$loadElements)): ?>

        <link rel="preload" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/fh-3.2.2/r-2.2.9/datatables.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/fh-3.2.2/r-2.2.9/datatables.min.css"></noscript>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/fh-3.2.2/r-2.2.9/datatables.min.js"></script>
        
        <?php if (isset($dataTable)) echo $dataTable->jsFile; ?>
    <?php endif; ?>

    <?php if (in_array('updater.js',$loadElements)): ?>
        <script async type="text/javascript" src="/js/updater.js"></script>
    <?php endif; ?>

    <?php if (in_array('records.js',$loadElements)): ?>
        <script async type="text/javascript" src="/js/records.js"></script>
    <?php endif; ?>
    
    <?php if (!is_local()): ?>
    <script async id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="8e413f14-cb38-4f21-b272-ef107fbe4387" data-blockingmode="auto" type="text/javascript"></script>
    <?php endif; ?>
    
</body>
</html>