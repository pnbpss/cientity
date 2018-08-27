<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:04 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class gntTaskGroups extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
