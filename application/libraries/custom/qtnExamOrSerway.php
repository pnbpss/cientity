<?php  
/** create by application/controllers/createEntityClassLibrary , since 16:42:36 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class qtnExamOrSerway extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
