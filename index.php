<?php 
require('includes/top.php');
$loadElements = array();
require('includes/header.php'); 
?>
    <div class="row">
        
        <div class="col-md">
            <h2 class="h6">Savner du også volleyball rekorder såsom hvem der har scoret flest point i VolleyLigaen?</h2 class="h6">

            <p>
            Denne lille side er lavet for at kunne dykke ned i volleyball statistik og rekorder fra VolleyLigaen. Denne side indsamler kamp-statistik fra alle tilgængelige volleyball sæsoner og viser dem på forskellige måder. Det betyder at du finde finde rekorder for enkelte volleyball kampe i VolleyLigaen. Du kan f.eks. finde:
            </p>
            <ul>
                <li>Hvilken mand har lavet flest esser?</li>
                <li>Hvilken kvinde har scoret angrebspoint?</li>
                <li>Hvilken mand har lavet flest per kamp?</li>
                <li>Hvilket hold har lavet flest bloks?</li>
                <li>Hvilket hold har lavet flest servefejl per kamp?</li>
                <li>Hvilken kvinde har spillet flest kampe?</li>
                <!-- <li>Hvilken mand har den bedste effektivitet i modtagningen?</li> -->
            </ul>

            <p></p>

            <p>Der indsamles kun statistik data for VolleyLigaen (inkl. slutspil, playdown og kvalifikation) og kun kampe som er færdigspillede. Pokal-kampe er pt. ikke med i alle sæsoner, men kommer det måske på et senere tidspunkt. Statistik for den indeværende volleyball sæson opdateres en gang i døgnet. Statistik fra tidligere sæsoner opdateres ved lejlighed.
            </p>

        <!--     <p>
                Der findes data for disse sæsoner:
                <ul>
                    <?php foreach($VolleyStats->getSeasonYears() as $comp): ?>
                    <li><?php echo $comp['year']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </p> -->
            <?php 
            $stats = $VolleyStats->getOverviewStats();
            ?>
            <p>
                I volleyball statistik databasen findes der i øjeblikket <b><?php echo $stats['seasons']; ?></b> sæsoner, <!-- <b><?php echo $stats['competitions']; ?></b> turneringer, --><b><?php echo $stats['teams']; ?></b> hold, <b><?php echo $stats['players']; ?></b> spillere, <b><?php echo $stats['games']; ?></b> VolleyLiga kampe og <b><?php echo $stats['player_stats']; ?></b> volleyball kamp statistik-linjer.
            </p>

            <p>
                Forslag og ideer modtages gerne på <span id="e732944326">[beskyttet e-mail adresse]</span><script type="text/javascript">/*<![CDATA[*/eval("var a=\"jKtc@iuseoPkrLgHFmUSE5b37.2BnpYTfRvZlMdG86JQAwayz4CX9_q+1DxhW0ION-V\";var b=a.split(\"\").sort().join(\"\");var c=\"Q6h1wx_0qXLy_649tQ+_\";var d=\"\";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById(\"e732944326\").innerHTML=\"<a href=\\\"mailto:\"+d+\"\\\">\"+d+\"</a>\"")/*]]>*/</script>.
            </p>
        </div>
        <div class="col-md-3">
            <img src="img/volleyball.jpg" class="img-fluid rounded" alt="Volleyball statistik i VolleyLigaen &copy; Intofoto" title="Volleyball statistik i VolleyLigaen &copy; Intofoto">
        </div>
    </div>

<?php require('includes/footer.php'); ?>