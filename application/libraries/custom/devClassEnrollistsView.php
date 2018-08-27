<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:10:55 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEnrollistsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'employeeFullName','descriptions'=>'employeeFullName||employeeFullName'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'corpName','descriptions'=>'corpName||corpName'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'officeName','descriptions'=>'officeName||officeName'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'acknowledged','descriptions'=>'acknowledged||acknowledged'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'refused','descriptions'=>'refused||refused'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'locationCode','descriptions'=>'locationCode||locationCode'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'classStartDate','descriptions'=>'classStartDate||classStartDate'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
			[ 'tableName'=>'hds_devClassEnrollistsView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
	 ]; 
	 unset($this->columnDescriptions[13]); 
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
