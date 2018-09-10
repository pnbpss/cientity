<?php  
/** create by application/controllers/createEntityClassLibrary , since 06:08:42 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEvaluatorsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'employeeFullName','descriptions'=>'employeeFullName||employeeFullName'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'comments','descriptions'=>'comments||comments'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'classStartDate','descriptions'=>'classStartDate||classStartDate'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'locationCode','descriptions'=>'locationCode||locationCode'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
			[ 'tableName'=>'hds_devClassEvaluatorsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
	 ]; 
	 unset($this->columnDescriptions[9]); 
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
