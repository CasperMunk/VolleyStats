<?php 
require('includes/top.php');
$loadElements = array();
$page_info = array(
    'title' => 'Statistik og rekorder fra VolleyLigaen',
);
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

            <?php 
            $stats = $VolleyStats->getOverviewStats();
            ?>
            <p>
                I volleyball statistik databasen findes der i øjeblikket <b><?php echo $stats['seasons']; ?></b> sæsoner, <!-- <b><?php echo $stats['competitions']; ?></b> turneringer, --><b><?php echo $stats['teams']; ?></b> hold, <b><?php echo $stats['players']; ?></b> spillere, <b><?php echo $stats['games']; ?></b> VolleyLiga kampe og <b><?php echo $stats['player_stats']; ?></b> volleyball kamp statistik-linjer.
            </p>

            <p>
                Forslag og ideer modtages gerne på <script type="text/javascript">document.write('<'+'a'+' '+'h'+'r'+'e'+'f'+'='+"'"+'m'+'a'+'i'+'l'+'t'+'o'+':'+'c'+'a'+'s'+'p'+'e'+'r'+'m'+'u'+'n'+'k'+'@'+'g'+'m'+'a'+'i'+'l'+'.'+'c'+'o'+'m'+"'"+'>'+'c'+'a'+'s'+'p'+'e'+'r'+'m'+'u'+'n'+'k'+'@'+'g'+'m'+'a'+'i'+'l'+'.'+'c'+'o'+'m'+'<'+'/'+'a'+'>');</script>.
            </p>
        </div>
        <div class="col-md-3">
            <img src="img/volleyball.jpg" class="img-fluid rounded" alt="Volleyball statistik i VolleyLigaen &copy; Intofoto" title="Volleyball statistik i VolleyLigaen &copy; Intofoto">
        </div>
    </div>

<?php require('includes/footer.php'); ?>