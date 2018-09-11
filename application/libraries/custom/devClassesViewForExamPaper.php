<?php  
/** create by application/controllers/createEntityClassLibrary , since 10:40:19 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassesViewForExamPaper extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'scId','descriptions'=>'scId||scId'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'startDate','descriptions'=>'startDate||startDate'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'locationId','descriptions'=>'locationId||locationId'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'statusId','descriptions'=>'statusId||statusId'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'createdBy','descriptions'=>'createdBy||createdBy'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'createdDate','descriptions'=>'createdDate||createdDate'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'capacity','descriptions'=>'capacity||capacity'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'descriptions','descriptions'=>'descriptions||descriptions'],   
			[ 'tableName'=>'hds_devClassesViewForExamPaper','columnName'=>'descriptions','descriptions'=>'descriptions||descriptions'],   
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
