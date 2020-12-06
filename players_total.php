<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?> 

<script>
$(document).ready( function () {
    var table = $('#table_players_total').DataTable({
        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Alle"]],
        "aoColumns": [
            null,
            null,
            { "orderSequence": [ "desc", "asc" ] },
            { "orderSequence": [ "desc", "asc" ] },
            { "orderSequence": [ "desc", "asc" ] }
        ],
        "fixedHeader": true,
        "stateSave": true,
        "language": {
            "url": '//cdn.datatables.net/plug-ins/1.10.22/i18n/Danish.json',
            "decimal": ",",
            "thousands": ".",
            "buttons": {
                "colvis": 'Skift kolonner',
                "colvisRestore": 'Nulstil kolonner'
            }
        },
        "dom": "fBrtilp",
        "buttons": [
            {
                extend: 'colvis',
                postfixButtons: [ 'colvisRestore' ]
            }
        ],
    });

    table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-6:eq(0)' );
});
</script>

<table id="table_players_total" class="table table-striped table-sm table-bordered compact" style="width:100%">
    <thead>
        <tr>
            <th>Spillernavn</th>
            <th>Køn</th>
            <th>Kampe spillet</th>
            <th>Points</th>
            <th>Esser</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($result = $VolleyStats->db->query("SELECT players.*, SUM(player_stats.points_total) as points_total, SUM(player_stats.serve_ace) as serve_ace, COUNT(player_stats.player_id) as games_played from players
LEFT JOIN player_stats ON players.id = player_stats.player_id
GROUP BY players.id
ORDER BY points_total DESC")) {
        if ($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>".$VolleyStats->reverseName($row['player_name'])."</td>
                    <td>".ucfirst($VolleyStats->translateText($row['gender']))."</td>
                    <td>".$VolleyStats->formatNumber($row['games_played'])."</td>
                    <td>".$VolleyStats->formatNumber($row['points_total'])."</td>
                    <td>".$VolleyStats->formatNumber($row['serve_ace'])."</td>
                </tr>
                ";
            }
        }
    }
    ?> 
    </tbody>
</table>
            
<p>
 


<?php require('includes/footer.php'); ?>