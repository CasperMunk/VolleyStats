<?php 
class DataTable {
	private $data = array();
 
    public function __set($name, $value){
        $this->data[$name] = $value;
    }
 
    public function __get($name){
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
	}
	
	function __construct($VolleyStats,$type,$context,$query){
		$this->VolleyStats = $VolleyStats;
		$this->type = $type;
		$this->context = $context;
		$this->length = 30;
		$this->query = $query;

		$this->init();

		if ((get('draw'))){
			$this->ajax($_GET);
		}
	}

	function init(){
		$this->headers = 
		    array(
		        array(
		            array(
		                'title' => 'Generelt',
		                'colspan' => 4,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 100,
		            ),
		            array(
		                'title' => 'Point',
		                'colspan' => 4,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 200,
		            ),
		            array(
		                'title' => 'Serv',
		                'colspan' => 3,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 300,
		            ),
		            array(
		                'title' => 'Modtagning',
		                'colspan' => 6,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 400,
		            ),
		            array(
		                'title' => 'Angreb',
		                'colspan' => 6,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 800,
		            ),
		            array(
		                'title' => 'Blok',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => null,
		                'prio' => 900,
		            )
		        ),
		        array(
		            array(
		                'title' => '#',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Generelt',
		                'prio' => 200,
		            ),
		            array(
		                'title' => 'Navn',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Generelt',
		                'prio' => 100,
		            ),
		            array(
		                'title' => 'Køn',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Generelt',
		                'prio' => 600,
		            ),
		            array(
		                'title' => 'Kampe',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Generelt',
		                'prio' => 300,
		            ),
		            array(
		                'title' => 'Tot',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Point',
		                'prio' => 400,
		            ),
		            array(
		                'title' => 'Fejl',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Point',
		            ),
		            array(
		                'title' => 'BP',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Point',
		            ),
		            array(
		                'title' => 'VT',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Point',
		            ),
		            array(
		                'title' => 'Tot',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Serv',
		            ),
		            array(
		                'title' => 'Fejl',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Serv',
		            ),
		            array(
		                'title' => 'Es',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Serv',
		                'prio' => 600,
		            ),
		            array(
		                'title' => 'Tot',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
		            ),
		            array(
		                'title' => 'Fejl',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
		            ),
		            array(
		                'title' => 'Pos',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
					),
					array(
		                'title' => 'Pos%',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
		            ),
		            array(
		                'title' => 'Perf',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
		                'prio' => 700,
					),
		            array(
		                'title' => 'Perf%',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Modtagning',
		                'prio' => 700,
		            ),
		            array(
		                'title' => 'Tot',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		            ),
		            array(
		                'title' => 'Fejl',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		            ),
		            array(
		                'title' => 'Blok',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		            ),
		            array(
		                'title' => 'Perf',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		                'prio' => 800,
					),
					array(
		                'title' => 'Kill%',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		                'prio' => 800,
					),
					array(
		                'title' => 'Eff',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Angreb',
		                'prio' => 800,
		            ),
		            array(
		                'title' => 'Point',
		                'colspan' => null,
		                'rowspan' => null,
		                'category' => 'Blok',
		                'prio' => 900,
		            )
		        )
		    )
		;

		$defaultFormat = '$.fn.dataTable.render.number( ".", ",", 0, "", "")';	

		if ($this->context == 'per_game'){
			$defaultFormat = '$.fn.dataTable.render.number( ".", ",", 2, "", "")';	
		}
		
		$this->columns = 
		    array(
		        array(
					'name' => '"number"',
					// 'data' => '"number"',
		            'visible' => 'true',
		            'className' => '"noColVis colvisGroupGenerelt"',
		            'orderable' => 'false',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
		            'order' => null,
		        ),
		        array(
					'name' => '"name"',
					'data' => '"name"',
		            'visible' => 'true',
		            'className' => '"noColVis colvisGroupGenerelt"',
		            'orderable' => 'true',
		            'searchable' => 'true',
		            'orderSequence' => '[ "desc","asc" ]',
		            'order' => null,
		        ),
		        array(
					'name' => '"gender"',
					'data' => '"gender"',
		            'visible' => 'true',
		            'className' => '"colvisGroupGenerelt"',
		            'orderable' => 'false',
		            'searchable' => 'true',
		            'orderSequence' => null,
					'order' => null,
					'render' => 'function (data,type){ 
						if (type === "display") {
							switch (data){
								case "male":
									return "Mand";
									break;
								case "female":
									return "Kvinde";
									break;
							}
						}
						return data;
					}'
		        ),
		        array(
					'name' => '"games_played"',
					'data' => '"games_played"',
		            'visible' => 'true',
		            'className' => '"colvisGroupGenerelt"',
		            'orderable' => 'true',
		            'searchable' => 'true',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => '$.fn.dataTable.render.number( ".", ",", 0, "", "")',
		        ),
		        array(
					'name' => '"points_total"',
					'data' => '"points_total"',
		            'visible' => 'true',
		            'className' => '"colvisGroupPoint"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => '"desc"',
					'render' => $defaultFormat
		        ),
		        array(
					'name' => '"error_total"',
					'data' => '"error_total"',
		            'visible' => 'false',
		            'className' => '"colvisGroupPoint"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"break_points"',
					'data' => '"break_points"',
		            'visible' => 'false',
		            'className' => '"colvisGroupPoint"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"win_loss"',
					'data' => '"win_loss"',
		            'visible' => 'false',
		            'className' => '"colvisGroupPoint"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"serve_total"',
					'data' => '"serve_total"',
		            'visible' => 'false',
		            'className' => '"colvisGroupServ"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"serve_error"',
					'data' => '"serve_error"',
		            'visible' => 'false',
		            'className' => '"colvisGroupServ"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"serve_ace"',
					'data' => '"serve_ace"',
		            'visible' => 'true',
		            'className' => '"colvisGroupServ"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"receive_total"',
					'data' => '"receive_total"',
		            'visible' => 'false',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"receive_error"',
					'data' => '"receive_error"',
		            'visible' => 'false',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"receive_position"',
					'data' => '"receive_position"',
		            'visible' => 'false',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
				),
				array(
					'name' => '"receive_pos_percent"',
					'data' => '"receive_pos_percent"',
		            'visible' => 'true',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => '$.fn.dataTable.render.number( ".", ",", 1, "", "%")'
		        ),
		        array(
					'name' => '"receive_perfect"',
					'data' => '"receive_perfect"',
		            'visible' => 'false',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
				),
				array(
					'name' => '"receive_perf_percent"',
					'data' => '"receive_perf_percent"',
		            'visible' => 'true',
		            'className' => '"colvisGroupModtagning"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => '$.fn.dataTable.render.number( ".", ",", 1, "", "%")'
		        ),
		        array(
					'name' => '"spike_total"',
					'data' => '"spike_total"',
		            'visible' => 'false',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"spike_error"',
					'data' => '"spike_error"',
		            'visible' => 'false',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"spike_blocked"',
					'data' => '"spike_blocked"',
		            'visible' => 'false',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        ),
		        array(
					'name' => '"spike_win"',
					'data' => '"spike_win"',
		            'visible' => 'true',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
				),
				array(
					'name' => '"kill_percent"',
					'data' => '"kill_percent"',
		            'visible' => 'true',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => '$.fn.dataTable.render.number( ".", ",", 1, "", "%")'
				),
				array(
					'name' => '"spike_eff"',
					'data' => '"spike_eff"',
		            'visible' => 'true',
		            'className' => '"colvisGroupAngreb"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => '$.fn.dataTable.render.number( ".", ",", 2, "", "")'
		        ),
		        array(
					'name' => '"block_win"',
					'data' => '"block_win"',
		            'visible' => 'true',
		            'className' => '"colvisGroupBlok"',
		            'orderable' => 'true',
		            'searchable' => 'false',
		            'orderSequence' => '[ "desc","asc" ]',
					'order' => null,
					'render' => $defaultFormat,
		        )
		    )
		;
	}

	function checkSettings(){
		foreach (array('type', 'context', 'length', 'query') AS $item){
			if (!isset($this->data[$item])){
				exit(ucfirst($item)." not set!");
			}
		}
	}

	function print(){
		$this->checkSettings();
		$count = $this->VolleyStats->getMysqlCount($this->query);	

		include('includes/gender_picker.php');
		echo '
		<table id="DataTable" class="table table-striped table-bordered table-hover table-sm nowrap" style="width:100%; display:none;">
		    <thead>
		        ';
		        foreach ($this->headers as $headerRows){
		        	echo '<tr>';
		        	foreach ($headerRows as $array){
		        		echo '<th';
		        		if ($array['colspan'] != null) echo ' colspan="'.$array['colspan'].'"';
		        		if ($array['rowspan'] != null) echo ' rowspan="'.$array['rowspan'].'" class="hasrowspan"';
		        		if ($array['category'] != null) echo ' data-category="'.$array['category'].'"';
		        		//if ($array['prio'] != null) echo ' data-priority="'.$array['prio'].'"';
		        		echo '>'.$array['title'].'</th>';
		        	}
		        	echo '</tr>';
		        }
		    echo '
		    </thead>
		    <tbody>
			';
			if ($result = $this->VolleyStats->db->query($this->query.' LIMIT '.$this->length)) {
				$table_columns = array_column($this->columns,'data');
				if ($result->num_rows>0){
					$n = 1;
					echo "<tr>";
					while($row = $result->fetch_assoc()) {
						echo "<td>".$n."</td>";	
						foreach ($table_columns as $item){
							$item = trim($item,'\'"');
							if (!empty($item)) echo "<td>".$row[$item]."</td>";	
						}
						$n++;
						echo "</tr>";
					}
					
				}
			}
		    echo ' 
		    </tbody>
		</table>
		<p>
		';

		$js = '
		<script type="text/javascript">
		$(document).ready( function () {
		    var dataTable = $("#DataTable").DataTable({
				"responsive": true,
		        "fixedHeader": true,
				"pageLength": '.$this->length.',
				"processing": true,
				"serverSide": true,
				"ajax": true,
				"deferLoading": '.$count.',
		        // "stateSave": true,
		        "language": {
		            "decimal":        ",",
		            "emptyTable":     "Ingen data",
		            "info":           "Viser _START_ til _END_ af _TOTAL_ linjer",
		            "infoEmpty":      "Viser 0 til 0 af 0 linjer",
		            "infoFiltered":   "(filtreret fra _MAX_ linjer)",
		            "infoPostFix":    "",
		            "thousands":      ".",
		            "lengthMenu":     "Vis _MENU_ linjer",
		            "loadingRecords": "Henter...",
		            "processing":     "<div class=\"spinner-border text-primary m-3\" role=\"status\"></div>",
		            "search":         "",
		            "zeroRecords":    "Ingen resultater matcher s&oslash;gningen",
		            "paginate": {
		                "first":      "F&oslash;rste",
		                "last":       "Sidste",
		                "next":       "N&aelig;ste",
		                "previous":   "Forrige"
		            },
		            "aria": {
		                "sortAscending":  ": activate to sort column ascending",
		                "sortDescending": ": activate to sort column descending"
		            },
		            "searchPlaceholder": "Søg på navn..",
		            buttons: {
		                colvis: "Kolonner"
		            }
		        },
		        "dom": "<\'top-buttons container-fluid px-0\'<\'row px-0\'<\'col-4 order-1 col-md-4 order-md-1 text-start\'B><\'col-12 order-12 col-md-4 order-md-2 text-md-center mb-2\'f><\'col-8 order-2 col-md-4 order-md-3 pr-0 text-end\'>>>rtpi",
		        "buttons": [
		            {
		                extend: "colvis",
		                collectionLayout: "two-column",
		                columns: ":not(.noColVis)",
		                columnText: function ( dt, idx, title ) {
			                return dt.column(idx).header().getAttribute("data-category")+\': \'+title;
			            }
		            }           
		        ],
		        "columns": [';
	        	foreach ($this->columns as $array){
	            		$js .= '
	            		{
						';
							if (!array_key_exists('data',$array)) $js .= '"data": '.$array['name'].',';
			                foreach ($array as $key => $value){
			                	if ($value != null AND $key != "order"){
			                		$js .= '"'.$key.'": '.$value.',
			                		';
			                	}

			                }
			            $js .= '},';
		            }
		        $js .= '
				],
		        "order": [ ';
		        $columnDefCount = 0;
	        	foreach ($this->columns as $array){
	                foreach ($array as $key => $value){
	                	if ($key == 'order' AND $value != null){
	                		$js .= '['.$columnDefCount.', '.$value.' ],';
	                	}
	                }
	                $columnDefCount++;
	            }
				$js .= '],
				
			});
			$("#DataTable").show();

		    dataTable.on( "draw.dt", function () {
		        dataTable.column(0, {order:"applied"}).nodes().each( function (cell, i) {
					var PageInfo = $("#DataTable").DataTable().page.info();
		            cell.innerHTML = i+1+PageInfo.start;
		        } );
		    } );

		    $(".dataTables_filter label input").removeClass("form-control-sm").addClass("ms-0");
		    $(".dataTables_wrapper .dt-buttons button").removeClass("btn-secondary").addClass("btn-primary");
			$("#gender_picker").show().appendTo($(".top-buttons div:nth-child(3)"));

		    $("#gender_picker",this).on("change", function () {
		    	var val = $("#gender_picker :checked").val();
	            if (val == "all" ){
	            	val = "";
				}

                dataTable
                	.columns(2)
                	//.search( val ? \'^\'+val+\'$\' : \'\', true, false)
                	.search( val , true, false)
                	.draw();
			} );
			
		});
		
		</script>';

		$this->jsFile = $js;
	}

	function ajax($get){
		$table = '('.$this->query.') AS t';
		$ajax_columns = array();
		foreach ($this->columns as $column){
			if (isset($column['data'])){
				$ajax_columns[] = array('dt' => trim($column['name'],'\'"'), 'db' => trim($column['data'],'\'"'));
			}else{
				$ajax_columns[] = array('dt' => trim($column['name'],'\'"', ));
			}
		}

		require('includes/secrets.php');

		$sql_details = array(
			'user' => $secrets['mysql_username'],
			'pass' => $secrets['mysql_password'],
			'db'   => $secrets['mysql_database'],
			'host' => $secrets['mysql_host']
		);

		require('includes/ssp.class.php');
		
		header('Content-type:application/json;charset=utf-8');
		echo json_encode(
			SSP::simple( $get, $sql_details, $table, $ajax_columns)
		);
		exit;
	}
}
?>