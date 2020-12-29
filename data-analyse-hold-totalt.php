<?php 
require('includes/top.php');

$type = 'teams';
$context = 'total';
$query = "
SELECT teams.team_name name, competitions.gender
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
    ,SUM(player_stats.receive_position)/SUM(player_stats.receive_total)*100 as receive_pos_percent
    ,SUM(player_stats.receive_perfect)/SUM(player_stats.receive_total)*100 as receive_perf_percent
    
    ,SUM(player_stats.spike_total) as spike_total 
    ,SUM(player_stats.spike_win) as spike_win 
    ,SUM(player_stats.spike_error) as spike_error 
    ,SUM(player_stats.spike_blocked) as spike_blocked    
    ,SUM(player_stats.spike_win)/SUM(player_stats.spike_total)*100 as kill_percent
    ,(SUM(player_stats.spike_win)-SUM(player_stats.spike_error)-SUM(player_stats.spike_blocked))/SUM(player_stats.spike_total) as spike_eff         
    
    ,SUM(player_stats.block_win) as block_win
FROM player_stats 
    INNER JOIN teams ON teams.id = player_stats.team_id 
    INNER JOIN competitions ON competitions.id = teams.competition_id
    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
WHERE excluded_games.game_id IS NULL
GROUP BY teams.team_name, competitions.gender
ORDER BY points_total DESC
";

$dataTable = new DataTable($VolleyStats,$type,$context,$query);

$loadElements = array("jQuery","DataTables");
require('includes/header.php');

$dataTable->print();

require('includes/footer.php');