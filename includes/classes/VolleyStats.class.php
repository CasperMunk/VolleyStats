<?php
require 'simple_html_dom.php';
class VolleyStats extends Helpers {
    var $player_list;
    public $db;

    function __construct(){
        $this->record_length = 10;
    }

    function __destruct(){
        if (isset($this->db)) $this->db->close();
    }

    function setSecrets($secrets){
        $this->secrets = $secrets;
    }

    public function initializeMysql($host,$user,$pass,$db){
        if (!isset($this->db)){
            $this->db = new mysqli($host, $user, $pass, $db);

            /* check connection */
            if ($this->db->connect_errno) {
                printf("Connect failed: %s\n", $this->db->connect_error);
                exit();
            }

            $this->db->set_charset("utf8mb4");
        }

    }

    public function fetchMysqlAll($query){
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

    public function executeMysql($query){
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

    public function getMysqlCount($query){
        // $query = "SELECT COUNT(*) FROM ($query) t";
        // return array_shift($this->fetchMysqlAll($query)[0]);

        if ($result = $this->db->query($query)) {
            return $result->num_rows;
        }else{
            printf("MySQL Error: %s\n", $this->db->error);
            exit();
        }
    }

    function getCompetitions($only_current=false){
        $query = "SELECT id, gender, year, current FROM competitions";
        if ($only_current) $query .= " WHERE current";
        $query .= " ORDER BY year DESC";
        return $this->fetchMysqlAll($query);
    }

    function getCompetition($competition_id){
        $query = "SELECT id, gender, year, current FROM competitions WHERE id = ".$competition_id;
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

    function makeApiCall($url){
        if (empty($url)) return false;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
        "Authorization: ".$this->secrets['api_bearer'],
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $resp = json_decode(curl_exec($curl));
        curl_close($curl);

        if(!is_object($resp)){
            echo "Fejl: Intet resultat ved API-kald ($url).<br>";
            return false;
        }

        return $resp;
    }

    function updateStadium($id,$force=false){
        if (empty($id)) return false;
        if ($force == true OR $this->getMysqlCount("SELECT id from stadiums WHERE id = ".$id) == 0){
            $data = $this->makeApiCall("https://dataprojectserviceswebapi.azurewebsites.net/v1/VO/dvbf/Stadium/".$id);

            $query = "INSERT INTO stadiums (id,name,address,city,zipcode) VALUES($id,'$data->StadiumName','$data->Address','$data->City','$data->ZIPCode')";
            $this->executeMysql($query);
        }
    }

    function updateTeamAndClub($id,$competition_id,$force=false){
        if (empty($id)) return false;

        if ($force == true OR $this->getMysqlCount("SELECT id from teams WHERE id = ".$id) == 0){
            $data = $this->makeApiCall("https://dataprojectserviceswebapi.azurewebsites.net/v1/VO/dvbf/Team/".$id);
            $query = "INSERT INTO teams (id,name,competition_id,club_id) VALUES($id,'$data->Name',$competition_id,$data->ClubID)";
            $this->executeMysql($query);

            if ($force == true OR $this->getMysqlCount("SELECT id from clubs WHERE id = ".$data->ClubID) == 0){
                $query = "INSERT INTO clubs (id,name) VALUES($data->ClubID,'$data->Club')";
                $this->executeMysql($query);
            }
        }
    }

    function getApiGameData($id){
        if (empty($id)) return false;

        $match_data = $this->makeApiCall("https://dataprojectserviceswebapi.azurewebsites.net/v1/VO/dvbf/Match/".$id);
        $match_livedata = $this->makeApiCall("https://dataprojectserviceswebapilive.azurewebsites.net/api/v1/dvbf/MatchLiveData/MatchID/".$id);
        
        foreach (array(1,2,3,4,5) as $i){
            $match_data->{'Set'.$i.'Time'} = $match_livedata->{'TS'.$i};
        }
        
        return $match_data;
    }

    function getGameData($game_id,$competition_id,$gender){
        if (empty($game_id) OR empty($competition_id) OR empty($gender)) return false;

        //Get API data object/array
        $data = $this->getApiGameData($game_id);

        // echo '<pre>';
        // print_r($data);

        //Check if game is played yet
        if ($data->Finalized != 1){
            return 'Kampen er ikke spillet endnu';
        }

        $this->updateStadium($data->StadiumID);
        $this->updateTeamAndClub($data->HomeTeamID,$competition_id);
        $this->updateTeamAndClub($data->GuestTeamID,$competition_id);

        //Add data fields to game_data array
        $game_data = array();

        $game_data['id'] =                  $data->MatchID;
        $game_data['spectators'] =          $data->Spectators;
        $game_data['home_team_id'] =        $data->HomeTeamID;
        $game_data['guest_team_id'] =       $data->GuestTeamID;
        $game_data['federation_match_id'] = $data->FederationMatchNumber;
        $game_data['stadium_id'] =          $data->StadiumID;
        $game_data['home_sets'] =           $data->Final_Home;
        $game_data['guest_sets'] =          $data->Final_Guest;
        foreach (array(1,2,3,4,5) as $i){
            $game_data['home_set'.$i] =     $data->{'Set'.$i.'Home'};
            $game_data['guest_set'.$i] =    $data->{'Set'.$i.'Guest'};
            $game_data['time_set'.$i] =     $data->{'Set'.$i.'Time'};
        }
        $game_data['spectators'] =          $data->Spectators;
        $game_data['date_time'] =           "'".DateTime::createFromFormat('Y-m-d\TH:i:s', $data->MatchDateTime)->format('Y-m-d H:i:s')."'"; //Format: 2020-12-02T20:00:00


        //Get refereee info
        

        //Update match player stats

        $url = 'http://dvbf-web.dataproject.com/MatchStatistics.aspx?ID='.$competition_id.'&mID='.$game_id;
        $content = $this->getHtmlData($url);
 
        //Get player statistics
        foreach (array("Home","Guest") as $team_type){
            if (strpos($content, '_DIV_MatchStats') == true) {
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
                        $this->addPlayerStats($game_id,$data->{$team_type.'TeamID'},$player_id,$row);
                    }
                }else{
                    echo 'Fejl ved load af spiller-statistik!';
                    return false;
                }
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

    function getGamesToday(){
        if ($result = $this->db->query("SELECT g.id as game_id, g.competition_id, c.gender FROM games g LEFT JOIN competitions c ON g.competition_id = c.id WHERE date(date_time) = CURDATE();")) {
            if ($result->num_rows>0){
                return $this->fetchMysqlAll($result);
            }else{
                return false;
            }
        }
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

        $this->executeMysql("REPLACE INTO player_stats (player_id,game_id,team_id,point_total,point_break_points,serve_total,serve_error,serve_ace,receive_total,receive_error,receive_position,receive_perfect,spike_total,spike_error,spike_blocked,spike_win,block_win,point_win_loss) VALUES ($player_id,$game_id,$team_id,$points_total,$break_points,$serve_total,$serve_error,$serve_ace,$receive_total,$receive_error,$receive_position,$receive_perfect,$spike_total,$spike_error,$spike_blocked,$spike_win,$block_win,$win_loss)");
    }

    function getPlayerId($player_name,$gender){
        if (empty($player_name) OR $player_name == null) return false;

        if (!isset($_SESSION['players_updated'])){
            $this->reloadPlayers();
        }else{
            if ((time() - $_SESSION['players_updated']) > (60 * 30)) {
                $this->reloadPlayers();
            }
        }            

        //Search for player
        $player_id = array_search($player_name,$_SESSION['players']);
        if ($player_id == false){
            //Add new player
            $query = "INSERT INTO players (name,gender) VALUES ('$player_name','$gender')";
            $player_id = $this->executeMysql($query);
            $_SESSION['players'][$player_id] = $player_name;
            $_SESSION['players_updated'] = time();
        }

        return $player_id;
    }

    function reloadPlayers(){
        $_SESSION['players'] = array();
        if ($result = $this->db->query("SELECT id, name FROM players")) {
            if ($result->num_rows>0){
                while($row = $result->fetch_assoc()) {
                    $_SESSION['players'][$row["id"]] = $row["name"];
                }

                $_SESSION['players_updated'] = time();
            }
        }
    }

    function getExcludedPlayers(){
        if (!isset($this->excluded_player_list)){
            if ($result = $this->db->query("SELECT name FROM excluded_players")) {
                if ($result->num_rows>0){
                    while($row = $result->fetch_assoc()) {
                        $this->excluded_player_list[] = $row['name'];
                    }
                }
            }
        }
        return $this->excluded_player_list;
    }

    function getTeamId($team_name,$competition_id){
        if (empty($team_name) OR empty($competition_id)) return false;

        $query = "SELECT id FROM teams WHERE team_name='$team_name' AND competition_id=$competition_id";

        if ($result = $this->db->query($query)) {
            if ($result->num_rows>0){
                return $this->fetchMysqlAll($query)[0]["id"];
            }else{
                return $this->executeMysql("INSERT INTO teams (team_name, competition_id) VALUES ('$team_name', $competition_id)");
            }
        }
        return false;
    }

    function getRefereeId($name){
        if (empty(trim($name))) return false;

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

    function setRecordType($type){
        if (empty($type)) return false;
        $this->recordType = $type;
    }

    function getRecordGroups($group){
        if (empty($this->recordType)) return false;
        if (empty($group)) return false;

        return $this->fetchMysqlAll("SELECT id, title, lookup_type FROM records_config WHERE record_type = '".$this->recordType."' AND record_group = '".$group."' ORDER BY sorting");
    }

    function getRecordTabs(){
        if (empty($this->recordType)) return false;
        $tabs = array();
        
        foreach ($this->fetchMysqlAll("SELECT record_group FROM records_config WHERE record_type = '".$this->recordType."' GROUP BY record_group, sorting ORDER BY sorting") as $tab){
            $tabs[$tab['record_group']] = ucfirst($this->translateText($tab['record_group']));
        }
                
        return $tabs;
    }

    function updateRecords(){
        $this->executeMysql("TRUNCATE TABLE records");
        if ($result = $this->db->query("SELECT * FROM records_config")) {
            if ($result->num_rows>0){
                while($r = $result->fetch_assoc()) {
                    foreach (array("male","female") as $gender){
                        if ($r['lookup_type'] == 'player_stats'){
                            $query = "
                            INSERT INTO records
                            (player_id, game_id, record_value, record_id)

                            SELECT player_stats.player_id, player_stats.game_id, ".$r['calculation']."(player_stats.".$r['field'].") record_value, ".$r['id']." as record_id 
                            FROM player_stats 
                                LEFT JOIN excluded_games ON player_stats.game_id = excluded_games.game_id 
                                LEFT JOIN players ON player_stats.player_id = players.id 
                                LEFT JOIN games ON player_stats.game_id = games.id
                                LEFT JOIN excluded_records ex_r ON player_stats.game_id = ex_r.game_id AND ex_r.record_id = ".$r['id']."
                            WHERE excluded_games.game_id IS NULL AND ex_r.game_id IS NULL AND players.gender='".$gender."' 
                            GROUP BY player_id, game_id
                            ORDER BY record_value DESC, games.date_time DESC, games.id
                            LIMIT ".$this->record_length."
                            ";  
                        }elseif ($r['lookup_type'] == 'game_stats') {
                            $field = $r['field'];
                            if (strpos($field,",") !== false){
                                $field = explode(",",$field);
                                $n = count($field);
                            }
                            $query = "
                            INSERT INTO records
                            (game_id, record_value, record_id)
                        
                            SELECT 
                                g.id game_id, (";
                                if ($r['special_calculation'] != NULL){
                                    $query .= $r['special_calculation'];
                                }elseif (is_array($field)){
                                    $query .= 'greatest(';
                                    foreach ($field as $i => $sub_fields){
                                        $query .= $r['calculation']."(".$sub_fields.")";
                                        if (($i+1) != $n) $query .= ',';
                                    }
                                    $query .= ')';
                                }else{
                                    $query .= $r['calculation'] . '(';
                                    $query .= $field;
                                    $query .= ')';
                                }
                                $query .= ") record_value, ".$r['id']." as record_id 

                            FROM games g
                                LEFT JOIN excluded_games ex ON g.id = ex.game_id
                                LEFT JOIN competitions c ON c.id = g.competition_id
                                LEFT JOIN excluded_records ex_r ON g.id = ex_r.game_id AND ex_r.record_id = ".$r['id']."

                                WHERE ex.game_id IS NULL AND c.gender='".$gender."' AND ex_r.game_id IS NULL
                                GROUP BY g.id 
                            ORDER BY record_value DESC, g.date_time DESC, g.id
                            LIMIT ".$this->record_length."
                            ";
                        }
                        // echo $query."<br><br>";
                        $this->executeMysql($query);
                    }    
                }
                return true;
            }else{
                return false;
            }
            return false;
        }   
    }

    function getRecords($id,$lookupType = ''){
        if (empty($id)) return false;

        if ((empty($lookupType))) $lookupType = $this->fetchMysqlAll("SELECT lookup_type FROM records_config WHERE id = ".$id)[0]['lookup_type'];

        if ($lookupType == 'player_stats'){
            $query = "
            SELECT r.game_id, p.name player_name, p.gender, r.record_value, comp.year, comp.current 
            FROM records r 
                INNER JOIN records_config c ON r.record_id = c.id 
                INNER JOIN players p ON p.id = r.player_id 
                INNER JOIN games g ON g.id = r.game_id 
                INNER JOIN competitions comp ON comp.id = g.competition_id
            WHERE r.record_id=".$id."
            ORDER BY r.record_value DESC, g.date_time DESC, g.id
            ";
        }elseif ($lookupType == 'game_stats'){
            $query = "
            SELECT r.game_id, comp.gender, r.record_value, comp.year, comp.current, t_home.team_name home_team_name, t_guest.team_name guest_team_name, UNIX_TIMESTAMP(g.date_time) date_time
            FROM records r 
                INNER JOIN records_config c ON r.record_id = c.id 
                INNER JOIN games g ON g.id = r.game_id 
                INNER JOIN competitions comp ON comp.id = g.competition_id
                LEFT JOIN teams t_home ON g.home_team_id = t_home.id
                LEFT JOIN teams t_guest ON g.guest_team_id = t_guest.id
            WHERE r.record_id=".$id."
            ORDER BY r.record_value DESC, g.date_time DESC, g.id
            ";
        }

        return $this->fetchMysqlAll($query);


    }

    function printRecords(){
        $tabs = $this->getRecordTabs();
        echo '
        <div class="row">
            <div class="col-md">
                <nav class="nav nav-pills btn-group record-tabs mb-2 mb-md-0">';
                    foreach ($tabs as $key => $tab){
                        echo '<a class="btn btn-outline-primary text-nowrap'.(($tab === reset($tabs)) ? ' active' : '').'" id="nav-'.$key.'-tab" data-bs-toggle="tab" href="#nav-'.$key.'">'.$tab.'</a>';
                    }
                    echo '
                </nav>
            </div>
            <div class="col-md text-md-end">';
                include("includes/gender_picker.php");
            echo '</div>
        </div>

        <div class="tab-content" id="nav-tabContent">';
            foreach ($tabs AS $key => $tab){
                echo '<div class="tab-pane fade show'.(($tab === reset($tabs)) ? ' active' : '').'" id="nav-'.$key.'">';
                
                foreach ($this->getRecordGroups($key) as $group){
                    echo '<h1 class="mt-2 h5">'.$group['title'].'</h1>
                    <ol class="records">';
                        
                            foreach ($this->getRecords($group['id']) as $record){
                                echo '
                                <li class="'.$record['gender'].' '.($record['current'] ? ' current' : '').'" data-value="'.$record['record_value'].'">';
                                    
                                        if ($group['lookup_type'] == 'player_stats'){
                                            echo '<a href="https://dvbf-web.dataproject.com/MatchStatistics.aspx?mID='.$record['game_id'].'" target="_blank" rel="noopener">';
                                                echo '<span class="title">'.$record['player_name'].'</span>' ;
                                                echo '<span class="record_value">('.$record['record_value'].')</span>';
                                            echo '</a>';
                                        }elseif ($group['lookup_type'] == 'game_stats'){
                                            echo '<a href="https://dvbf-web.dataproject.com/MatchStatistics.aspx?mID='.$record['game_id'].'" target="_blank" rel="noopener">';
                                                echo '<span class="title">'.$record['home_team_name'].' - '.$record['guest_team_name'].'</span> ';
                                                echo '<span class="record_value">('.$record['record_value'].')</span>';
                                            echo '</a>';
                                        }
                                    if ($record['current']) echo ' <span class="badge bg-primary">Denne sæson</span>';
                                echo '</li>
                                ';
                            }
                    echo '</ol>';
                }
                echo '</div>';
            }	
            echo '</div>';
    }
}