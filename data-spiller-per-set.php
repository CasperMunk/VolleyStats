<?php 
require('includes/top.php');

$type = 'players';
$context = 'per_set';
$query = "
SELECT 
    players.id, players.name, players.gender
    ,COUNT(player_stats.player_id) as games_played
    ,(SUM(games.home_sets)+SUM(games.guest_sets)) as sets_played 

    ,SUM(player_stats.point_total)/(SUM(games.home_sets)+SUM(games.guest_sets)) as point_total
    ,SUM(player_stats.receive_error+player_stats.spike_error+player_stats.serve_error)/(SUM(games.home_sets)+SUM(games.guest_sets)) as error_total
    ,SUM(player_stats.point_break_points)/(SUM(games.home_sets)+SUM(games.guest_sets)) as point_break_points 
    ,SUM(player_stats.point_win_loss)/(SUM(games.home_sets)+SUM(games.guest_sets)) as point_win_loss

    ,SUM(player_stats.serve_total)/(SUM(games.home_sets)+SUM(games.guest_sets)) as serve_total 
    ,SUM(player_stats.serve_ace)/(SUM(games.home_sets)+SUM(games.guest_sets)) as serve_ace 
    ,SUM(player_stats.serve_error)/(SUM(games.home_sets)+SUM(games.guest_sets)) as serve_error
    ,SUM(player_stats.serve_error)/SUM(player_stats.serve_total)*100 as serve_error_percent
    ,SUM(player_stats.serve_ace)/SUM(player_stats.serve_total)*100 as serve_ace_percent

    ,SUM(player_stats.receive_total)/(SUM(games.home_sets)+SUM(games.guest_sets)) as receive_total 
    ,SUM(player_stats.receive_position)/(SUM(games.home_sets)+SUM(games.guest_sets)) as receive_position 
    ,SUM(player_stats.receive_perfect)/(SUM(games.home_sets)+SUM(games.guest_sets)) as receive_perfect
    ,SUM(player_stats.receive_error)/(SUM(games.home_sets)+SUM(games.guest_sets)) as receive_error
    ,SUM(player_stats.receive_position)/SUM(player_stats.receive_total)*100 as receive_pos_percent
    ,SUM(player_stats.receive_perfect)/SUM(player_stats.receive_total)*100 as receive_perf_percent
    ,SUM(player_stats.receive_error)/SUM(player_stats.receive_total)*100 as receive_error_percent
    
    ,SUM(player_stats.spike_total)/(SUM(games.home_sets)+SUM(games.guest_sets)) as spike_total 
    ,SUM(player_stats.spike_win)/(SUM(games.home_sets)+SUM(games.guest_sets)) as spike_win 
    ,SUM(player_stats.spike_error)/(SUM(games.home_sets)+SUM(games.guest_sets)) as spike_error 
    ,SUM(player_stats.spike_blocked)/(SUM(games.home_sets)+SUM(games.guest_sets)) as spike_blocked
    ,SUM(player_stats.spike_win)/SUM(player_stats.spike_total)*100 as kill_percent
    ,(SUM(player_stats.spike_win)-SUM(player_stats.spike_error)-SUM(player_stats.spike_blocked))/SUM(player_stats.spike_total)*100 as spike_eff
    ,SUM(player_stats.spike_error)/SUM(player_stats.spike_total)*100 as spike_error_percent
    ,SUM(player_stats.spike_blocked)/SUM(player_stats.spike_total)*100 as spike_blocked_percent          
    
    ,SUM(player_stats.block_win)/(SUM(games.home_sets)+SUM(games.guest_sets)) as block_win 
FROM players
    LEFT JOIN player_stats ON players.id = player_stats.player_id
    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
    LEFT JOIN games ON games.id = player_stats.game_id
WHERE excluded_games.game_id IS NULL 
GROUP BY players.id HAVING games_played > 20
ORDER BY point_total DESC
";

$dataTable = new DataTable($VolleyStats,$type,$context,$query);

$loadElements = array("jQuery","DataTables");

$page_info = array(
    'title' => 'Data for spillere (pr. sæt)',
);

require('includes/header.php');

echo '<p>Spillere med mindre end 20 kampe er undtaget denne liste.</p>';

$dataTable->print();

require('includes/footer.php');