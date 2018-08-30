<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:41:03 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devTest002 extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
