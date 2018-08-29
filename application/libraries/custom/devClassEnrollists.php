<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:25 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEnrollists extends entity{	 
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
			//var_dump($session);
			$dbPrefix = $this->_returnDbPrefix();
			return " and {$dbPrefix}devClassEnrollists.employeeId in ('{$session['employeeId']}') ";
		}		
	}
} 
