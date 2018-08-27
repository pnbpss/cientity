<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:13:59 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devSubjectCourseView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devSubjectCourseView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devSubjectCourseView','columnName'=>'courseAndSubject','descriptions'=>'courseAndSubject||courseAndSubject'],   
			[ 'tableName'=>'hds_devSubjectCourseView','columnName'=>'courseAndSubject','descriptions'=>'courseAndSubject||courseAndSubject'],   
	 ]; 
	 unset($this->columnDescriptions[2]); 
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
