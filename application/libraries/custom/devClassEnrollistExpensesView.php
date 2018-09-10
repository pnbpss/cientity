<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:41:46 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEnrollistExpensesView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'titleName','descriptions'=>'titleName||titleName'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'employeeFullName','descriptions'=>'employeeFullName||employeeFullName'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'officeName','descriptions'=>'officeName||officeName'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'positionName','descriptions'=>'positionName||positionName'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'acknowledged','descriptions'=>'acknowledged||acknowledged'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'refused','descriptions'=>'refused||refused'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'locationCode','descriptions'=>'locationCode||locationCode'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'classStartDate','descriptions'=>'classStartDate||classStartDate'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'locationDescriptions','descriptions'=>'locationDescriptions||locationDescriptions'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'subjectCodeAndName','descriptions'=>'subjectCodeAndName||subjectCodeAndName'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'comments','descriptions'=>'comments||comments'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'empAndClass','descriptions'=>'empAndClass||empAndClass'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'amount','descriptions'=>'amount||amount'],   
			[ 'tableName'=>'hds_devClassEnrollistExpensesView','columnName'=>'amount','descriptions'=>'amount||amount'],   
	 ]; 
	 unset($this->columnDescriptions[17]); 
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
