<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:59:37 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassBudgetsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'startDate','descriptions'=>'startDate||startDate'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'classDescription','descriptions'=>'classDescription||classDescription'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'expenseTypeName','descriptions'=>'expenseTypeName||expenseTypeName'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'amount','descriptions'=>'amount||amount'],   
			[ 'tableName'=>'hds_devClassBudgetsView','columnName'=>'amount','descriptions'=>'amount||amount'],   
	 ]; 
	 unset($this->columnDescriptions[6]); 
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
