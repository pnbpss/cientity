<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:33:01 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassQuizChecks extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
