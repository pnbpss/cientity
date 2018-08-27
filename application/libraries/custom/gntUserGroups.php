<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:07 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class gntUserGroups extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_gntUserGroups','columnName'=>'groupuser','descriptions'=>'groupuser||groupuser'],   
			[ 'tableName'=>'hds_gntUserGroups','columnName'=>'groupname','descriptions'=>'groupname||groupname'],   
			[ 'tableName'=>'hds_gntUserGroups','columnName'=>'groupstatus','descriptions'=>'groupstatus||groupstatus'],   
			[ 'tableName'=>'hds_gntUserGroups','columnName'=>'groupstatus','descriptions'=>'groupstatus||groupstatus'],   
	 ]; 
	 unset($this->columnDescriptions[3]); 
	 list($this->columnDescriptionsColumnIndexed,$this->revisedColumnDescriptions) = $this->reviseColumnDescriptions($this->columnDescriptions); 
	 } 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
