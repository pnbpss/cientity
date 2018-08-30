<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:41:02 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devTest001 extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
