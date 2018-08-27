<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:03 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class gntPrivileges extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
