<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:33 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devEmployees extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devEmployees','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'titleName','descriptions'=>'titleName||titleName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'firstName','descriptions'=>'firstName||firstName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'lastName','descriptions'=>'lastName||lastName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'nick','descriptions'=>'nick||nick'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'gender','descriptions'=>'gender||gender'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'birthdate','descriptions'=>'birthdate||birthdate'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'workStart','descriptions'=>'workStart||workStart'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'workEnd','descriptions'=>'workEnd||workEnd'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'IDNo','descriptions'=>'IDNo||IDNo'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'corpName','descriptions'=>'corpName||corpName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'officeName','descriptions'=>'officeName||officeName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'uname','descriptions'=>'uname||uname'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'hrmCorpId','descriptions'=>'hrmCorpId||hrmCorpId'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'em_status','descriptions'=>'em_status||em_status'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'period_no','descriptions'=>'period_no||period_no'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'ssoBranchId','descriptions'=>'ssoBranchId||ssoBranchId'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'email','descriptions'=>'email||email'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'mobile','descriptions'=>'mobile||mobile'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'stdPositionId','descriptions'=>'stdPositionId||stdPositionId'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'IDNoAndFullName','descriptions'=>'รหัสปปช.(ชื่อ-สกุล)||IDNoAndFullName'],   
			[ 'tableName'=>'hds_devEmployees','columnName'=>'FLName','descriptions'=>'ชื่อ-สกุลพนักงาน||FLName'],   
	 ]; 
	 unset($this->columnDescriptions[22]); 
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
