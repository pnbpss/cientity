<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:45 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devSubjects extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	public function _getUserSessionValue($key){
		$session = $this->_retSessionData();
		return $session[$key];
	}
} 
