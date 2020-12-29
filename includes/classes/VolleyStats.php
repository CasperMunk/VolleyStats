<?php
require 'simple_html_dom.php';
class VolleyStats extends VolleyStatsHelpers {
    //Vars
    var $player_list;
    var $db;

    function initializeMysql($host,$user,$pass,$db){
        if (!isset($this->db)){
            $this->db = new mysqli($host, $user, $pass, $db);

            /* check connection */
            if ($this->db->connect_errno) {
                printf("Connect failed: %s\n", $this->db->connect_error);
                exit();
            }

            $this->db->set_charset("utf8");
        }

    }

    function __destruct(){
        if (isset($this->db)) $this->db->close();
    }

    function getCompetitions($only_auto_update=false){
        $query = "SELECT id, gender, year, auto_update FROM competitions";
        if ($only_auto_update) $query .= " WHERE auto_update";
        $query .= " ORDER BY year DESC";
        return $this->fetchMysqlAll($query);
    }

    function getCompetition($competition_id){
        $query = "SELECT id, gender, year, auto_update FROM competitions WHERE id = ".$competition_id;
        return $this->fetchMysqlAll($query)[0];
    }

    function getSeasonYears(){
        $query = "SELECT year FROM competitions GROUP BY year ORDER BY year DESC";
        return $this->fetchMysqlAll($query);
    }

    function getOverviewStats(){
        $query = "
        SELECT
            (SELECT COUNT(DISTINCT(year)) FROM competitions) as seasons,
            (SELECT COUNT(*) FROM competitions) as competitions,
            (SELECT COUNT(*) FROM teams) as teams,
            (SELECT COUNT(*) FROM games) as games,
            (SELECT COUNT(*) FROM players) as players,
            (SELECT COUNT(*) FROM player_stats) as player_stats
        ";

        return $this->formatNumber($this->fetchMysqlAll($query)[0]);
    }

    function reloadGames($competition_id){
        $all_games = array();
        $url = "http://dvbf-web.dataproject.com/CompetitionMatches.aspx?ID=".$competition_id;
        $content = $this->getHtmlData($url);

        //Get PIDs
        preg_match_all("/\&amp;PID=\d*(?=.*[0-9])/",$content,$p_ids);
        $p_ids = array_unique(str_replace("&amp;PID=","",array_shift($p_ids)));

        //Loop through PIDS
        foreach($p_ids as $p_id){
            $games = array();
            $url = "http://dvbf-web.dataproject.com/CompetitionMatches.aspx?ID=".$competition_id."&PID=".$p_id;
            $content = $this->getHtmlData($url);

            preg_match_all("/MatchStatistics\.aspx\?mID=\d*(?=.*[0-9])/",$content,$games);
            $games = array_unique(str_replace("MatchStatistics.aspx?mID=","",array_shift($games)));
        
            $all_games = $all_games + $games;
        }    

        foreach ($all_games as $game){
            $this->executeMysql("REPLACE INTO games (id,competition_id) VALUES ($game,$competition_id)");
        }

        return $all_games;
    }

    function getGames($competition_id,$update=false){
        if($update){
            return $this->reloadGames($competition_id);
        }else{
            if ($result = $this->db->query("SELECT id FROM games WHERE competition_id=$competition_id")) {
                if ($result->num_rows>0){
                    while($row = $result->fetch_assoc()) {
                        $games[] = $row["id"];
                    }
                    return $games;
                }else{
                    return $this->reloadGames($competition_id); 
                }
                return false;
            }
        }  
    }

    function getGameData($game_id,$competition_id,$gender){
        if (empty($game_id) OR empty($competition_id) OR empty($gender)) return false;
        $url = 'http://dvbf-web.dataproject.com/MatchStatistics.aspx?ID='.$competition_id.'&mID='.$game_id;
        $content = $this->getHtmlData($url);

        $set_score_data = trim($this->cleanInputData($this->getTagById("span","Content_Main_LB_SetsPartials",$content),"text"));

        if (empty($set_score_data)) {
            return 'Kampen er ikke spillet endnu';
        }

        //Get general info about game
        $game_data = array();

        // $federation_id = preg_match_all("\d*(?=.*[0-9])\.pdf",$content,$matches);
        // $game_data["federation_id"] = str_replace(".pdf","",);

        $game_data["location"] = "'".$this->cleanInputData($this->getTagById("span","Content_Main_LB_Stadium",$content),"text")."'";

        $date_time = $this->cleanInputData($this->getTagById("span","Content_Main_LB_DateTime",$content),"text");
        $months_array = array("januar" => 1, "februar" => 2, "marts" => 3, "april" => 4, "maj" => 5, "juni" => 6, "juli" => 7, "august" => 8, "september" => 9, "oktober" => 10, "november" => 11, "december" => 12);
        $date_time = strtr($date_time,$months_array);
        $game_data["date_time"] = "'".DateTime::createFromFormat('j. n Y - H:i', $date_time)->format('Y-m-d H:i:s')."'";

        $game_data["spectators"] = $this->cleanInputData($this->getTagById("span","Content_Main_LBL_Spectators",$content));

        if (strpos($content, 'id="Content_Main_LB_Referees"') == true) {
            $referee1_name = $this->reverseName(trim(explode("-",$this->cleanInputData($this->getTagById("span","Content_Main_LB_Referees",$content),"text"))[0]));
            $game_data["referee1_id"] = $this->getRefereeId($referee1_name);

            $referee2_name = $this->reverseName(trim(explode("-",$this->cleanInputData($this->getTagById("span","Content_Main_LB_Referees",$content),"text"))[1]));
            $game_data["referee2_id"] = $this->getRefereeId($referee2_name);
        }
        
        $game_data["home_sets"] = $this->cleanInputData($this->getTagById("span","Content_Main_LBL_WonSetHome",$content));
        $game_data["guest_sets"] = $this->cleanInputData($this->getTagById("span","Content_Main_LBL_WonSetGuest",$content));

        //Get set scores an loop through them
        $set_scores = explode(" ",$set_score_data);

        $set_count = 1;
        foreach ($set_scores as $score){
            $game_data["home_set".$set_count] = explode("/",$score)[0];
            $game_data["guest_set".$set_count] = explode("/",$score)[1];
            $set_count++;
        }

        if (strpos($content, '_DIV_MatchStats') == false) {
            //Update general info about game
            $this->updateGameData($game_id,$game_data);
            return 'Kampstats opdateret. Ingen spillerstat tilgængelig.';
        }
        
        //Get player statistics
        foreach (array("Home","Guest") as $team_type){
            //Get team name
            $team_name = $this->cleanInputData($this->getTagById("span","Content_Main_ctl17_RP_MatchStats_TeamName_".$team_type."_0",$content),"text");

            //Get team id (create one if it doesn't exist)
            $team_id = $this->getTeamId($team_name,$competition_id);
            if ($team_id == false){
                echo 'Intet team ID for kamp ID '.$game_id;
                exit;
            }

            $game_data[strtolower($team_type)."_team_id"] = $team_id;

            //Load stat table
            $team_table = $this->getTagById("div","RG_".$team_type."Team",$content);

            if ($html = str_get_html($team_table)){
                foreach ($html->find('table.rgMasterTable tbody tr') as $row){
                    //Trim name
                    $player_name = trim(str_replace('(L)', '', $row->find('td', 1)->plaintext));
                    if (in_array($player_name,$this->getExcludedPlayers())) continue;
                    $player_name = $this->ucname(strtolower($this->reverseName($player_name)));

                    //Get player id (create one if it doesn't exist)
                    $player_id = $this->getPlayerId($player_name,$gender);
                    
                    //Add stats for player
                    $this->addPlayerStats($game_id,$team_id,$player_id,$row);
                }
            }else{
                return 'Fejl ved load af HTML-data';
            }
        }

        $this->updateGameData($game_id,$game_data);
        return true;
    }

    function updateGameData($game_id,$dataset){
        $update_fields = array();
        foreach ($dataset as $key => $data){
            $update_fields[] = $key." = ".$data;
        }

        $query = "UPDATE games SET ".implode(",",$update_fields)." WHERE id = ".$game_id;
        $this->executeMysql($query);
    }

    function getHtmlData($url){
        $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36';

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");

        if (!$content = curl_exec($ch)){     
            echo 'Error getting data';
            return false;
        }
        curl_close($ch);
        return $content;

    }

    function addPlayerStats($game_id,$team_id,$player_id,$data){
        if (empty($player_id) OR empty($game_id)) return false;

        $points_total =     $this->cleanInputData($data->find("span[id=PointsTot]",0)->plaintext);
        $break_points =     $this->cleanInputData($data->find("span[id=Points]",0)->plaintext);
        $serve_total =      $this->cleanInputData($data->find("span[id=ServeTot]",0)->plaintext);
        $serve_error =      $this->cleanInputData($data->find("span[id=ServeErr]",0)->plaintext);
        $serve_ace =        $this->cleanInputData($data->find("span[id=ServeAce]",0)->plaintext);
        $receive_total =    $this->cleanInputData($data->find("span[id=RecTot]",0)->plaintext);
        $receive_error =    $this->cleanInputData($data->find("span[id=RecErr]",0)->plaintext);

        $RecPos = $data->find("span[id=RecPos]",0);
        if (isset($RecPos)) $receive_position = $this->cleanInputData($RecPos->plaintext);

        $RecPos0 = $data->find("span[id=RecPos0]",0);
        if (isset($RecPos0)) $receive_position = $this->cleanInputData($RecPos0->plaintext);

        $receive_position = $receive_total * ($receive_position/100);

        $RecPerf = $data->find("span[id=RecPerf]",0);
        if (isset($RecPerf)) $receive_perfect = $this->cleanInputData($RecPerf->plaintext);

        $RecPerf0 = $data->find("span[id=RecPerf0]",0);
        if (isset($RecPerf0)) $receive_perfect = $this->cleanInputData($RecPerf0->plaintext);

        $receive_perfect = $receive_total * ($receive_perfect/100);

        $spike_total =      $this->cleanInputData($data->find("span[id=SpikeTot]",0)->plaintext);
        $spike_error =      $this->cleanInputData($data->find("span[id=SpikeErr]",0)->plaintext);
        $spike_blocked =    $this->cleanInputData($data->find("span[id=SpikeHP]",0)->plaintext);
        $spike_win =        $this->cleanInputData($data->find("span[id=SpikeWin]",0)->plaintext);
        $block_win =        $this->cleanInputData($data->find("span[id=BlockWin]",0)->plaintext);
        $win_loss =         $this->cleanInputData($data->find("span[id=L_VP]",0)->plaintext);

        $this->executeMysql("REPLACE INTO player_stats (player_id,game_id,team_id,points_total,break_points,serve_total,serve_error,serve_ace,receive_total,receive_error,receive_position,receive_perfect,spike_total,spike_error,spike_blocked,spike_win,block_win,win_loss) VALUES ($player_id,$game_id,$team_id,$points_total,$break_points,$serve_total,$serve_error,$serve_ace,$receive_total,$receive_error,$receive_position,$receive_perfect,$spike_total,$spike_error,$spike_blocked,$spike_win,$block_win,$win_loss)");
    }

    function getPlayerId($player_name,$gender){
        if (empty($player_name) OR $player_name == null) return false;

        if ((time() - $_SESSION['players_updated']) > (60 * 30)) {
            $this->reloadPlayers();
        }

        //Search for player
        $player_id = array_search($player_name,$_SESSION['players']);
        if ($player_id == false){
            //Add new player
            $query = "INSERT INTO players (player_name,gender) VALUES ('$player_name','$gender')";
            $player_id = $this->executeMysql($query);
            $_SESSION['players'][$player_id] = $player_name;
            $_SESSION['players_updated'] = time();
        }

        return $player_id;
    }

    function reloadPlayers(){
        $_SESSION['players'] = array();
        if ($result = $this->db->query("SELECT id, player_name FROM players")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $_SESSION['players'][$row["id"]] = $row["player_name"];
                }

                $_SESSION['players_updated'] = time();
            }
        }
    }

    function getExcludedPlayers(){
        if (!isset($this->excluded_player_list)){
            if ($result = $this->db->query("SELECT player_name FROM excluded_players")) {
                if ($result->num_rows>0){
                    while($row = $result->fetch_assoc()) {
                        $this->excluded_player_list[] = $row['player_name'];
                    }
                }
            }
        }
        return $this->excluded_player_list;
    }

    function getTeamId($team_name,$competition_id){
        if (empty($team_name) OR empty($competition_id)) return false;

        if ($result = $this->db->query("SELECT id FROM teams WHERE team_name='$team_name' AND competition_id=$competition_id")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    return $row["id"];
                }
            }else{
                return $this->executeMysql("INSERT INTO teams (team_name, competition_id) VALUES ('$team_name', $competition_id)");
            }
        }
        return false;
    }

    function getRefereeId($name){
        if (empty($name)) return false;

        if ($result = $this->db->query("SELECT id FROM referees WHERE name='$name'")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    return $row["id"];
                }
            }else{
                return $this->executeMysql("INSERT INTO referees (name) VALUES ('$name')");
            }
        }
        return false;
    }

    function getRecords($type){
        if (empty($type)) return false;
        // COUNT(player_stats.player_id) as games_played
        
        if ($type == 'games_played'){
            $query = "
                SELECT 
                    players.id, players.player_name, players.gender, COUNT(player_stats.player_id) as ".$type."
                FROM players 
                    INNER JOIN player_stats ON player_stats.player_id = players.id 
                    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
                    WHERE excluded_games.game_id IS NULL
                GROUP BY players.id  
                ORDER BY ".$type." DESC 
                LIMIT 50
            ";
        }else{
            $query = "
                SELECT 
                    players.id, players.player_name, players.gender, player_stats.game_id as game_id, MAX(player_stats.".$type.") as ".$type." 
                FROM players 
                    INNER JOIN player_stats ON player_stats.player_id = players.id 
                    LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id
                    WHERE excluded_games.game_id IS NULL
                GROUP BY players.id, players.gender, player_stats.game_id
                ORDER BY ".$type." DESC 
                LIMIT 50
            ";
        }
        return $this->fetchMysqlAll($query);
    }

    function printRecordTable($record){
        echo '<h5 class="mt-2">'.$record['title'].'</h5>
        <ol class="records">';
            foreach ($this->getRecords($record['id']) as $result){
                echo '
                <li class="'.$result['gender'].' hidden">';
                    if (isset($result['game_id'])) echo '<a href="https://dvbf-web.dataproject.com/MatchStatistics.aspx?mID='.$result['game_id'].'" target="_blank">';
                        echo '
                        <span class="player_name">'.$result['player_name'].'</span>
                        <span class="description">('.$result[$record['id']].' '.$record['measurement'].')</span>
                        ';
                    if (isset($result['game_id'])) echo '</a>';
                echo '</li>
                ';
            }
        echo '</ol>';
    }

    function fetchMysqlAll($query){
        if ($result = $this->db->query($query)) {
            if ($result->num_rows>0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }else{
                printf("MySQL Error: No rows returned. \n");
                exit();
            }
        }else{
            printf("MySQL Error: %s\n", $this->db->error);
            exit();
        }
    }

    function executeMysql($query){
        if (empty($query)){
            echo 'No Query!'; 
            return;  
        } 
        if ($result = $this->db->query($query) === TRUE){
            return $this->db->insert_id;
        }else{
            printf("MySQL Error: %s\n", $this->db->error);
            echo '<p><pre>MySQL Query: '.$query;
            exit();
        }
    }

    function getMysqlCount($query){
        $query = "SELECT COUNT(*) FROM ($query) t";
        return array_shift($this->fetchMysqlAll($query)[0]);
    }
}