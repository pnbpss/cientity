<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:42 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizTypes extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 