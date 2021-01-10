<?php 
require('includes/top.php'); 
require('includes/header.php');

$faq = array(
    array(
        'q' => 'Jeg har fundet en fejl. Hvad gør jeg?',
        'a' => 'Forslag og fejlrettelser modtages meget gerne! Smid mig en mail på <span id="e732944326">[beskyttet e-mail adresse]</span><script type="text/javascript">/*<![CDATA[*/eval("var a=\"jKtc@iuseoPkrLgHFmUSE5b37.2BnpYTfRvZlMdG86JQAwayz4CX9_q+1DxhW0ION-V\";var b=a.split(\"\").sort().join(\"\");var c=\"Q6h1wx_0qXLy_649tQ+_\";var d=\"\";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById(\"e732944326\").innerHTML=\"<a href=\\\"mailto:\"+d+\"\\\">\"+d+\"</a>\"")/*]]>*/</script>.'
    ),
    array(
        'q' => 'Er nogle kampe undtaget?',
        'a' => 'Ja, enkelte kampe er undtaget hvis de har meget misvisende statistik.'
    ),
);
?>

<div class="accordion" id="FAQ_accordion">
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
