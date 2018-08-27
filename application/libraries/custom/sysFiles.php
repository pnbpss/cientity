<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:16:40 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysFiles extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
