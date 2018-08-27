<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:13:58 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devSubDistricts extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
