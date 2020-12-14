<?php 
class dataTable {
	var $headers;
	var $columnDefs;
	var $data;
	var $filter;
	var $jsFile;

	function setHeaders($array){
		if (is_array($array)) $this->headers = $array;
	}

	function setColumnDefs($array){
		if (is_array($array)) $this->columnDefs = $array;
	}

	function setData($array){
		if (is_array($array)) $this->data = $array;
	}

	function setFilter($array){
		if (is_array($array)) $this->filter = $array;
	}

	function drawTable(){
		if (!is_array($this->headers)) exit('Headers not set!');
		if (!is_array($this->columnDefs)) exit('columnDefs not set!');
		if (!is_array($this->data)) exit('Data not set!');

		if (isset($this->filter)){
			echo '
			<p>
			    '.$this->filter['text'].'
			</p>
			';
		}

		$js = '
		<script type="text/javascript">';
		$js .= '
		$(document).ready( function () {
			';
			if (isset($this->filter)){
				$js .= '
				$.fn.dataTable.ext.search.push(
			        function( settings, data, dataIndex ) {
			            var min = parseInt( $("#played_games_min").val(), 10 );
			            // var max = parseInt( $("#max").val(), 10 );
			            var max = parseInt( "", 10 );
			            var games_played = parseFloat( data['.$this->filter["columnNumber"].'] ) || 0; // use data from the games played column
			     
			            if ( ( isNaN( min ) && isNaN( max ) ) ||
			                 ( isNaN( min ) && games_played <= max ) ||
			                 ( min <= games_played   && isNaN( max ) ) ||
			                 ( min <= games_played   && games_played <= max ) )
			            {
			                return true;
			            }
			            return false;
			        }
			    );

			    $("input#played_games_min").keyup( function() {
			        dataTable.draw();
			    } );
				';
			}
			$js .= '
		    var dataTable = $("#DataTable").DataTable({
		        //"responsive": true,
		        "fixedHeader": true,
		        "pageLength": 30,
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
		            "loadingRecords": "Hender...",
		            "processing":     "Arbejder...",
		            "search":         "",
		            "zeroRecords":    "Ingen linjer matcher s&oslash;gningen",
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
		                colvis: "Rediger kolonner"
		            }
		        },
		        "dom": "<\'top-buttons container-fluid px-0\'<\'row px-0\'<\'col-md\'B><\'col-md text-center\'><\'col-md pr-0\'f>>>rtpi",
		        "buttons": [
		            ';
		            // foreach ($this->headers[0] as $array){
		            // 	if ($array['filter_button']){
		            // 		$js .= '
		            // 		{
				          //       text: "'.$array['title'].'",
				          //       extend: "colvis",
				          //       columns: ".colvisGroup'.$array['title'].'",
				          //   },';		
		            // 	}
		            // }
		            $js .= '
		            {
		                extend: "colvis",
		                collectionLayout: "two-column",
		                columns: ":not(.noColVis)",
		                columnText: function ( dt, idx, title ) {
			                return dt.column(idx).header().getAttribute("data-category")+\': \'+title;
			            }
		            }           
		        ],
		        columnDefs: [';
		        $columnDefCount = 0;
	        	foreach ($this->columnDefs as $array){
	            		$js .= '
	            		{
	            			"targets": '.$columnDefCount.',
	            			';
			                foreach ($array as $key => $value){
			                	if ($value != null AND $key != "order"){
			                		$js .= '"'.$key.'": '.$value.',
			                		';
			                	}

			                }
			            $js .= '},';
			            $columnDefCount++;
		            }
		        $js .= '
		        ],
		        "order": [ ';
		        $columnDefCount = 0;
	        	foreach ($this->columnDefs as $array){
	                foreach ($array as $key => $value){
	                	if ($key == 'order' AND $value != null){
	                		$js .= '['.$columnDefCount.', '.$value.' ],';
	                	}
	                }
	                $columnDefCount++;
	            }
		    	$js .= '],
		    });

		    dataTable.on( "order.dt search.dt", function () {
		        dataTable.column(0, {order:"applied"}).nodes().each( function (cell, i) {
		            cell.innerHTML = i+1;
		        } );
		    } ).draw();

		    $(".dataTables_filter label input").removeClass("form-control-sm").addClass("ms-0");
		    $(".dataTables_wrapper .dt-buttons button").removeClass("btn-secondary").addClass("btn-primary");
		    $("#gender_picker").show().appendTo($(".top-buttons div:nth-child(2)"));

		    $("#gender_picker",this).on("keyup change", function () {
		    	var val = $("#gender_picker :checked").val();
	            if (val == "all" ){
	            	val = "";
	            }
                // dataTable.column(2).search(val,false,false,true).draw();
                dataTable
                	.column(2)
                	.search( val ? \'^\'+val+\'$\' : \'\', true, false)
                	.draw();
	        } );
		});
		</script>';

		$this->jsFile = $js;

		include('includes/gender_picker.php');
		echo '
		<table id="DataTable" class="table table-hover table-sm" style="width:100%">
		    <thead>
		        ';
		        foreach ($this->headers as $headerRows){
		        	echo '<tr>';
		        	foreach ($headerRows as $array){
		        		echo '<th';
		        		if ($array['colspan'] != null) echo ' colspan="'.$array['colspan'].'"';
		        		if ($array['rowspan'] != null) echo ' rowspan="'.$array['rowspan'].'" class="hasrowspan"';
		        		if ($array['category'] != null) echo ' data-category="'.$array['category'].'"';
		        		echo '>'.$array['title'].'</th>';
		        	}
		        	echo '</tr>';
		        }
		    echo '
		    </thead>
		    <tbody>
		    ';
		    foreach ($this->data as $tr){
	        		echo "<tr>\n";
	        		foreach ($tr as $td){
	        			if (is_array($td)){
	        				echo "<td data-order='".$td[0]."' data-filter='".$td[0]."'>".$td[1]."</td>\n";
	        			}else{
	        				echo "<td>".$td."</td>\n";	
	        			}
	        		}
	        		echo "</tr>\n";
	        	}
		    echo ' 
		    </tbody>
		</table>
		            
		<p>
		';
	}
}
?>