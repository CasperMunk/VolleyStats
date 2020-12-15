<?php 
require('includes/top.php');
$loadElements = array("jQuery","DataTables");
require('includes/header.php'); 

$dataTable = new DataTable();

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
                array($row['gender'],ucfirst($VolleyStats->translateText($row['gender']))),
                $VolleyStats->formatNumber($row['games_played']),
                $VolleyStats->formatNumber($row['points_total']),
                $VolleyStats->formatNumber($row['error_total']),
                $VolleyStats->formatNumber($row['break_points']),
                $VolleyStats->formatNumber($row['win_loss']),
                $VolleyStats->formatNumber($row['serve_total']),
                $VolleyStats->formatNumber($row['serve_error']),
                $VolleyStats->formatNumber($row['serve_ace']),
                $VolleyStats->formatNumber($row['receive_total']),
                $VolleyStats->formatNumber($row['receive_error']),
                $VolleyStats->formatNumber($row['receive_position']),
                $VolleyStats->formatNumber($row['receive_perfect']),
                $VolleyStats->formatNumber($row['spike_total']),
                $VolleyStats->formatNumber($row['spike_error']),
                $VolleyStats->formatNumber($row['spike_blocked']),
                $VolleyStats->formatNumber($row['spike_win']),
                $VolleyStats->formatNumber($row['block_win'])
            );
        }
    }
}
$dataTable->setData($data);

$dataTable->drawTable();

require('includes/footer.php'); ?>