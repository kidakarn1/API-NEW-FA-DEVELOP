<?php
class API_REPRINT_FA_APP_UPDATE extends CI_Controller
{
	public function check_sys_database_dict($stdate , $eddate , $tbName ){
		  $sql = "SELECT * FROM sys_database_dict WHERE sdd_main_table_name = '{$tbName}' AND CONVERT ( DATE, sdd_info_start_date ) <= '{$stdate}' AND CONVERT ( DATE, sdd_info_end_date ) >= '{$eddate}' and sdd_status_flag = '1' order by sdd_id desc ";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get[0]["sdd_backup_table_name"];
		}else{
			return 0;
		}
	} 
	public function Get_Table($name_tb){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME like '{$name_tb}%' and (TABLE_NAME != 'tag_print_detail_defact' and   TABLE_NAME != 'tag_print_detail_genarate' and TABLE_NAME != 'tag_print_detail_sub'   and TABLE_NAME != 'tag_print_detail_main' ) ORDER BY TABLE_NAME desc";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get;
		}else{
			return 0;
		}
	}
	public function Get_Table_Sub_Detail(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME like '{$tag_print_detail_sub}%') ";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get;
		}else{
			return 0;
		}
	}
	public function Get_data_dict_backup(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql  = "Select * from data_dict_backup where ddb_status = '1'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			return $get[0]["ddb_id"];
		}else{
			return 0;
		}
	}
	public function get_log_ref_id(){
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$log_qr_detail = $_GET["log_qr_detail"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "SELECT min(log_cur_box_no) as cur_box from log_reprint_app where log_qr_detail  Like '%{$log_qr_detail} %'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql  = "SELECT min(log_cur_box_no) as cur_box from $TableName where log_qr_detail  Like '%{$log_qr_detail} %'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_all(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		parse_str($_SERVER['QUERY_STRING'], $_GET); 
		$id = $_GET["id"];
		$sql = "Select * from tag_print_detail where id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT * FROM $TableName where id = '{$id}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_tag_log(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$qr_code = $_GET["qr_code"];
		$stdate =  substr($qr_code,8,8);
		$tbName1 = $this->check_sys_database_dict($stdate , $stdate , "log_reprint_app");
		if(empty($tbName1)){
			$tbName1 = "log_reprint_app";
		}
	 	$sql = "SELECT * FROM $tbName1 where log_qr_detail = '{$qr_code}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					 $sql = "SELECT * FROM $TableName where log_qr_detail = '{$qr_code}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function GET_DATA_TAG(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$qr_code = Trim($_GET["qr_code"]);
		$stdate =  substr($qr_code,8,8);
		$tbName1 = $this->check_sys_database_dict($stdate , $stdate , "tag_print_detail");
		if(empty($tbName1)){
			$tbName1 = "tag_print_detail";
		}
	 	$sql = "SELECT * FROM $tbName1 where qr_detail = '{$qr_code}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT * FROM $TableName where qr_detail = '{$qr_code}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function GET_DATA_TAG_2(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
	 	$sql = "SELECT * , ISNULL( PKG_UNIT_QTY, PS_UNIT_NUMERATOR  ) AS PS_UNIT_NUMERATOR  FROM sup_work_plan_supply_dev where WI = '{$wi}' and LVL = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("sup_work_plan_supply_dev_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					  $sql = "SELECT * , ISNULL( PKG_UNIT_QTY, PS_UNIT_NUMERATOR  ) AS PS_UNIT_NUMERATOR  FROM $TableName where WI = '{$wi}' and LVL = 1";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function insert_log_print(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$log_ref = $_GET["log_ref"];
		$userID = $_GET["userID"];
		$sql = " INSERT INTO log_reprint_tag (log_system_typ,log_ref_db,log_ref_tag_id,log_created_date,log_created_by)VALUES('1','1','{$log_ref}',CURRENT_TIMESTAMP,'{$userID}')";
		$query = $this->TBK_FA01->query($sql);
	}

	public function get_data_to_reprint_log(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$part_no = $_GET["part_no"]; 
		$line = $_GET["line"];
		$lot = $_GET["lot"];
		$box = $_GET["box"];
		$qty = $_GET["qty"];
	 	$sql = "SELECT * FROM [dbo].[log_reprint_app] WHERE [log_qr_detail] LIKE '%{$part_no}%' AND [log_qr_detail] LIKE '%{$lot}%' AND [log_qr_detail] LIKE '%{$line}%' AND [log_new_box_no] = '{$box}' AND [log_new_qty] = {$qty}";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				$sql = "SELECT * FROM [dbo].[{$TableName}] WHERE [log_qr_detail] LIKE '%{$part_no}%' AND [log_qr_detail] LIKE '%{$lot}%' AND [log_qr_detail] LIKE '%{$line}%' AND [log_new_box_no] = '{$box}' AND [log_new_qty] = {$qty}";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_log_ref_id_1(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);	
		$qr = $_GET["qr"]; 
		$id = $_GET["id"];
		$stdate =  substr($qr,8,8);
		$tbName1 = $this->check_sys_database_dict($stdate , $stdate , "log_reprint_app");
		if(empty($tbName1)){
			$tbName1 = "log_reprint_app";
		}
	 	$sql = "SELECT min(log_id) as log_id from $tbName1 where log_cur_box_no = '{$id}' and  log_qr_detail  LIKE '%{$qr}%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				$sql = " SELECT min(log_id) as log_id from $TableName where log_cur_box_no = '{$id}' and  log_qr_detail  LIKE '%{$qr}%'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_log_ref_id_2(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
	 	// $sql = " select log_ref_id from log_reprint_app where log_id = '{$id}'";
	 	$sql = " select * from log_reprint_app where log_id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				// $sql = " SELECT min(log_id) as log_id from $TableName where log_id = '{$id}'";
					$sql = " SELECT  * from $TableName where log_id = '{$id}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
 						if($get[0]["log_ref_db"] == "1"){
							$status_while = true;
							$log_id = $get[0]["log_id"];
							while($status_while){ 
								$result = $this->check_log_ref_db($log_id, "log_reprint_app");
								$log_id = $result["log_id"];
								if($result["status"] == "Stop"){
									$status_while = false;
									$data = array("log_ref_id" => $result["log_id"]);
									echo json_encode($data);
									return ;
								}
							}
						}else{
							$data = array("log_ref_id" => $get[0]["log_id"]);
							echo json_encode($data);
							return ;
						}
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			if($get[0]["log_ref_db"] == "1"){
				$status_while = true;
				$log_id = $get[0]["log_id"];
				while($status_while){ 
					$result = $this->check_log_ref_db($log_id, "log_reprint_app");
					$log_id = $result[0]["log_id"];
					if($result[0]["status"] == "Stop"){
						$status_while = false;
						$data[0] = array("log_ref_id" => $result[0]["log_id"]);
						echo json_encode($data);
						return ;
					}
				}

			}else{
				$data[0] = array("log_ref_id" => $get[0]["log_ref_id"]);
				echo json_encode($data);
				return ;
			}
		}
	}

	public function check_log_ref_db($log_id , $tbName){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
	 	  $sql = " select * from  $tbName where log_id = '{$log_id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			return "0";
		}else{
			if($get[0]["log_ref_db"] == "1"){
				$data[0] = array("status" => "Next" , "log_id" => $get[0]["log_ref_id"]);
			}else{
				$data[0] = array("status" => "Stop" , "log_id" =>$get[0]["log_ref_id"]);
			}
			return $data;
		}
	}
public function get_data_to_reprint_new_fa(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$line = $_GET["line"];
		 $actaul_date = $_GET["actaul_date"];
		$lot_no = $_GET["lot_no"];
		$wi = $_GET["wi"];
		$tbName1 = $this->check_sys_database_dict($actaul_date , $actaul_date , "tag_print_detail");
		$tbName2 = $this->check_sys_database_dict($actaul_date , $actaul_date , "log_reprint_app");
	 	     $sql = "
				SELECT
					A.FA_ID AS FA_ID,
					A.RE_ID AS RE_ID,
					A.WI AS WI,
					A.QR_DETAIL AS QR_DETAIL,
					TRIM(SUBSTRING(A.QR_DETAIL, 20, 25 )) As ITEM_CD , 
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
						$tbName1  
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
								$tbName1  
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
					RE_ID ASC";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			$Data_Name_Table2 = $this->Get_Table("log_reprint_app_bk");
			$Table2= $Data_Name_Table2[0]["TABLE_NAME"];
			if(empty($Table2)){
				$Table2 = "log_reprint_app";
			}
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				 $sql = "
						SELECT
							A.FA_ID AS FA_ID,
							A.RE_ID AS RE_ID,
							A.WI AS WI,
							A.QR_DETAIL AS QR_DETAIL,
							TRIM(SUBSTRING(A.QR_DETAIL, 20, 25 )) As ITEM_CD , 
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
								$TableName 
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
										$TableName 
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
										$Table2 
									WHERE
										LOG_REF_DB = '2' 
										AND LOG_STATUS = '1' 
									) B ON A.ID = B.ID 
								) 
							) AS A 
						ORDER BY
							FA_ID,
							RE_ID ASC";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}

	public function get_data_tag_new_fa(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
	 	$sql = " SELECT top 1 next_proc FROM tag_print_detail where wi = '{$wi}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				$sql = " SELECT next_proc FROM $TableName where wi = '{$wi}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_to_reprint_main_m83(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$lot_no = $_GET["lot_no"];
		$prod_date = $_GET["prod_date"];
		$tbName1 = $this->check_sys_database_dict($prod_date , $prod_date , "tag_print_detail_main");
		if(empty($tbName1)){
			$tbName1 = "tag_print_detail_main";
		}
		$sql = "SELECT
				TAG_ID,
				TAG_REF_STR_ID,
				TAG_REF_END_ID,
				LINE_CD,
				TAG_WI_NO,
				TAG_QR_DETAIL,
				TAG_BATCH_NO,
				TAG_NEXT_PROC 
			FROM
				$tbName1 
			WHERE
				LINE_CD = 'K1M083' 
				AND ( TAG_WI_NO LIKE '%{$wi}%' AND TAG_QR_DETAIL LIKE '%{$lot_no}%' AND TAG_QR_DETAIL LIKE '%{$prod_date}%' ) 
				AND FLG_CONTROL <> '2'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_main_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				$sql = "SELECT
							TAG_ID,
							TAG_REF_STR_ID,
							TAG_REF_END_ID,
							LINE_CD,
							TAG_WI_NO,
							TAG_QR_DETAIL,
							TAG_BATCH_NO,
							TAG_NEXT_PROC 
						FROM
							$TableName 
						WHERE
							LINE_CD = 'K1M083' 
							AND ( TAG_WI_NO LIKE '%{$wi}%' AND TAG_QR_DETAIL LIKE '%{$lot_no}%' AND TAG_QR_DETAIL LIKE '%{$prod_date}%' ) 
							AND FLG_CONTROL <> '2'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_qrcode_tag_sub(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql = "SELECT tag_qr_detail from tag_print_detail_sub where tag_ref_id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table_Sub_Detail();
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
 	 				$sql = "SELECT tag_qr_detail from $TableName where tag_ref_id = '{$id}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function insert_m83_batch(){
		$log_ref = $_GET["log_ref"];
		$user_id = $_GET["user_id"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "INSERT INTO log_reprint_tag (log_system_typ,log_ref_db,log_ref_tag_id,log_created_date,log_created_by)VALUES('1','3','{$log_ref}',CURRENT_TIMESTAMP,'{$user_id}'";
		$query = $this->TBK_FA01->query($sql);


	}
	public function get_qrcode_detail_sub(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$ref_start = $_GET["ref_start"];
		$ref_end = $_GET["ref_end"];
		$dateProduct = $_GET["dateProduct"];
		$tbName1 = $this->check_sys_database_dict($dateProduct , $dateProduct , "tag_print_detail");
		if(empty($tbName1)){
			$tbName1 = "tag_print_detail";
		}
		$sql = "SELECT *  FROM $tbName1 WHERE QR_DETAIL LIKE '%K1M083%' AND ID BETWEEN '{$ref_start}' AND '{$ref_end}' AND FLG_CONTROL = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT *  FROM $TableName WHERE QR_DETAIL LIKE '%K1M083%' AND ID BETWEEN '{$ref_start}' AND '{$ref_end}' AND FLG_CONTROL = 1";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}				
				echo "0";																			
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_dummytag(){
		$line = $_GET["line"];
		$prod_date = $_GET["prod_date"];
		$wi = $_GET["wi"];
		$lot_no = $_GET["lot_no"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$tbName1 = $this->check_sys_database_dict($prod_date , $prod_date , "production_actual");
		if(empty($tbName1)){
			$tbName1 = "production_actual";
		}
		$sql = "SELECT
				 * 
				FROM
				 PRODUCTION_ACTUAL
				WHERE
				 LINE_CD = '{$line}' 
				 AND PRD_ST_DATE BETWEEN '$prod_date 00:00:00' AND '$prod_date 23:59:59'
				 AND ( WI LIKE '%{$wi}%' OR LOT_NO LIKE '%{$lot_no}%' )
				 AND ACT_QTY <> '0'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("production_actual_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT
							 * 
							FROM
							 $TableName
							WHERE
							 LINE_CD = '{$line}' 
							 AND PRD_ST_DATE BETWEEN '$prod_date 00:00:00' AND '$prod_date 23:59:59'
							 AND ( WI LIKE '%{$wi}%' OR LOT_NO LIKE '%{$lot_no}%' )
							 AND ACT_QTY <> '0'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}				
				echo "0";																			
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function find_max_box(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$part_no = $_GET["part_no"];
		$lot_no = $_GET["lot_no"];
		$line = $_GET["line"];
		$seq = $_GET["seq"];
		$sql = "SELECT ISNULL( MAX
					( log_new_box_no ), 0) AS box_no 
				FROM
					log_reprint_app 
				WHERE
					log_qr_detail LIKE '%{$part_no}%' 
					AND log_qr_detail LIKE '%{$lot_no}%' 
					AND log_qr_detail LIKE '%{$line}%' 
					AND log_qr_detail LIKE '%{$seq}%'
					AND log_new_box_no >= 900";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT ISNULL( MAX
					( log_new_box_no ), 00) AS box_no 
						FROM
							$TableName 
						WHERE
							log_qr_detail LIKE '%{$part_no}%' 
							AND log_qr_detail LIKE '%{$lot_no}%' 
							AND log_qr_detail LIKE '%{$line}%' 
							AND log_qr_detail LIKE '%{$seq}%'
							AND log_new_box_no >= 900";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_tag_for_dummy(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$lot_no = $_GET["lot_no"];
		$seq = $_GET["seq"];
		 $sql = "SELECT
				 TOP 1 *
				FROM
				 TAG_PRINT_DETAIL
				WHERE
				 WI = '{$wi}'
				 AND SEQ_NO LIKE '%{$seq}%'
				 AND QR_DETAIL LIKE '%{$lot_no}%'
				 AND flg_control <> 2
				 AND flg_control <> 9";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
						$sql = "SELECT
								 TOP 1 *
								FROM
								 $TableName
								WHERE
								 WI = '{$wi}'
								 AND SEQ_NO LIKE '%{$seq}%'
								 AND QR_DETAIL LIKE '%{$lot_no}%'
								 AND flg_control <> 2
								 AND flg_control <> 9";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_dummytag_m83(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$lot_no = $_GET["lot_no"];
		$part_no = $_GET["part_no"];
		$sql = " SELECT
				 TOP 1 *
				FROM
				 TAG_PRINT_DETAIL_MAIN
				WHERE
				 TAG_WI_NO = '{$wi}'
				 AND ( TAG_QR_DETAIL LIKE '%{$lot_no}%' AND TAG_QR_DETAIL LIKE '%{$part_no}%' )
				AND flg_control <> 2";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_main_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
						$sql = "SELECT
								 TOP 1 *
								FROM
								 $TableName 
								WHERE
								 TAG_WI_NO = '{$wi}'
								 AND ( TAG_QR_DETAIL LIKE '%{$lot_no}%' AND TAG_QR_DETAIL LIKE '%{$part_no}%' )
								AND flg_control <> 2";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function product_type(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$sql = "SELECT PRODUCT_TYP FROM sup_work_plan_supply_dev where WI = '{$wi}' AND LVL = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("sup_work_plan_supply_dev_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
						  $sql = "SELECT PRODUCT_TYP FROM $TableName where WI = '{$wi}' AND LVL = 1";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_max_boxno(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql = "SELECT max(log_new_box_no) as box_no FROM log_reprint_app where log_ref_id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
						  $sql = "SELECT max(log_new_box_no) as box_no FROM $TableName where log_ref_id = '{$id}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_box_first_scan(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$qr_code_detail = $_GET["qr_code_detail"];
		$stdate =  substr($qr_code_detail,8,8);
		$tbName1 = $this->check_sys_database_dict($stdate , $stdate , "log_reprint_app");
		if(empty($tbName1)){
			$tbName1 = "log_reprint_app";
		}
		$sql = "SELECT MAX(log_new_box_no) as box_no FROM [dbo].[log_reprint_app] WHERE [log_qr_detail] LIKE %{$qr_code_detail}%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
						  $sql = "SELECT MAX(log_new_box_no) as box_no FROM $TableName WHERE [log_qr_detail] LIKE %{$qr_code_detail}%'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_tagtype(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$line = $_GET["line"];
		$sql = "SELECT tag_type FROM [dbo].[sys_line_mst] where line_cd = '{$line}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
				  	$sql = "SELECT tag_type FROM $TableName where line_cd = '{$line}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function update_status(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql ="UPDATE log_reprint_app SET log_status = 9, log_updated_date = CURRENT_TIMESTAMP where log_id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
	}
	public function update_status_tag_print_detail(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql ="UPDATE tag_print_detail SET flg_control = 9, updated_date = CURRENT_TIMESTAMP where id = '{$id}";
		$query = $this->TBK_FA01->query($sql);
	}
	public function getMaxBox_reprintPD4(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi_tag = $_GET["wi_tag"];
		$lot_tag = $_GET["lot_tag"];
		$seq_tag = $_GET["seq_tag"];
		$sql ="Select count(box_no) as MaxBoxNo from tag_print_detail where wi = '{$wi_tag}' and TRIM( SUBSTRING(qr_detail, 59, 4 )) = '{$lot_tag}' and seq_no = '{$seq_tag}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
				  	$sql = " Select count(box_no) as MaxBoxNo from $TableName where wi = '{$wi}' and TRIM( SUBSTRING(qr_detail, 59, 4 )) = '{$log_tag}' and seq_no = '{$seq_tag}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function update_print_count(){
		$id = $_GET["id"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql ="UPDATE tag_print_detail SET print_count = (print_count + 1), updated_date = CURRENT_TIMESTAMP where id = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
	}
	public function	get_data_to_reprint_new_faPD4(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$line = $_GET["line"];
		$actaul_date = $_GET["actaul_date"];
		$lot_no = $_GET["lot_no"];
		$wi = $_GET["wi"];
		$boxNo = $_GET["boxNo"];
		$tbName1 = $this->check_sys_database_dict($actaul_date , $actaul_date , "tag_print_detail");
		$tbName2 = $this->check_sys_database_dict($actaul_date , $actaul_date , "log_reprint_app");
		if(empty($tbName1)){
			$tbName1 = "tag_print_detail";
		}
		if(empty($tbName2)){
			$tbName2 = "log_reprint_app";
		}
		$sql ="SELECT
			A.FA_ID AS FA_ID,
			A.RE_ID AS RE_ID,
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
				$tbName1 
			WHERE
		        box_no =  {$boxNo} AND
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
						$tbName1 
					WHERE
		                box_no =  {$boxNo} AND
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
			RE_ID ASC";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail");
			$Data_Name_Table2 = $this->Get_Table("log_reprint_app_bk");
			$Table2= $Data_Name_Table2[0]["TABLE_NAME"];
			if(empty($Table2)){
				$Table2 = "log_reprint_app";
			}
			if(!empty($Data_Name_Table)){
				$rs = "0";
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
				 $sql ="SELECT
				A.FA_ID AS FA_ID,
				A.RE_ID AS RE_ID,
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
					$TableName 
				WHERE
			        box_no =  {$boxNo} AND
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
							$TableName 
						WHERE
			                box_no =  {$boxNo} AND
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
							$Table2 
						WHERE
							LOG_REF_DB = '2' 
							AND LOG_STATUS = '1' 
						) B ON A.ID = B.ID 
					) 
				) AS A 
			ORDER BY
				FA_ID,
				RE_ID ASC";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																						
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function check_user(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$emp_id = $_GET["emp_id"];
		$sql = "select sys_reprint_app_users.id  from sys_reprint_app_users where emp_cd = '{$emp_id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public	function check_emp_cd(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql = "select status, id_perm_g from sys_reprint_app_users where emp_cd = '{$id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function check_status_perm(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$sql = " SELECT id_perm_g,status FROM sys_reprint_app_permission WHERE id_perm_g = N'{$id}' AND (status = '1')";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function check_status_menu(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id_user = $_GET["id_user"];
		$sql = "SELECT mm.status FROM sys_reprint_app_users AS users INNER JOIN sys_reprint_app_permission_group AS pg ON pg.id_perm_g = users.id_perm_g INNER JOIN sys_reprint_app_permission AS pp ON pp.id_perm_g = pg.id_perm_g INNER JOIN sys_reprint_app_menu AS mm ON mm.id_menu = pp.id_menu AND users.emp_cd = '{$id_user}' and mm.status&users.status&pp.status&pg.status = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function GET_DATA_USER(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id_user = $_GET["id_user"];
		$sql = "SELECT mm.id_menu,mm.picture_name,mm.picture_path,mm.status FROM sys_reprint_app_users AS users INNER JOIN sys_reprint_app_permission_group AS pg ON pg.id_perm_g = users.id_perm_g INNER JOIN sys_reprint_app_permission AS pp ON pp.id_perm_g = pg.id_perm_g INNER JOIN sys_reprint_app_menu AS mm ON mm.id_menu = pp.id_menu AND users.emp_cd = '{$id_user}' and mm.status&users.status&pp.status&pg.status = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function getpd(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "SELECT dep_cd from sys_line_mst where enable = 1 and chk_sys_flg = 1";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function getline(){
		$pd = $_GET["pd"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "SELECT line_cd from sys_line_mst where dep_cd = '{$pd}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function getpd_dummytag(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = " SELECT
                         LINE_ID,
                         DEP_CD,
                         LINE_CD,
                         LINE_NAME,
                         TAG_TYPE
                        FROM
                         SYS_LINE_MST 
                        WHERE
                         ENABLE = '1' AND CHK_SYS_FLG = '1'
                        ORDER BY DEP_CD,LINE_CD ASC";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function GET_ID_MENU(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$_GET["wi"];
		$sql = " SELECT  mm.id_menu from sys_reprint_app_menu AS mm where picture_name = '{$wi}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function get_pd(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
	 	$sql = " SELECT PD FROM sup_work_plan_supply_dev WHERE WI LIKE '%{$wi}%' AND LVL LIKE '%1%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("sup_work_plan_supply_dev_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT PD FROM $TableName WHERE WI LIKE '%{$wi}%' AND LVL LIKE '%1%'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_id_tag_print_main(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$qr_code = $_GET["qr_code"];
		$stdate =  substr($qr_code,8,8);
		$tbName1 = $this->check_sys_database_dict($stdate , $stdate , "tag_qr_detail_main");
		if(empty($tbName1)){
			$tbName1 = "tag_qr_detail_main";
		}
	 	$sql = "select tag_id from tag_print_detail_main where tag_qr_detail = '{$qr_code}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_main_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "select tag_id from $TableName where tag_qr_detail = '{$qr_code}'";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function check_tag_type(){
		$line_cd = $_GET["line_cd"];
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$sql = "SELECT tag_type FROM sys_line_mst where line_cd = '{$line_cd}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo "0";
		}else{
			echo json_encode($get);
		}
	}
	public function get_data_tag_log_reprint(){
		$line = $_GET["line"];
		$year = $_GET["year"];
		$part_no = $_GET["part_no"];
		$lot_no = $_GET["lot_no"];
		$sql  = "SELECT * FROM [dbo].[log_reprint_app] WHERE log_status <> 9 and ([log_qr_detail] LIKE'%{$line}%' AND [log_qr_detail] LIKE '%{$year}%' AND [log_qr_detail] LIKE '%{$part_no}%' AND [log_qr_detail] LIKE '%{$lot_no}%')";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("log_reprint_app_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT * FROM [dbo].[$TableName] WHERE log_status <> 9 and ([log_qr_detail] LIKE'%{$line}%' AND [log_qr_detail] LIKE '%{$year}%' AND [log_qr_detail] LIKE '%{$part_no}%' AND [log_qr_detail] LIKE '%{$lot_no}%')";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function	insert_new_fa_print(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$ref_db = $_GET["ref_db"];
		$log_ref = $_GET["log_ref"];
		$userID = $_GET["userID"];
		$sql = "INSERT INTO log_reprint_tag (log_system_typ,log_ref_db,log_ref_tag_id,log_created_date,log_created_by)VALUES(1,'{$ref_db}','{$log_ref}',CURRENT_TIMESTAMP,'{$userID}'";
		$query = $this->TBK_FA01->query($sql);
	}
	public function insert_m83_sub(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$log_ref = $_GET["log_ref"];
		$user_id = $_GET["user_id"];
		$sql = "INSERT INTO log_reprint_tag (log_system_typ,log_ref_db,log_ref_tag_id,log_created_date,log_created_by)VALUES(1,2,'{$log_ref}',CURRENT_TIMESTAMP, '{$user_id}')";
		$query = $this->TBK_FA01->query($sql);
	}
	public function get_part_no(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$wi = $_GET["wi"];
		$sql = "SELECT ITEM_CD FROM [dbo].[sup_work_plan_supply_dev] WHERE [WI] LIKE '%{$wi}%' AND [LVL] LIKE '%1%'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("sup_work_plan_supply_dev");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT ITEM_CD FROM [dbo].[$TableName] WHERE [WI] LIKE '%{$wi}%' AND [LVL] LIKE '%1%')";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function get_shift(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$ref_id = $_GET["ref_id"];
		$sql  = "SELECT SHIFT FROM [dbo].[tag_print_detail] WHERE [id] = '{$ref_id}'";
		$query = $this->TBK_FA01->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			$Data_Name_Table = $this->Get_Table("tag_print_detail_bk");
			if(!empty($Data_Name_Table)){
				foreach ($Data_Name_Table as $key => $value) {
					$TableName = $value["TABLE_NAME"];
					$sql = "SELECT SHIFT FROM [dbo].[$TableName] WHERE [id] = '{$ref_id}')";
					$query = $this->TBK_FA01->query($sql);
					$get = $query->result_array();
					if(!empty($get)){
						echo json_encode($get);
						return ;
					}else{
						// echo "0";
					}
				}
				echo "0";																							
			}else{
				echo "0";
			}
		}else{
			echo json_encode($get);
		}
	}
	public function insert_dummy_tag(){
		$this->TBK_FA01 = $this->load->database('tbkkfa01_db', true);
		$id = $_GET["id"];
		$cur_qty = $_GET["cur_qty"];
		$new_qty = $_GET["new_qty"];
		$cur_box = $_GET["cur_box"];
		$new_box = $_GET["new_box"];
		$next_process = $_GET["next_process"];
		$qr_detail = $_GET["qr_detail"];
		$user_id = $_GET["user_id"];
		  $sql = "INSERT INTO log_reprint_app (id_menu,log_ref_db,log_ref_id,log_cur_qty,log_new_qty,log_cur_box_no,log_new_box_no,log_cur_next_proc,log_new_next_proc,log_qr_detail,log_status,log_created_date,log_created_by,log_updated_date,log_updated_by )VALUES (5,2,'{$id}','{$cur_qty}','{$new_qty}','{$cur_box}','{$new_box}','{$next_process}','{$next_process}','{$qr_detail}',1,CURRENT_TIMESTAMP,'{$user_id} ',CURRENT_TIMESTAMP,'{$user_id}')";
		$query = $this->TBK_FA01->query($sql);

	}
	public function insert_dummy_tag2(){
		$id = $_GET["id"];
		$cur_qty = $_GET["cur_qty"];
		$new_qty = $_GET["new_qty"];
		$cur_box = $_GET["cur_box"];
		$new_box = $_GET["new_box"];
		$next_process = $_GET["next_process"];
		$qr_detail = $_GET["qr_detail"];
		$user_id = $_GET["user_id"];
		$sql = "INSERT INTO log_reprint_app (id_menu,log_ref_db,log_ref_id,log_cur_qty,log_new_qty,log_cur_box_no,log_new_box_no,log_cur_next_proc,log_new_next_proc,log_qr_detail,log_status,log_created_date,log_created_by,log_updated_date,log_updated_by )VALUES (5,3,'{$id}','{$cur_qty}','{$new_qty}','{$cur_box}','{$new_box}','{$next_process}','{$next_process}','{$qr_detail}',1,CURRENT_TIMESTAMP,'{$user_id} ',CURRENT_TIMESTAMP,'{$user_id}')";
		$query = $this->TBK_FA01->query($sql);
	}
	// public function insert_scan_tag(){
	// 	$id = $_GET["id"];
	// 	$cur_qty = $_GET["cur_qty"];
	// 	$new_qty = $_GET["new_qty"];
	// 	$cur_box = $_GET["cur_box"];
	// 	$new_box = $_GET["new_box"];
	// 	$next_process = $_GET["next_process"];
	// 	$qr_detail = $_GET["qr_detail"];
	// 	$user_id = $_GET["user_id"];
	// 	$sql = "INSERT INTO log_reprint_app (id_menu,log_ref_db,log_ref_id,log_cur_qty,log_new_qty,log_cur_box_no,log_new_box_no,log_cur_next_proc,log_new_next_proc,log_qr_detail,log_status,log_created_date,log_created_by,log_updated_date,log_updated_by )VALUES (5,3,'{$id}','{$cur_qty}','{$new_qty}','{$cur_box}','{$new_box}','{$next_process}','{$next_process}','{$qr_detail}',1,CURRENT_TIMESTAMP,'{$user_id} ',CURRENT_TIMESTAMP,'{$user_id}'";
	// 	$query = $this->TBK_FA01->query($sql);
	// }
}
?>