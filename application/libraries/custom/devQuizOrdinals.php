<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:13:41 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizOrdinals extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
