<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:26 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClasses extends entity{	 
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
