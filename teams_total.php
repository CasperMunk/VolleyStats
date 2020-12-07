<?php require('includes/top.php'); ?>
<?php require('includes/header.php'); ?>

<p>
Kommer snart
</p>

<?php
// SELECT teams.id, teams.team_name, competitions.gender
// ,COUNT(player_stats.player_id) as games_played 

//             ,SUM(player_stats.points_total) as points_total
//             ,SUM(player_stats.recieve_error+player_stats.spike_error+player_stats.serve_error) as error_total
//             ,SUM(player_stats.break_points) as break_points 
//             ,SUM(player_stats.win_loss) as win_loss

//             ,SUM(player_stats.serve_total) as serve_total 
//             ,SUM(player_stats.serve_ace) as serve_ace 
//             ,SUM(player_stats.serve_error) as serve_error

//             ,SUM(player_stats.recieve_total) as recieve_total 
//             ,SUM(player_stats.recieve_position) as recieve_position 
//             ,SUM(player_stats.recieve_perfect) as recieve_perfect
//             ,SUM(player_stats.recieve_error) as recieve_error 
            
//             ,SUM(player_stats.spike_total) as spike_total 
//             ,SUM(player_stats.spike_win) as spike_win 
//             ,SUM(player_stats.spike_error) as spike_error 
//             ,SUM(player_stats.spike_blocked) as spike_blocked             
            
//             ,SUM(player_stats.block_win) as block_win 
// FROM player_stats 
// INNER JOIN teams ON teams.id = player_stats.team_id 
// INNER JOIN competitions ON competitions.id = teams.competition_id 
// GROUP BY teams.team_name, competitions.gender
?>

<?php require('includes/footer.php'); ?>