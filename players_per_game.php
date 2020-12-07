<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?> 

<p>
    Spillere med under <input type="text" class="input-small text-center" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="played_games_min" value="10" size="3"> kampe er undtaget fra denne liste.
</p>

<script>
$(document).ready( function () {
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#played_games_min').val(), 10 );
            // var max = parseInt( $('#max').val(), 10 );
            var max = parseInt( '', 10 );
            var games_played = parseFloat( data[3] ) || 0; // use data from the games played column
     
            if ( ( isNaN( min ) && isNaN( max ) ) ||
                 ( isNaN( min ) && games_played <= max ) ||
                 ( min <= games_played   && isNaN( max ) ) ||
                 ( min <= games_played   && games_played <= max ) )
            {
                return true;
            }
            return false;
        }
    );

    $("input#played_games_min").keyup( function() {
        dataTable.draw();
    } );

    var dataTable = $('#table_players_per_game').DataTable({
        "responsive": true,
        "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "Alle"]],
        "fixedHeader": {
            headerOffset: $('nav.navbar').outerHeight()
        },
        // "stateSave": true,
        "language": {
            "url": '//cdn.datatables.net/plug-ins/1.10.22/i18n/Danish.json',
            "decimal": ",",
            "thousands": "."
        },
        "dom": "Bfrtilp",
        "buttons": [
            {
                text: 'Generelt',
                extend: 'colvis',
                columns: '.colvisGroupGeneral'
            },
            {
                text: 'Point',
                extend: 'colvis',
                columns: '.colvisGroupPoints'
            },
            {
                text: 'Serv',
                extend: 'colvis',
                columns: '.colvisGroupServe'
            },
            {
                text: 'Modtagning',
                extend: 'colvis',
                columns: '.colvisGroupReceive'
            },
            {
                text: 'Angreb',
                extend: 'colvis',
                columns: '.colvisGroupSpike'
            },
            {
                text: 'Blok',
                extend: 'colvis',
                columns: '.colvisGroupBlock'
            },
            {
                text: 'Nulstil',
                extend: 'colvisRestore',
            }
        ],
        columnDefs: [
            {
                "targets": [ 0,1,3,5,11,19,20 ],
                "visible": true
            },
            {
                "targets": "_all",
                "visible": false
            },
            {
                "targets": [0],
                "className": 'noVis'
            },
            {
                "targets": [1,2,3,4],
                "className": 'colvisGroupGeneral'
            },
            {
                "targets": [5,6,7,8],
                "className": 'colvisGroupPoints'
            },
            {
                "targets": [9,10,11],
                "className": 'colvisGroupServe'
            },
            {
                "targets": [12,13,14,15],
                "className": 'colvisGroupReceive'
            },
            {
                "targets": [16,17,18,19],
                "className": 'colvisGroupSpike'
            },
            {
                "targets": [20],
                "className": 'colvisGroupBlock'
            },
            { 
                "targets": [0] ,
                "orderSequence": [ "asc","desc" ]
                
            },
            { 
                "targets": "_all" ,
                "orderSequence": [ "desc","asc" ]
                
            },
        ],
        "order": [[ 5, "desc" ]],
    });
});
</script>

<table id="table_players_per_game" class="table table-striped table-sm table-bordered compact" style="width:100%">
    <thead>
        <tr>
            <th rowspan="2">Spillernavn</th>
            <th colspan="4">Generelt</th>
            <th colspan="4">Point</th>
            <th colspan="3">Serv</th>
            <th colspan="4">Modtagning</th>
            <th colspan="4">Angreb</th>
            <th colspan="1">Blok</th>
        </tr>
        <tr>
            <! -- Generelt -->
            <th>Køn</th>
            <th>Sæsoner spillet</th>
            <th>Kampe spillet</th>
            <th>Antal klubber</th>
            
            <! -- Point -->
            <th>Total</th>
            <th>Fejl</th>
            <th>BP</th>
            <th>VT</th>

            <! -- Serv -->
            <th>Total</th>
            <th>Fejl</th>
            <th>Es</th>
    
            <! -- Modtagning -->
            <th>Total</th>
            <th>Fejl</th>
            <th>Pos</th>
            <th>Perf</th>

            <! -- Angreb -->
            <th>Total</th>
            <th>Fejl</th>
            <th>Blok</th>
            <th>Perf</th>

            <! -- Blok -->
            <th>Point</th>            
        </tr>
    </thead>
    <tbody>
    <?php 
    if ($result = $VolleyStats->db->query("
        SELECT 
            players.* 
            ,COUNT(player_stats.player_id) as games_played 

            ,SUM(player_stats.points_total) as points_total
            ,SUM(player_stats.recieve_error+player_stats.spike_error+player_stats.serve_error) as error_total
            ,SUM(player_stats.break_points) as break_points 
            ,SUM(player_stats.win_loss) as win_loss

            ,SUM(player_stats.serve_total) as serve_total 
            ,SUM(player_stats.serve_ace) as serve_ace 
            ,SUM(player_stats.serve_error) as serve_error

            ,SUM(player_stats.recieve_total) as recieve_total 
            ,SUM(player_stats.recieve_position) as recieve_position 
            ,SUM(player_stats.recieve_perfect) as recieve_perfect
            ,SUM(player_stats.recieve_error) as recieve_error 
            
            ,SUM(player_stats.spike_total) as spike_total 
            ,SUM(player_stats.spike_win) as spike_win 
            ,SUM(player_stats.spike_error) as spike_error 
            ,SUM(player_stats.spike_blocked) as spike_blocked             
            
            ,SUM(player_stats.block_win) as block_win 
                  
        FROM players
            LEFT JOIN player_stats ON players.id = player_stats.player_id
            GROUP BY players.id
            ORDER BY points_total DESC")) {
        if ($result->num_rows>0){
            while($row = $result->fetch_assoc()) {
                echo "

                <tr>
                     <!-- 0 --><td>".$VolleyStats->reverseName($row['player_name'])."</td>

                    <! -- Generelt -->
                    <!-- 1 --><td>".ucfirst($VolleyStats->translateText($row['gender']))."</td>
                    <!-- 2 --><td><!-- Sæsoner spillet --></td>
                    <!-- 3 --><td>".$VolleyStats->formatNumber($row['games_played'])."</td>
                    <!-- 4 --><td><!-- Antal klubber --></td>
                    
                    <! -- Point -->
                    <!-- 5 --><td>".$VolleyStats->formatNumber($row['points_total']/$row['games_played'],2)."</td>
                    <!-- 6 --><td>".$VolleyStats->formatNumber($row['error_total']/$row['games_played'],2)."</td>
                    <!-- 7 --><td>".$VolleyStats->formatNumber($row['break_points']/$row['games_played'],2)."</td>
                    <!-- 8 --><td>".$VolleyStats->formatNumber($row['win_loss']/$row['games_played'],2)."</td>

                    <! -- Serv -->
                    <!-- 9 --><td>".$VolleyStats->formatNumber($row['serve_total']/$row['games_played'],2)."</td>
                    <!-- 10 --><td>".$VolleyStats->formatNumber($row['serve_error']/$row['games_played'],2)."</td>
                    <!-- 11 --><td>".$VolleyStats->formatNumber($row['serve_ace']/$row['games_played'],2)."</td>
            
                    <! -- Modtagning -->
                    <!-- 12 --><td>".$VolleyStats->formatNumber($row['recieve_total']/$row['games_played'],2)."</td>
                    <!-- 13 --><td>".$VolleyStats->formatNumber($row['recieve_error']/$row['games_played'],2)."</td>
                    <!-- 14 --><td>".$VolleyStats->formatNumber($row['recieve_position']/$row['games_played'],2)."</td>
                    <!-- 15 --><td>".$VolleyStats->formatNumber($row['recieve_perfect']/$row['games_played'],2)."</td>

                    <! -- Angreb -->
                    <!-- 16 --><td>".$VolleyStats->formatNumber($row['spike_total']/$row['games_played'],2)."</td>
                    <!-- 17 --><td>".$VolleyStats->formatNumber($row['spike_error']/$row['games_played'],2)."</td>
                    <!-- 18 --><td>".$VolleyStats->formatNumber($row['spike_blocked']/$row['games_played'],2)."</td>
                    <!-- 19 --><td>".$VolleyStats->formatNumber($row['spike_win']/$row['games_played'],2)."</td>

                    <! -- Blok -->
                    <!-- 20 --><td>".$VolleyStats->formatNumber($row['block_win']/$row['games_played'],2)."</td>
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