<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:16:59 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysUserGroups extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
