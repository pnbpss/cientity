<?php  
/** create by application/controllers/createEntityClassLibrary , since 16:42:41 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class qtnQuestions extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
