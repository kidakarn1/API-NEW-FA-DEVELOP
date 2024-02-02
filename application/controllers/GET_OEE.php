<?php
class GET_OEE extends CI_Controller
{
	public function GET_TARGET(){
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$Shift = $_GET["shift"];
		$wi = $_GET["WI"];
		$actual = $_GET["actual"];
		$this->test_new_fa02 = $this->load->database('tbkkfa01_db', true);
		$sql = " Select * from sup_work_plan_supply_dev where WI = '{$wi}' and LVL = '1'";
		$query = $this->test_new_fa02->query($sql);
		$get = $query->result_array();
		if(!empty($get)){
			$Target = $this->Manage_Target($Shift  , $get , $actual);
			echo $Target;
		}
	}
	public function Manage_Target($Shift , $get , $actual){
		if($Shift == "P" || $Shift == "Q"){
			$workTime = 12*60;
			$Break = 90;
			$ProductionTime = $workTime - $Break;
		}elseif ($Shift == "A" || $Shift == "B" || $Shift == "S") {
			$workTime = 9*60;
			$Break = 60;
			$ProductionTime = $workTime - $Break;
		}elseif ($Shift == "M" || $Shift == "N"){
			$workTime = 3*60;
			$Break = 10;
			$ProductionTime = $workTime - $Break;
		}else{
			return 0;
		}
		if(is_null($get[0]["CT"])){
			return 0;
		}else{
			$Target = $ProductionTime*$get[0]["CT"];
			$Plan = $get[0]["QTY"];
			if($Target > $Plan){
				if($Plan - $actual > 0){
					return intval($Plan) - intval($actual);
				}else{
					return 0;
				}
			}else{
				$Target_remain = $Plan - $actual;
				if($Target > $Target_remain){
					return $Target_remain;
				}else{
					return intval($Target);
				}
			}
		}
	}
}
?>