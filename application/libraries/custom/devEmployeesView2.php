<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:41:49 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devEmployeesView2 extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'titleName','descriptions'=>'titleName||titleName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'firstName','descriptions'=>'firstName||firstName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'lastName','descriptions'=>'lastName||lastName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'nick','descriptions'=>'nick||nick'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'gender','descriptions'=>'gender||gender'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'birthdate','descriptions'=>'birthdate||birthdate'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'workStart','descriptions'=>'workStart||workStart'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'workEnd','descriptions'=>'workEnd||workEnd'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'IDNo','descriptions'=>'IDNo||IDNo'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'officeName','descriptions'=>'officeName||officeName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'em_status','descriptions'=>'em_status||em_status'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'email','descriptions'=>'email||email'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'mobile','descriptions'=>'mobile||mobile'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'IDNoAndFullName','descriptions'=>'IDNoAndFullName||IDNoAndFullName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'FLName','descriptions'=>'FLName||FLName'],   
			[ 'tableName'=>'hds_devEmployeesView2','columnName'=>'FLName','descriptions'=>'FLName||FLName'],   
	 ]; 
	 unset($this->columnDescriptions[18]); 
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
