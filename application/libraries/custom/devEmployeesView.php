<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:59:38 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devEmployeesView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'titleName','descriptions'=>'titleName||titleName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'firstName','descriptions'=>'firstName||firstName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'lastName','descriptions'=>'lastName||lastName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'nick','descriptions'=>'nick||nick'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'gender','descriptions'=>'gender||gender'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'birthdate','descriptions'=>'birthdate||birthdate'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'workStart','descriptions'=>'workStart||workStart'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'workEnd','descriptions'=>'workEnd||workEnd'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'IDNo','descriptions'=>'IDNo||IDNo'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'officeName','descriptions'=>'officeName||officeName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'uname','descriptions'=>'uname||uname'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'em_status','descriptions'=>'em_status||em_status'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'email','descriptions'=>'email||email'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'mobile','descriptions'=>'mobile||mobile'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'IDNoAndFullName','descriptions'=>'IDNoAndFullName||IDNoAndFullName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'FLName','descriptions'=>'FLName||FLName'],   
			[ 'tableName'=>'hds_devEmployeesView','columnName'=>'FLName','descriptions'=>'FLName||FLName'],   
	 ]; 
	 unset($this->columnDescriptions[19]); 
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
