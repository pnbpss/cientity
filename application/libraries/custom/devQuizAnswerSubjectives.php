<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:50:30 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizAnswerSubjectives extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
