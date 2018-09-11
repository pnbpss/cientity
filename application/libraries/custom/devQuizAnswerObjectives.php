<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:50:29 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizAnswerObjectives extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
