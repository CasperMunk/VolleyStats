<div id="gender_picker" class="btn-group btn-group-toggle btn-sm" data-toggle="buttons">
    <span class="input-group-text">Filter:</span>
    <label class="btn btn-light active">
        <input type="radio" name="gender_picker" value="all" autocomplete="off" checked="checked"> Begge
    </label>
    <label class="btn btn-light ">
        <input type="radio" name="gender_picker" value="male" autocomplete="off"> Mand
    </label>
    <label class="btn btn-light ">
        <input type="radio" name="gender_picker" value="female" autocomplete="off"> Kvinde
    </label>
</div>

<?php foreach ($records as $record): ?>
    <h3><?php echo $record['title']?></h3>
    <ol class="records">
        <?php foreach ($VolleyStats->getRecords($record['id']) as $result): ?>
            <li class="<?php echo $result['gender']; ?>">
                <span class="player_name"><?php echo $VolleyStats->reverseName($result['player_name']); ?></span>
                <span class="description">(<?php echo $result[$record['id']]; ?> <?php echo $record['measurement']?>)</span>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endforeach; ?>

<script>
$(document).ready( function () {
    $("input[type='radio'][name='gender_picker']").change(function() {
        var val = $("input[type='radio'][name='gender_picker']:checked").val();
        $("ol.records").each(function(){
            $(this).children("li").removeClass("hidden bold");
            if (val != 'all') {            
                $(this).children("li:not(."+val+")").addClass("hidden");
            }
            $(this).children("li:not(.hidden)").slice(10).addClass("hidden");
            $(this).children("li:not(.hidden)").slice(0,1).addClass("bold");
        });
    });

    $("input[type='radio'][name='gender_picker']").trigger("change");
});
</script>