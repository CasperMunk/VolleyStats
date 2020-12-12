<?php 
require('includes/top.php');
$loadElements = array("jQuery","DataTables");
require('includes/header.php'); 

$dataTable = new DataTable();
$dataTable->setHeaders(
    array(
        array(
            array(
                'title' => '#',
                'colspan' => null,
                'rowspan' => 2,
                'filter_button' => false,
            ),
            array(
                'title' => 'Spillernavn',
                'colspan' => null,
                'rowspan' => 2,
                'filter_button' => false,
            ),
            array(
                'title' => 'Generelt',
                'colspan' => 2,
                'rowspan' => null,
                'filter_button' => true,
            ),
            array(
                'title' => 'Point',
                'colspan' => 4,
                'rowspan' => null,
                'filter_button' => true,
            ),
            array(
                'title' => 'Serv',
                'colspan' => 3,
                'rowspan' => null,
                'filter_button' => true,
            ),
            array(
                'title' => 'Modtagning',
                'colspan' => 4,
                'rowspan' => null,
                'filter_button' => true,
            ),
            array(
                'title' => 'Angreb',
                'colspan' => 4,
                'rowspan' => null,
                'filter_button' => true,
            ),
            array(
                'title' => 'Blok',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => true,
            )
        ),
        array(
            array(
                'title' => 'Køn',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Kampe spillet',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Total',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Fejl',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'BP',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'VT',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Total',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Fejl',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Es',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Total',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Fejl',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Pos',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Perf',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Total',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Fejl',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Blok',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Perf',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            ),
            array(
                'title' => 'Point',
                'colspan' => null,
                'rowspan' => null,
                'filter_button' => false,
            )
        )
    )
);

$dataTable->setColumnDefs(
    array(
        array(
            // 'title' => '#',
            'visible' => 'true',
            'className' => null,
            'orderable' => 'false',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Spillernavn',
            'visible' => 'true',
            'className' => null,
            'orderable' => 'false',
            'searchable' => 'true',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Køn',
            'visible' => 'true',
            'className' => '"colvisGroupGenerelt"',
            'orderable' => 'false',
            'searchable' => 'true',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Kampe spillet',
            'visible' => 'true',
            'className' => '"colvisGroupGenerelt"',
            'orderable' => 'true',
            'searchable' => 'true',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Total',
            'visible' => 'true',
            'className' => '"colvisGroupPoint"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => '"desc"'
        ),
        array(
            // 'title' => 'Fejl',
            'visible' => 'false',
            'className' => '"colvisGroupPoint"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'BP',
            'visible' => 'false',
            'className' => '"colvisGroupPoint"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'VT',
            'visible' => 'false',
            'className' => '"colvisGroupPoint"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Total',
            'visible' => 'false',
            'className' => '"colvisGroupServ"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Fejl',
            'visible' => 'false',
            'className' => '"colvisGroupServ"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Es',
            'visible' => 'true',
            'className' => '"colvisGroupServ"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Total',
            'visible' => 'false',
            'className' => '"colvisGroupModtagning"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Fejl',
            'visible' => 'false',
            'className' => '"colvisGroupModtagning"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Pos',
            'visible' => 'false',
            'className' => '"colvisGroupModtagning"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Perf',
            'visible' => 'false',
            'className' => '"colvisGroupModtagning"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Total',
            'visible' => 'false',
            'className' => '"colvisGroupAngreb"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Fejl',
            'visible' => 'false',
            'className' => '"colvisGroupAngreb"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Blok',
            'visible' => 'false',
            'className' => '"colvisGroupAngreb"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Perf',
            'visible' => 'true',
            'className' => '"colvisGroupAngreb"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        ),
        array(
            // 'title' => 'Point',
            'visible' => 'true',
            'className' => '"colvisGroupBlok"',
            'orderable' => 'true',
            'searchable' => 'false',
            'orderSequence' => '[ "desc","asc" ]',
            'order' => null,
        )
    )
);

$data = array();
$query = "
SELECT 
    players.* 
    ,COUNT(player_stats.player_id) as games_played 

    ,SUM(player_stats.points_total) as points_total
    ,SUM(player_stats.receive_error+player_stats.spike_error+player_stats.serve_error) as error_total
    ,SUM(player_stats.break_points) as break_points 
    ,SUM(player_stats.win_loss) as win_loss

    ,SUM(player_stats.serve_total) as serve_total 
    ,SUM(player_stats.serve_ace) as serve_ace 
    ,SUM(player_stats.serve_error) as serve_error

    ,SUM(player_stats.receive_total) as receive_total 
    ,SUM(player_stats.receive_position) as receive_position 
    ,SUM(player_stats.receive_perfect) as receive_perfect
    ,SUM(player_stats.receive_error) as receive_error 
    
    ,SUM(player_stats.spike_total) as spike_total 
    ,SUM(player_stats.spike_win) as spike_win 
    ,SUM(player_stats.spike_error) as spike_error 
    ,SUM(player_stats.spike_blocked) as spike_blocked             
    
    ,SUM(player_stats.block_win) as block_win 
          
FROM players
    LEFT JOIN player_stats ON players.id = player_stats.player_id
    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
WHERE excluded_games.game_id IS NULL
    GROUP BY players.id
    ORDER BY points_total DESC
";
if ($result = $VolleyStats->db->query($query)) {
    if ($result->num_rows>0){
        while($row = $result->fetch_assoc()) {
            $data[] = array(
                '',
                $VolleyStats->reverseName($row['player_name']),
                ucfirst($VolleyStats->translateText($row['gender'])),
                array($row['games_played'],$VolleyStats->formatNumber($row['games_played'])),
                $VolleyStats->formatNumber($row['points_total']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['error_total']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['break_points']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['win_loss']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['serve_total']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['serve_error']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['serve_ace']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['receive_total']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['receive_error']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['receive_position']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['receive_perfect']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['spike_total']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['spike_error']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['spike_blocked']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['spike_win']/$row['games_played'],2),
                $VolleyStats->formatNumber($row['block_win']/$row['games_played'],2)
            );
        }
    }
}
$dataTable->setData($data);

$dataTable->setFilter(array(
    'text' => 'Spillere med mindre end <input type="text" class="input-small text-center" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="played_games_min" value="10" size="3"> kampe er undtaget fra denne liste.',
    'columnNumber' => 3
));

$dataTable->drawTable();

require('includes/footer.php'); ?>