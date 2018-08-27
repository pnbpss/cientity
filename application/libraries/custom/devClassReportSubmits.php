<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:21:56 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassReportSubmits extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
