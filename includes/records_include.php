<div id="gender_picker" class="btn-group" role="group" aria-label="Basic radio toggle button group">
    <input type="radio" class="btn-check" name="gender_picker" value="all" id="gender_picker1" autocomplete="off" checked>
    <label class="btn btn-outline-primary" for="gender_picker1">Begge køn</label>

    <input type="radio" class="btn-check" name="gender_picker" value="female" id="gender_picker2" autocomplete="off">
    <label class="btn btn-outline-primary" for="gender_picker2">Kvinder</label>

    <input type="radio" class="btn-check" name="gender_picker" value="male" id="gender_picker3" autocomplete="off">
    <label class="btn btn-outline-primary" for="gender_picker3">Mænd</label>    
</div>

<!-- <div id="gender_picker" class="btn-group btn-group-toggle btn-sm" data-toggle="buttons">
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
</div> -->

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