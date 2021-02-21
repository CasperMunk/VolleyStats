<?php 
require('includes/top.php'); 
require('includes/header.php');

$faq = array(
    array(
        'q' => 'Jeg har fundet en fejl. Hvad gør jeg?',
        'a' => 'Forslag og fejlrettelser modtages meget gerne! Smid mig en mail.'
    ),
    array(
        'q' => 'Er nogle kampe undtaget?',
        'a' => 'Ja, enkelte kampe er undtaget hvis de har meget misvisende statistik. Nogle kampe er også undtaget rekord-listerne hvis rekorden er misvisende for resultatet.'
    ),
    array(
        'q' => 'Hvorfor har du lavet siden?',
        'a' => 'Sammen med nogle venner kom vi til at snakke om hvad rekorden for flest esser i en kamp mon var. Så kom jeg i tanke om at der jo ligger mange års statistik på alle kampe online. Så jeg lavede en simpel web-scraper som kunne tjekke server og siden da har det hele udviklet sig lidt. :)'
    ),
    array(
        'q' => 'Er det Open Source?',
        'a' => 'Ja. Alle data på denne side kan frit bruges af andre. Kildekoden til hele sitet er offentlig tilgængeligt på <a href="https://github.com/CasperMunk/VolleyStats">https://github.com/CasperMunk/VolleyStats</a>. Ønsker du at genbruge kildekoden, så lav et fork på Github og smid mig en mail, så kan du få en kopi af databasen og konfigurationsfilen (som ikke ligger på Github).'
    ),
    array(
        'q' => 'Kan man regne med statistikken?',
        'a' => 'Det akkumullerede data er naturligvis kun så god som det statistik der indsamles fra kampene. I de tidlige år (specielt 2014/2015 og bagud) er data-kvaliteten ikke så god som de seneste år. Nogle kampe er derfor sorterede fra ligesom der er lavet meget arbejde med rengøring af data for at forbedre kvaliteten.'
    ),
    array(
        'q' => 'Hvor tit opdateres statistikken?',
        'a' => 'Dagens kampe opdateres en gang pr. minut. Hele sæsonens kampe opdateres en gang i døgnet og data fra tidligere sæsonen opdateres kun efter behov.'
    ),
    array(
        'q' => 'Hvad skal alt dette bruges til?',
        'a' => 'Det var egentlig mest lavet for sjov og fordi jeg havde Corona-kuller, men jeg forestiller mig at sitet kan bruges både af scouts og folk som er interesserede i rekorder fra VolleyLigaen.'
    ),
);
?>

<p>Herunder finder du svar på de mest stillede spørgsmål. Se i øvrigt også <a href="/betingelser">betingelser for brug</a>. Har du et spørgsmål så send mig en mail på <span id="e732944326">[beskyttet e-mail adresse]</span><script type="text/javascript">/*<![CDATA[*/eval("var a=\"jKtc@iuseoPkrLgHFmUSE5b37.2BnpYTfRvZlMdG86JQAwayz4CX9_q+1DxhW0ION-V\";var b=a.split(\"\").sort().join(\"\");var c=\"Q6h1wx_0qXLy_649tQ+_\";var d=\"\";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById(\"e732944326\").innerHTML=\"<a href=\\\"mailto:\"+d+\"\\\">\"+d+\"</a>\"")/*]]>*/</script>.</p>
<div class="accordion w-50" id="FAQ_accordion">
    <div class="accordion-item">
        <?php foreach($faq as $key => $item): ?>
        <h2 class="accordion-header" id="FAQ_Heading_<?php echo $key; ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#FAQ_collapse_<?php echo $key; ?>">
                <?php echo $item['q']; ?>
            </button>
        </h2>

        <div id="FAQ_collapse_<?php echo $key; ?>" class="accordion-collapse collapse" data-bs-parent="#FAQ_accordion">
            <div class="accordion-body">
                <?php echo $item['a']; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require('includes/footer.php'); ?>
