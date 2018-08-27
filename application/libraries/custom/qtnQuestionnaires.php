<?php  
/** create by application/controllers/createEntityClassLibrary , since 16:42:40 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class qtnQuestionnaires extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
