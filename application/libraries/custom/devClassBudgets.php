<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:24 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassBudgets extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	function additionalWhereInFilterRow(){
		$session = $this->_retSessionData();
		if($session['userGroupId']===7){ //user group is users			
			//force user in users group unable to list class expense
			return " and 1=0 ";
		}		
	}
} 
