<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:26:13 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassQuizAnswers extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
