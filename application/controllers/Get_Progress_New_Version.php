 
<?php
class Get_Progress_New_Version extends CI_Controller
{
	 public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        // $this->load->model('AutoTransfer_model', 'transfer');
    }
	 public function index(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);	


		$sql = "Select * from sys_version_control where version_enable = '1'";
	 	$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
 		$id_version = $get[0]["version_id"];
 		$version_name_dashboard = $get[0]["version_name"];
		$sql_count = "SELECT
					 count(line_id) As Total_Line
				FROM
				sys_line_mst
				where 
				enable = '1' And
				chk_sys_flg = '1'";
		$query_count = $this->TBK_FA01->query($sql_count);
		$get_count = $query_count->result_array();
		$sql_departmet = "SELECT
			dep_cd,
			count(line_cd) As TotalLine_ByPD
		FROM
		sys_line_mst
		where 
		enable = '1' and 
        chk_sys_flg = '1' 
		GROUP BY 
		dep_cd 
		ORDER BY dep_cd asc";
		$query_department = $this->TBK_FA01->query($sql_departmet);
		$get_department = $query_department->result_array();
 		$sql2 = "SELECT line_id , version_name FROM sys_version_control, sys_log_program_version WHERE sys_version_control.version_enable = '1' And sys_log_program_version.version_id = '{$id_version}' and sys_version_control.version_id = sys_log_program_version.version_id GROUP BY sys_log_program_version.line_id  ,  sys_version_control.version_name";
	 	$query2 = $this->TBK_FA01->query($sql2);
	 	$get2 = $query2->result_array();
echo "<center><h1>DASHBOARD CHECK UPDATE VERSION $version_name_dashboard </h1></center>";
	 	echo "<table border= '1'>";
		echo "<tr>";
			echo "<th> Total Line </th>";
			echo "<th> Total Update Line </th>";
			echo "<th> Total Percent </th>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>".$get_count[0]["Total_Line"]."</td>";
			echo "<td>".count($get2)."</td>";
			echo "<td>".number_format((count($get2)/$get_count[0]["Total_Line"]*100),2)." %</td>";
		echo "</tr>";
		echo "</table>";	


		echo "<center>";
 		echo "<table border='2'>";
			echo "<tr>";
			echo "<th>PD</th>";
			echo "<th>Total Production Line</th>";
			echo "<th>Detail</th>";
			echo "<th>Total Update Production Line</th>";
			echo "<th>Percent </th>";
			// echo "<th>Upgrade Status</th>";
			// echo "<th>Latest Upgrade Date</th>";
			echo "</tr>";
		foreach ($get_department as $key => $value) {
			  $sql_byline = "SELECT
							line_cd 
						FROM
						sys_line_mst
						where 
						enable = '1' AND 
						chk_sys_flg = '1' AND 
						dep_cd = '{$value["dep_cd"]}'";
			$query_ByLine = $this->TBK_FA01->query($sql_byline);
	 		$get_ByLine = $query_ByLine->result_array();			
			echo "<tr>";
			echo "<td>".$value["dep_cd"]."</td>";
			echo "<td>".$value["TotalLine_ByPD"]."</td>";
			echo "<td>";
				echo "<table border = '1'>";
				echo "<tr>";
				echo "<th>Line Production </th>";
				echo "<th>Current Version </th>";
				echo "<th>Upgrade Status </th>";
				echo "<th>Latest Upgrade Date </th>";
				echo "</tr>";
				$sen = 0;
				foreach ($get_ByLine as $key => $value_ByLine) {
					$sqlBNLine = "SELECT
									sys_version_control.version_name , 
									sys_log_program_version.updated_date,
									sys_version_control.version_id
								FROM
									sys_line_mst,
									sys_log_program_version,
									sys_version_control 
								WHERE
								sys_line_mst.enable = '1' And 
								sys_line_mst.line_cd = sys_log_program_version.line_id AND
								sys_log_program_version.version_id = sys_version_control.version_id
								AND sys_log_program_version.status_flg = '1'
								AND sys_line_mst.chk_sys_flg = '1'
								AND sys_log_program_version.line_id = '{$value_ByLine["line_cd"]}'
								ORDER BY sys_line_mst.line_cd asc";
					$queryBNLine = $this->TBK_FA01->query($sqlBNLine);
	 				$getBNLine = $queryBNLine->result_array();
	 				$totalLineByDep = 0;
	 				$color_status = "";
	 				if(empty($getBNLine)){
	 					$versionName = " - ";
	 					$status = "Not Upgrade";
	 					$updated_date = " - ";
	 					$color_status = "#F76559";
	 				}else{
	 					$versionName = $getBNLine[0]["version_name"];
	 					$updated_date = $getBNLine[0]["updated_date"];
	 					if($getBNLine[0]["version_id"] == $id_version){
	 						$status = "Success";
	 						$sen++;
	 						$color_status = "#FFFFFF";
	 					}else{
	 						$status = "Not Upgrade";
	 						$color_status = "#F76559";
	 					}
	 				}		
				  echo "<tr>";
				  echo "<td>".$value_ByLine["line_cd"]."</td>";
				  echo "<td>".$versionName."</td>";
				  echo "<td bgcolor='$color_status'>".$status."</td>";
				  echo "<td>" .$updated_date. " </td>";
				  echo "</tr>";
				}
				echo "</table>";

			echo "</td>";
			echo "<td>".$sen."</td>";
			echo "<td>". number_format(($sen/$value["TotalLine_ByPD"])*100 , 2 ) ." % </td>";
			// echo "<td>Upgrade Status</td>";
			// echo "<td>Latest Upgrade Date</td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</center>";







































	 // 	$sql = "Select * from sys_version_control where version_enable = '1'";
	 // 	$query = $this->TBK_FA01->query($sql);
		// $get = $query->result_array();
 	// 	$id_version = $get[0]["version_id"];
 	// 	$sql2 = "SELECT
		// 			* 
		// 		FROM
		// 			sys_version_control,
		// 			sys_log_program_version
		// 			WHERE 
		// 			sys_version_control.version_enable = '1' And 
		// 			sys_log_program_version.version_id = '{$id_version}' and 
		// 			sys_version_control.version_id = sys_log_program_version.version_id";
	 // $query2 = $this->TBK_FA01->query($sql2);
	 // $get2 = $query2->result_array();

	 // echo "Total  : ".$get_count[0]["Total_Line"]." Line";
	 // echo "<table border='2'>";
	 // echo "<tr>";
	 // 	echo "<th> No </th>";
	 // 	echo "<th> Line Production </th>";
	 // echo "</tr>";
	 // $i = 1;
	 // foreach ($get2 as $key => $value) {
	 //  echo "<tr>";
	 // 	echo "<td>".$i."</td>";
	 // 	echo "<td>".$value["line_id"]."</td>";
	 // echo "</tr>";
	 // $i++;
	 // }
	 // echo "</table>";
}
}
?>