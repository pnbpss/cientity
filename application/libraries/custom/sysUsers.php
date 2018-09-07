<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:17:00 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysUsers extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
