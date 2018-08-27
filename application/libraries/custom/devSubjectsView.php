<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:01 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devSubjectsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'code','descriptions'=>'code||code'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'name','descriptions'=>'name||name'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'classDuration','descriptions'=>'classDuration||classDuration'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'shopDuration','descriptions'=>'shopDuration||shopDuration'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'preSubjectId','descriptions'=>'preSubjectId||preSubjectId'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'closedId','descriptions'=>'closedId||closedId'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'createdDate','descriptions'=>'createdDate||createdDate'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'createdBy','descriptions'=>'createdBy||createdBy'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'codeAndName','descriptions'=>'codeAndName||codeAndName'],   
			[ 'tableName'=>'hds_devSubjectsView','columnName'=>'codeAndName','descriptions'=>'codeAndName||codeAndName'],   
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
