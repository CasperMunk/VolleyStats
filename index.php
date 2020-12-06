<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?>

<p>
Denne lille service er lavet for at give et overblik over spillere og kampe i VolleyLigaen. Der indsamles kun data for VolleyLigaen og kun kampe som er færdigspillede. Data for den indeværende sæson opdateres en gang i døgnet. Data fra tidligere sæsoner opdateres ved lejlighed.
</p>

<p>
    Der findes data for nedenstående sæsoner:
    <ul>
        <?php foreach($VolleyStats->getSeasonYears() as $comp): ?>
        <li><?php echo $comp['year']; ?></li>
        <?php endforeach; ?>
    </ul>
</p>
<?php 
$stats = $VolleyStats->getOverviewStats();
?>
<p>
    I databasen findes der i øjeblikket <b><?php echo $stats['competitions']; ?></b> turneringer, <b><?php echo $stats['teams']; ?></b> hold, <b><?php echo $stats['players']; ?></b> spillere, <b><?php echo $stats['games']; ?></b> kampe og <b><?php echo $stats['player_stats']; ?></b> statistik-linjer.
</p>

<p>
    Se venligst <a href="conditions.php">betingelser for brug</a>.
</p>

<?php require('includes/footer.php'); ?>