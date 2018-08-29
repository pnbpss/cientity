<?php  
/** */ 
class repExpenseReports{	 	
	public function __construct(){		
		$this->CI =& get_instance();
		$this->CI->load->database();		
	}
	function createFilterRow(){
		return "";
	}
	function createSearchResultZone(){
		return "";
	}
} 
