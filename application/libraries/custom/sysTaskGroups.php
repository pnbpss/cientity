<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:14:08 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysTaskGroups extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
