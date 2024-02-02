<?php
// use CodeIgniter\Database\Exceptions\DatabaseException;
class Api_check_data extends CI_Controller
{
	public function index(){
	
	 }
	public function chk_spec_line(){
		$this->tbkkfa01_db = $this->load->database('tbkkfa01_db', true);
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$line_cd = $_GET["line_cd"];
		$sql = "select *  from sys_line_mst where chk_spec_line = 1 and line_cd = '$line_cd'";
		$query = $this->tbkkfa01_db->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo  "0";
		}else{
			echo "1";
		}
	}
	public function check_line_reprint(){
		$this->tbkkfa01_db = $this->load->database('tbkkfa01_db', true);
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$line_cd = $_GET["line_cd"];
		$sql = "select  *   from sys_line_mst where  tag_issue_flg = '1' and line_cd = '$line_cd'";
		$query = $this->tbkkfa01_db->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo  "0";
		}else{
			echo "1";
		}
	}
	public function check_format_tag(){
		$this->tbkkfa01_db = $this->load->database('tbkkfa01_db', true);
		parse_str($_SERVER['QUERY_STRING'], $_GET);
		$line_cd = $_GET["line_cd"];
		$sql = "select  *  from sys_line_mst where  tag_type = '2' and tag_issue_flg = '2'  and line_cd = '$line_cd'";
		$query = $this->tbkkfa01_db->query($sql);
		$get = $query->result_array();
		if(empty($get)){
			echo  "0";
		}else{
			echo "1";
		}
	}
		public function check_ConDB(){
		 try {
        	$this->tbkkfa01_db = $this->load->database('tbkkfa01_db', true);
	
        	// Check if the connection is successful
        	if ($this->tbkkfa01_db === false) {
        	    // Unable to connect to the database
        	    echo "Connect database fail";
        	} else {
        	    // Connection successful, proceed with the SQL query
        	    $sql = "SELECT * FROM sys_line_mst";
        	    $query = $this->tbkkfa01_db->query($sql);
	
        	    // Check if the query was successful
        	 	echo "Connect database success";
        	}
    	} catch (DatabaseException $e) {
    	    // Handle any exceptions that may occur during the connection attempt or query execution
    	    echo "0";
    	    log_message('error', 'Database Exception: ' . $e->getMessage());
    	}
	}
}
?>