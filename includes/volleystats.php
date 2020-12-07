<?php
require 'simple_html_dom.php';
class VolleyStats {
    //Vars
    var $player_list;
    var $db;

    function __construct() {
        $this->translations = array(
            'male' => 'mand',
            'female' => 'kvinde'
        );
    }

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

    function getCompetitions(){
        if ($result = $this->db->query("SELECT id, gender, year FROM competitions ORDER BY year DESC")) {
            if ($result->num_rows>0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    function getSeasonYears(){
        if ($result = $this->db->query("SELECT year FROM competitions GROUP BY year ORDER BY year DESC")) {
            if ($result->num_rows>0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
    }

    function getOverviewStats(){
        $query = "
        SELECT
            (SELECT COUNT(id) FROM competitions) as competitions,
            (SELECT COUNT(id) FROM teams) as teams,
            (SELECT COUNT(id) FROM games) as games,
            (SELECT COUNT(id) FROM players) as players,
            (SELECT COUNT(*) FROM player_stats) as player_stats
        ";

        if ($result = $this->db->query($query)) {
            if ($result->num_rows>0){
                $numbers = $result->fetch_all(MYSQLI_ASSOC)[0];
                return $this->formatNumber($numbers);
            }
        }
    }

    function formatNumber($val,$decimal=0)
    {
        if (is_array($val)){
            return array_map(function($num){return number_format($num,0,",",".");}, $val);
        }else{
            return number_format($val,$decimal,",",".");
        }
        
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
            $this->db->query("REPLACE INTO games (id,competition_id) VALUES ($game,$competition_id)");
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
        $url = 'http://dvbf-web.dataproject.com/MatchStatistics.aspx?mID='.$game_id;
        $content = $this->getHtmlData($url);

        if (strpos($content, '_DIV_MatchStats') == false) {
            return 'skipped';
        }else{
            foreach (array("Home","Guest") as $team_type){
                //Get team name
                $team_name = strip_tags($this->getTagById("span","Content_Main_ctl17_RP_MatchStats_TeamName_".$team_type."_0",$content));

                //Get team id (create one if it doesn't exist)
                $team_id = $this->getTeamId($team_name,$competition_id);
                if ($team_id == false){
                    echo 'No team ID for game no. '.$game_id;
                    exit;
                }

                //Load stat table
                $team_table = $this->getTagById("div","RG_".$team_type."Team",$content);

                if ($html = str_get_html($team_table)){
                    foreach ($html->find('table.rgMasterTable tbody tr') as $row){
                        //Trim name
                        $player_name = trim(str_replace('(L)', '', $row->find('td', 1)->plaintext));
                        if ($player_name == 'TOTALER') continue;

                        //Get player id (create one if it doesn't exist)
                        $player_id = $this->getPlayerId($player_name,$gender);
                        
                        //Add stats for player
                        $this->addPlayerStats($game_id,$team_id,$player_id,$row);
                    }
                }else{
                    return false;
                }
            }
            return true;
        } 
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

        $points_total =     $this->cleanStatsData($data->find("span[id=PointsTot]",0)->plaintext);
        $break_points =     $this->cleanStatsData($data->find("span[id=Points]",0)->plaintext);
        $serve_total =      $this->cleanStatsData($data->find("span[id=ServeTot]",0)->plaintext);
        $serve_error =      $this->cleanStatsData($data->find("span[id=ServeErr]",0)->plaintext);
        $serve_ace =        $this->cleanStatsData($data->find("span[id=ServeAce]",0)->plaintext);
        $recieve_total =    $this->cleanStatsData($data->find("span[id=RecTot]",0)->plaintext);
        $recieve_error =    $this->cleanStatsData($data->find("span[id=RecErr]",0)->plaintext);

        $RecPos = $data->find("span[id=RecPos]",0);
        if (isset($RecPos)) $recieve_position = $this->cleanStatsData($RecPos->plaintext);

        $RecPos0 = $data->find("span[id=RecPos0]",0);
        if (isset($RecPos0)) $recieve_position = $this->cleanStatsData($RecPos0->plaintext);

        $recieve_position = $recieve_total * ($recieve_position/100);

        $RecPerf = $data->find("span[id=RecPerf]",0);
        if (isset($RecPerf)) $recieve_perfect = $this->cleanStatsData($RecPerf->plaintext);

        $RecPerf0 = $data->find("span[id=RecPerf0]",0);
        if (isset($RecPerf0)) $recieve_perfect = $this->cleanStatsData($RecPerf0->plaintext);

        $recieve_perfect = $recieve_total * ($recieve_perfect/100);

        $spike_total =      $this->cleanStatsData($data->find("span[id=SpikeTot]",0)->plaintext);
        $spike_error =      $this->cleanStatsData($data->find("span[id=SpikeErr]",0)->plaintext);
        $spike_blocked =    $this->cleanStatsData($data->find("span[id=SpikeHP]",0)->plaintext);
        $spike_win =        $this->cleanStatsData($data->find("span[id=SpikeWin]",0)->plaintext);
        $block_win =        $this->cleanStatsData($data->find("span[id=BlockWin]",0)->plaintext);
        $win_loss =         $this->cleanStatsData($data->find("span[id=Points]",0)->plaintext);

        if ($result = $this->db->query("REPLACE INTO player_stats (player_id,game_id,team_id,points_total,break_points,serve_total,serve_error,serve_ace,recieve_total,recieve_error,recieve_position,recieve_perfect,spike_total,spike_error,spike_blocked,spike_win,block_win,win_loss) VALUES ($player_id,$game_id,$team_id,$points_total,$break_points,$serve_total,$serve_error,$serve_ace,$recieve_total,$recieve_error,$recieve_position,$recieve_perfect,$spike_total,$spike_error,$spike_blocked,$spike_win,$block_win,$win_loss)") === TRUE){
            return true;
        }
    }

    function cleanStatsData($val){
        return intval(trim(str_replace('%', '', $val)));
    }

    function getPlayerId($player_name,$gender){
        if (empty($player_name) OR $player_name == null) return false;

        if (empty($this->player_list) OR $this->player_list == null) $this->reloadPlayers();

        //Search for player
        $player_id = array_search($player_name,$this->player_list,true);
        if ($player_id == false){
            //Add new player
            if ($result = $this->db->query("INSERT INTO players (player_name,gender) VALUES ('$player_name','$gender')") === TRUE){
                $player_id = $this->db->insert_id;
                $this->player_list[$player_id] = $player_name;
            }
        }

        return $player_id;
    }

    function reloadPlayers(){
        $this->player_list = array();
        if ($result = $this->db->query("SELECT id, player_name FROM players")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $this->player_list[$row["id"]] = $row["player_name"];
                }
            }
        }
    }

    function getTeamId($team_name,$competition_id){
        if (empty($team_name) OR empty($competition_id)) return false;

        if ($result = $this->db->query("SELECT id FROM teams WHERE team_name='$team_name' AND competition_id=$competition_id")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    return $row["id"];
                }
            }else{
                if ($result = $this->db->query("INSERT INTO teams (team_name, competition_id) VALUES ('$team_name', $competition_id)") === TRUE){
                    return $this->db->insert_id;
                }
            }
        }
        return false;
    }

    function getTagById($tag,$id,$content){
        if (preg_match('#(<'.$tag.'[^>]*id=[\'|"]'.$id.'[\'|"][^>]*>)(.*)</'.$tag.'>#isU', $content, $response)){
            return $response[0];
        }    
    }

    function reverseName($name){
        return trim(strstr($name," "))." ".substr($name,0,strpos($name," "));
    }

    function translateText($text){
        return strtr($text,$this->translations);
    }
}
?>