<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:05 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class gntTasks extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
