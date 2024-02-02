<?php
class API_REPRINT_FA_APP extends CI_Controller
{
	public function Get_Table_tag_print_detail_bk(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME like 'tag_print_detail_bk%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get;
		}else{
			return 0;
		}
	}
	public function Get_Table_sup_workplan_supply_dev_bk(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME like 'sup_work_plan_supply_dev%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get;
		}else{
			return 0;
		}
	}
	public function Get_Result_supply_dev(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$wi = $_GET["wi"];
		$Data_Name_Table = $this->Get_Table_sup_workplan_supply_dev_bk();
		$sql = "SELECT * FROM sup_work_plan_supply_dev where WI = '{$wi}' and LVL = '1'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			foreach ($Data_Name_Table as $key => $value) {
				$TableName = $value["TABLE_NAME"];
				$sql = "SELECT * FROM $TableName where WI = '{$wi}' and LVL = '1'";
				$query = $this->TBK_FA01->query($sql);
				$get = $query->result_array();
				if(!empty($get)){
					echo json_encode($get);
					return ;
				} 
			}
			return "";
		}else{
			echo json_encode($get);
		}
	}
	public function Get_Result_tag_Print(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$qrCode = $_GET["qr_code"];
		$Data_Name_Table = $this->Get_Table_tag_print_detail_bk();
		$sql = "SELECT * FROM tag_print_detail where qr_detail = '{$qrCode}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			foreach ($Data_Name_Table as $key => $value) {
				$TableName = $value["TABLE_NAME"];
				$sql = "SELECT * FROM $TableName where qr_detail = '{$qrCode}'";
				$query = $this->TBK_FA01->query($sql);
				$get = $query->result_array();
				if(!empty($get)){
					echo json_encode($get);
					return ;
				} 
			}
			return "";
		}else{
			echo json_encode($get);
		}
	}
	public function checkId($id){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$Data_Name_Table = $this->Get_Table_tag_print_detail_bk();
		  $sql = "SELECT * FROM tag_print_detail where id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			foreach ($Data_Name_Table as $key => $value) {
				$TableName = $value["TABLE_NAME"];
				$sql = "SELECT * FROM $TableName where id = '{$id}'";
				$query = $this->TBK_FA01->query($sql);
				$get = $query->result_array();
				if(!empty($get)){
					return $TableName;
				} 
			}
		}else{
				return "tag_print_detail";
		}
	}
	public function Update_statsus_tag_print_detail(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
	 	$id = $_GET["id"];
		$Data_Name_Table = $this->checkId($id);
		$sql = "UPDATE $Data_Name_Table SET flg_control = 9, updated_date = CURRENT_TIMESTAMP where id = '{$id}'";
		  $query = $this->TBK_FA01->query($sql);
	}
	public function get_data_to_reprint_log(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$line = $_GET["line"];
		$box = $_GET["box"];
		$qty = $_GET["qty"];
		$part_no = $_GET["part_no"];
		$lot = $_GET["lot"];
	 	$sql = " SELECT * FROM [dbo].[log_reprint_app] WHERE 
[log_qr_detail] LIKE '%$part_no%' AND [log_qr_detail] LIKE '%$lot%' AND [log_qr_detail] LIKE '%$line%' AND [log_new_box_no] = '$box' AND [log_new_qty] = '$qty'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array(); 
		if(empty($get)){
 			echo "0";
		}else{
			echo json_encode($get); 
		}
	}
	public function get_data_all(){


		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$get =  $this->check_log_reprint_app($id);
		if(empty($get)){
			$get =  $this->checkId($id);	
			//  $sql = "select * from $table_name where log_id = '{$id}'" ;
		// $query = $this->TBK_FA01->query($sql);
		// $get = $query->result_array();
		}
		if(empty($get)){
 			echo "0";
		}else{
			echo json_encode($get); 
		}
	}
	public function get_data_tag_new_fa(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$sql = "SELECT next_proc FROM tag_print_detail where wi = '{$wi}'" ;
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			echo "0";
		}else{
			echo $get[0]["next_proc"]; 
		}
	}
	public function check_log_reprint_app($id){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$table_name =  $this->check_log_reprint_bk($id);
		$sql = "select Top 1 * from $table_name where log_id = '{$id}'" ;
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			return  "0";
		}else{
			if($get[0]["log_ref_db"] == "2"){
				//  query data join for get wi 
				return  $get; 
			}else{
				$log_ref_id = $get[0]["log_ref_id"] ;
				$sql_check_ref = "select * from $table_name where log_ref_id = '{$log_ref_id}'";
				$query_check_ref = $this->TBK_FA01->query($sql_check_ref);
				$get_check_ref = $query_check_ref->result_array();
				check_data:
				$log_id_loop = "";
				$log_ref_db_loop = "";
				$log_ref_id_loop = "";
				foreach ($get_check_ref as $key => $value) {
 					 $result_ok = $this->check_ref_log_reprint_app($value["log_id"], $value["log_ref_id"] , $table_name , $value["log_ref_db"]);
						$log_id_loop  = $value["log_id"];
						$log_ref_db_loop  = $value["log_ref_db"];
						$log_ref_id_loop =  $value["log_ref_id"];
						if($result_ok[0]["log_ref_db"] == "2"){
							return  $result_ok;
						}
 					}
 					if($log_ref_db_loop == "1"){
						$get_check_ref =  $this->get_data_log_reprint($table_name , $log_ref_db_loop , $log_ref_id_loop);
						// print_r($get_check_ref);
						// exit();
						goto check_data;
 					}
				}
			}
		}
	public function get_data_log_reprint($table_name , $log_ref_db_loop , $log_ref_id_loop){
		    $this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
			$sql_check_ref = "select * from $table_name where log_id = '{$log_ref_id_loop}'";
			$query_check_ref = $this->TBK_FA01->query($sql_check_ref);
			$get_check_ref = $query_check_ref->result_array();
			if(empty($get_check_ref)){
				return "0";
			}else{
				return $get_check_ref;
			}
	}
	public function check_ref_log_reprint_app($pk_log_id , $log_ref_id , $table_name , $log_ref_db){
			if($log_ref_db == "2"){
				  $sql_join = "SELECT
				 				 top 1 * 
								FROM
									log_reprint_app,
									tag_print_detail 
								WHERE
									  log_reprint_app.log_id = '{$pk_log_id}'
									AND log_reprint_app.log_ref_id = '{$log_ref_id}' 
								AND tag_print_detail.id = '{$log_ref_id}'
									AND log_reprint_app.log_ref_id = tag_print_detail.id
							 ";
					$query_join = $this->TBK_FA01->query($sql_join);
					$get_join = $query_join->result_array();
					return $get_join ;
			}else{
				 // echo "<br>";
				 // echo $pk_log_id;
				 //  echo "<br>";
				 // echo $log_ref_db ;
				 //  echo "<br>";
				 // echo $table_name; 
					// echo "A";
					//   echo "<br>";
					//     echo "<br>";
					// // exit();
					return 0;
			}
		}
		public function check_log_reprint_bk($id){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$Data_Name_Table = $this->Get_Table_log_reprint_app_bk();
		  $sql = "SELECT * FROM log_reprint_app where log_id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			foreach ($Data_Name_Table as $key => $value) {
				$TableName = $value["TABLE_NAME"];
				$sql = "SELECT * FROM $TableName where log_id = '{$id}'";
				$query = $this->TBK_FA01->query($sql);
				$get = $query->result_array();
				if(!empty($get)){
					return $TableName;
				} 
			}
		}else{
				return "log_reprint_app";
		}
	}

	public function Get_Table_log_reprint_app_bk(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME like 'log_reprint_app%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get;
		}else{
			return 0;
		}
	}

	public function get_data_tag_log(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$qrCode = $_GET["qr_code"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$startDate = date("Y-m-d"); 
		$tbName = $this->getTable_sys_database_dict("log_reprint_app" , $startDate , $startDate);
		 	  $sql = "SELECT * FROM $tbName where log_qr_detail = '{$qrCode}'";
			$query = $this->TBK_FA01->query($sql);
			$get = $query->result_array();
			if(empty($get)){
	 			echo  "0";
			}else{
				echo  json_encode($get);
			}
	}
	public function get_data_tag_log_reprint(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$line = $_GET["line"];
		$year = $_GET["year"];
		$partNo = $_GET["part_no"];
		$lot_no = $_GET["lot_no"];
		$startDate = date("Y-m-d"); 
		$tbName = $this->getTable_sys_database_dict("log_reprint_app" , $startDate , $startDate);
		  $sql = "SELECT
					* 
				FROM
					$tbName 
				WHERE
					log_status <> '9' 
					AND ( [log_qr_detail] LIKE '%{$line}%' AND [log_qr_detail] LIKE '%{$year}%' AND [log_qr_detail] LIKE '%{$partNo}%' AND [log_qr_detail] LIKE '%{$lot_no}%' )";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			echo "0";
		}else{
			echo json_encode($get);
		}

	}
	public function getTable_sys_database_dict($main_table , $startDate , $endDate){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "SELECT
		* 
	FROM
		sys_database_dict 
	WHERE
		sdd_main_table_name = '$main_table' 
		AND CONVERT ( DATE, sdd_info_start_date ) <= '{$startDate}' 
		AND CONVERT ( DATE, sdd_info_end_date ) >= '{$startDate}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			return "0";
		}else{
			return $get[0]["sdd_backup_table_name"]; 
		}
	}
	public function getMaxBox_reprintPD4(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi_tag = $_GET["wi_tag"];
		$lot_tag = $_GET["lot_tag"];
		$seq_tag = $_GET["seq_tag"]; 
		$sql = "Select count(box_no) as MaxBoxNo from tag_print_detail where wi = '{$wi_tag}' and TRIM( SUBSTRING(qr_detail, 59, 4 )) = '{$lot_tag}' and seq_no = '{$seq_tag}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			echo "0";
		}else{
			echo $get[0]["MaxBoxNo"]; 
		}
	}
	public function get_data_to_reprint_new_fa(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$line = $_GET["line"];
		$actaul_date = $_GET["actaul_date"];
		$lot_no =  $_GET["lot_no"];
		$wi =  $_GET["wi"];
		$startDate = date("Ymd", strtotime($actaul_date));
		$tbName = $this->getTable_sys_database_dict("tag_print_detail" , $startDate , $startDate);
		$tbName2 = $this->getTable_sys_database_dict("LOG_REPRINT_APP" , $startDate , $startDate);
		   $sql = " 
				SELECT
					A.FA_ID AS FA_ID,
					ISNULL(A.RE_ID , 0)AS RE_ID,
					A.WI AS WI,
					A.QR_DETAIL AS QR_DETAIL,
					TRIM( SUBSTRING(A.QR_DETAIL, 20, 25 )) As ITEM_CD , 
					A.BOX_NO AS BOX_NO,
					A.SEQ_NO AS SEQ_NO,
					A.SHIFT AS SHIFT,
					A.NEXT_PROC AS NEXT_PROC,
					A.FLG_CONTROL AS FLG_CONTROL,
				CASE
						WHEN A.FLG_CONTROL = 1 THEN
						'Completed Tag' 
						WHEN A.FLG_CONTROL = 0 THEN
						'Incomplete Tag' ELSE 'Reprint Tag' 
					END AS STATUS_TAG 
				FROM
					(
					SELECT
						ID AS FA_ID,
						NULL AS RE_ID,
						WI AS WI,
						QR_DETAIL AS QR_DETAIL,
						BOX_NO AS BOX_NO,
						SEQ_NO AS SEQ_NO,
						SHIFT AS SHIFT,
						NEXT_PROC AS NEXT_PROC,
						FLG_CONTROL AS FLG_CONTROL 
					FROM
						$tbName 
					WHERE
						( FLG_CONTROL = 0 OR FLG_CONTROL = 1 ) 
						AND ( QR_DETAIL LIKE '%{$line}%' AND QR_DETAIL LIKE '%{$actaul_date}%' AND QR_DETAIL LIKE '%{$lot_no}%' AND WI LIKE '%{$wi}%' ) UNION ALL
						(
						SELECT
							A.ID AS FA_ID,
							B.RE_ID AS RE_ID,
							A.WI AS WI,
							B.QR_DETAIL AS QR_DETAIL,
							B.BOX_NO AS BOX_NO,
							A.SEQ_NO AS SEQ_NO,
							A.SHIFT AS SHIFT,
							B.NEXT_PROC AS NEXT_PROC,
							A.FLG_CONTROL AS FLG_CONTROL 
						FROM
							(
							SELECT
								ID,
								WI,
								SEQ_NO,
								SHIFT,
								FLG_CONTROL 
							FROM
								$tbName 
							WHERE
								FLG_CONTROL = 9 
								AND ( QR_DETAIL LIKE '%{$line}%' AND QR_DETAIL LIKE '%{$actaul_date}%' AND QR_DETAIL LIKE '%{$lot_no}%' AND WI LIKE '%{$wi}%' ) 
							) A
							LEFT OUTER JOIN (
							SELECT
								LOG_ID AS RE_ID,
								LOG_REF_ID AS ID,
								LOG_QR_DETAIL AS QR_DETAIL,
								LOG_NEW_QTY AS NEW_QTY,
								LOG_NEW_BOX_NO AS BOX_NO,
								LOG_NEW_NEXT_PROC AS NEXT_PROC 
							FROM
								$tbName2
							WHERE
								LOG_REF_DB = '2' 
								AND LOG_STATUS = '1' 
							) B ON A.ID = B.ID 
						) 
					) AS A 
				ORDER BY
					FA_ID,
					RE_ID ASC
		  ";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
 			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	// name
		Public Function get_max_boxno($id){
			parse_str($_SERVER['QUERY_STRING'], $_GET);
			$id = $_GET["id"];
			$startDate = date("Y-m-d"); 
			$tbName = $this->getTable_sys_database_dict("tag_print_detail" , $startDate , $startDate);
			$sql="SELECT max(log_new_box_no) as box_no FROM $tbName where log_ref_id = '{$id}'";
			$query = $this->TBK_FA01->query($sql);
			$get = $query->result_array();
			if(empty($get)){
 				echo "0";
			}else{
				$rs = "";
				$box = $get[0]["box_no"];
				$lengthData = count($box);
				if($lengthData = 1){
					$rs = "00".$box;
				}else if($lengthData = 2){
					$rs = "0".$box;
				}else{
					$rs = $box;
				}
				echo $rs;
			}
		}
		public function get_data_to_reprint_new_faPD4(){
			parse_str($_SERVER['QUERY_STRING'], $_GET);
			$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
			$line = $_GET["line"];
			$actaul_date = $_GET["actaul_date"];
			$lot_no = $_GET["lot_no"];
			$wi = $_GET["wi"];
			$boxNo = $_GET["boxNo"];
			 $startDate = date("Y-m-d", strtotime($actaul_date));
			 $tbName = $this->getTable_sys_database_dict("tag_print_detail" , $startDate , $startDate);
			$tbName2 = $this->getTable_sys_database_dict("LOG_REPRINT_APP" , $startDate , $startDate);
			   $sql = " 
						SELECT
							A.FA_ID AS FA_ID,
							ISNULL(A.RE_ID,0) As  RE_ID,
							A.WI AS WI,
							A.QR_DETAIL AS QR_DETAIL,
							TRIM( SUBSTRING(A.QR_DETAIL, 20, 25 )) As ITEM_CD , 
							A.BOX_NO AS BOX_NO,
							A.SEQ_NO AS SEQ_NO,
							A.SHIFT AS SHIFT,
							A.NEXT_PROC AS NEXT_PROC,
							A.FLG_CONTROL AS FLG_CONTROL,
						CASE
								WHEN A.FLG_CONTROL = 1 THEN
								'Completed Tag' 
								WHEN A.FLG_CONTROL = 0 THEN
								'Incomplete Tag' ELSE 'Reprint Tag' 
							END AS STATUS_TAG 
						FROM
							(
							SELECT
								ID AS FA_ID,
								NULL AS RE_ID,
								WI AS WI,
								QR_DETAIL AS QR_DETAIL,
								BOX_NO AS BOX_NO,
								SEQ_NO AS SEQ_NO,
								SHIFT AS SHIFT,
								NEXT_PROC AS NEXT_PROC,
								FLG_CONTROL AS FLG_CONTROL 
							FROM
								TAG_PRINT_DETAIL 
							WHERE
						        box_no = $boxNo AND
								( FLG_CONTROL = 0 OR FLG_CONTROL = 1 ) 
								AND ( QR_DETAIL LIKE '%{$line}%' AND QR_DETAIL LIKE '%{$actaul_date}%' AND QR_DETAIL LIKE '%{$lot_no}%' AND WI LIKE '%{$wi}%' ) UNION ALL
								(
								SELECT
									A.ID AS FA_ID,
									B.RE_ID AS RE_ID,
									A.WI AS WI,
									B.QR_DETAIL AS QR_DETAIL,
									B.BOX_NO AS BOX_NO,
									A.SEQ_NO AS SEQ_NO,
									A.SHIFT AS SHIFT,
									B.NEXT_PROC AS NEXT_PROC,
									A.FLG_CONTROL AS FLG_CONTROL 
								FROM
									(
									SELECT
										ID,
										WI,
										SEQ_NO,
										SHIFT,
										FLG_CONTROL 
									FROM
										TAG_PRINT_DETAIL 
									WHERE
						                box_no = $boxNo AND
										FLG_CONTROL = 9 
										AND ( QR_DETAIL LIKE '%{$line}%' AND QR_DETAIL LIKE '%{$actaul_date}%' AND QR_DETAIL LIKE '%{$lot_no}%' AND WI LIKE '%{$wi}%' ) 
									) A
									LEFT OUTER JOIN (
									SELECT
										LOG_ID AS RE_ID,
										LOG_REF_ID AS ID,
										LOG_QR_DETAIL AS QR_DETAIL,
										LOG_NEW_QTY AS NEW_QTY,
										LOG_NEW_BOX_NO AS BOX_NO,
										LOG_NEW_NEXT_PROC AS NEXT_PROC 
									FROM
										LOG_REPRINT_APP 
									WHERE
										LOG_REF_DB = '2' 
										AND LOG_STATUS = '1' 
									) B ON A.ID = B.ID 
								) 
							) AS A 
						ORDER BY
							FA_ID,
							RE_ID ASC

			";
	$query = $this->TBK_FA01->query($sql);
	$get = $query->result_array();
	if(empty($get)){
 		echo "0";
	}else{
		echo json_encode($get);
	}
	}
}
?>