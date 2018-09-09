<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:28 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devEmployees extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
