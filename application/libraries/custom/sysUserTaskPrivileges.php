<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:14:09 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysUserTaskPrivileges extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
