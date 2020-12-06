<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?>

<p>
Denne lille side er lavet for at kunne dybbe ned i statistik fra VolleyLigaen. Der indsamles kun data for VolleyLigaen og kun kampe som er færdigspillede. Data for den indeværende sæson opdateres en gang i døgnet. Data fra tidligere sæsoner opdateres ved lejlighed.
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
    Forslag og ideer modtages gerne på <span id="e732944326">[javascript protected email address]</span><script type="text/javascript">/*<![CDATA[*/eval("var a=\"jKtc@iuseoPkrLgHFmUSE5b37.2BnpYTfRvZlMdG86JQAwayz4CX9_q+1DxhW0ION-V\";var b=a.split(\"\").sort().join(\"\");var c=\"Q6h1wx_0qXLy_649tQ+_\";var d=\"\";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById(\"e732944326\").innerHTML=\"<a href=\\\"mailto:\"+d+\"\\\">\"+d+\"</a>\"")/*]]>*/</script>.
</p>

<p>
    Se venligst <a href="conditions.php">betingelser for brug</a>.
</p>

<?php require('includes/footer.php'); ?>