<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:41 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizGroups extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
