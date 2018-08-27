<?php  
/** create by application/controllers/createEntityClassLibrary , since 03:53:43 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassExtInstructorsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'percentLoad','descriptions'=>'percentLoad||percentLoad'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'comments','descriptions'=>'comments||comments'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'IDNo','descriptions'=>'IDNo||IDNo'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'fullName','descriptions'=>'fullName||fullName'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassExtInstructorsView','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
	 ]; 
	 unset($this->columnDescriptions[7]); 
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
