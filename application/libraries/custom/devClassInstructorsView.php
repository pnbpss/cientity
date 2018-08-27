<?php  
/** create by application/controllers/createEntityClassLibrary , since 16:09:49 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassInstructorsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'employeeFullName','descriptions'=>'employeeFullName||employeeFullName'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'percentLoad','descriptions'=>'percentLoad||percentLoad'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'comments','descriptions'=>'comments||comments'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'classStartDate','descriptions'=>'classStartDate||classStartDate'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'locationCode','descriptions'=>'locationCode||locationCode'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
			[ 'tableName'=>'hds_devClassInstructorsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
	 ]; 
	 unset($this->columnDescriptions[10]); 
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
