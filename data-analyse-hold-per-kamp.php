<?php 
require('includes/top.php');
$loadElements = array("jQuery","DataTables");
require('includes/header.php'); 

$dataTable = new DataTable();

$data = array();
$query = "
SELECT teams.team_name, competitions.gender
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
FROM player_stats 
    INNER JOIN teams ON teams.id = player_stats.team_id 
    INNER JOIN competitions ON competitions.id = teams.competition_id
    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
WHERE excluded_games.game_id IS NULL
GROUP BY teams.team_name, competitions.gender
HAVING games_played > 100
";
if ($result = $VolleyStats->db->query($query)) {
    if ($result->num_rows>0){
        while($row = $result->fetch_assoc()) {
            $data[] = array(
                '',
                $row['team_name'],
                array($row['gender'],ucfirst($VolleyStats->translateText($row['gender']))),
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

// $dataTable->setFilter(array(
//     'text' => 'Hold med mindre end <input type="text" class="input-small text-center" aria-label="Small" aria-describedby="inputGroup-sizing-sm" id="played_games_min" value="100" size="4"> kampe er undtaget fra denne liste.',
//     'columnNumber' => 3
// ));
?>

<p>Hold med mindre end 100 kampe er undtaget fra denne liste.</p>

<?php
$dataTable->drawTable();

require('includes/footer.php'); ?>