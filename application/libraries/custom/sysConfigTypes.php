<?php  
/** create by application/controllers/createEntityClassLibrary , since 16:31:52 */ 
require_once(APPPATH.'libraries\entity.php');  
class sysConfigTypes extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
