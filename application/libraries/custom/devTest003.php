<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:41:04 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devTest003 extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
