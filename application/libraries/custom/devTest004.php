<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:42:18 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devTest004 extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
