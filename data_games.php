<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?> 

<script>
$(document).ready( function () {
    $('#example').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
            url: '//cdn.datatables.net/plug-ins/1.10.22/i18n/Danish.json'
        }
    });
});
</script>

<table id="example" class="table table-striped table-sm table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Spiller</th>
            <th>Køn</th>
            <th>Kampe spillet</th>
            <th>Total points</th>
            <th>Total esser</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($result = $VolleyStats->db->query("SELECT players.*, SUM(player_stats.total_points) as total_points, SUM(player_stats.serve_aces) as serve_aces, COUNT(player_stats.total_points) as games_played from players
LEFT JOIN player_stats ON players.id = player_stats.player_id
GROUP BY players.id
ORDER BY total_points DESC")) {
        if ($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>".$VolleyStats->reverseName($row['player_name'])."</td>
                    <td>".ucfirst($row['gender'])."</td>
                    <td>".$row['games_played']."</td>
                    <td>".$row['total_points']."</td>
                    <td>".$row['serve_aces']."</td>
                </tr>
                ";
            }
        }
    }
    ?> 
    </tbody>
</table>
            

 


<?php require('includes/footer.php'); ?>