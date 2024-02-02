<?php
class ApiCheckQTY extends CI_Controller
{
	public function index(){
	
	 }
	public function CheckWorking(){
		$this->tbkkfa01_db = $this->load->database('tbkkfa01_db', true);
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$item_cd = $_GET["item_cd"];
		date_default_timezone_set('Asia/bangkok');
		$sdn = date('Y-m-d');
		$edn = date('Y-m-d');
		$tn = date("H:i:s");
		if($tn >= "00:00:00" and $tn <= "08:00:00"){
			$sdn = date("Y-m-d",strtotime("-1 days",strtotime($sdn)));
		}else{
			$edn = date("Y-m-d",strtotime("+1 days",strtotime($edn)));
		}
		$st ="";
		$ed = $tn ;
		if($tn >= "08:00:00" and $tn <= "17:15:00"){
			$st = "08:00:00";
		}else{
			$st = "17:00:00";
		}
		   $sqlCheckAct = "SELECT COALESCE(SUM(act_qty), 0) AS QtyAct
		FROM
			production_actual 
		WHERE
			prd_st_date BETWEEN '$sdn $st' and  '$edn $ed' and 
			item_cd = '{$item_cd}' and 
			transfer_flg ='9'
		 ";
		 // echo"<br><br><br><br>";
		$queryCheckAct = $this->tbkkfa01_db->query($sqlCheckAct);
		$getSumAct = $queryCheckAct->result_array();
		$tranferQty = $getSumAct[0]["QtyAct"];
	  	    $sqlSum = "SELECT
					production_actual_detail.ITEM_CD AS ItemCd,
					SUM ( production_actual_detail.qty)  - $tranferQty AS Total 
				FROM
					production_actual_detail,
					production_working_info 
				WHERE
					production_actual_detail.ITEM_CD = '{$item_cd}' 
					AND ( production_actual_detail.end_time BETWEEN '$sdn $st' AND '$edn $ed' ) 
					AND production_actual_detail.pwi_id = production_working_info.pwi_id 
				GROUP BY
					production_actual_detail.ITEM_CD";
		$querySum = $this->tbkkfa01_db->query($sqlSum);
		$getSum = $querySum->result_array();
		if(empty($getSum)){
			echo "0";
		}else{
			echo json_encode($getSum);
		}			
	}
}
?>