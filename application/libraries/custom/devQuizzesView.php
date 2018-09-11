<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:11:22 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizzesView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'groupId','descriptions'=>'groupId||groupId'],   
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'quizTypeId','descriptions'=>'quizTypeId||quizTypeId'],   
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'questions','descriptions'=>'questions||questions'],   
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'Group_Questions','descriptions'=>'Group_Questions||Group_Questions'],   
			[ 'tableName'=>'hds_devQuizzesView','columnName'=>'Group_Questions','descriptions'=>'Group_Questions||Group_Questions'],   
	 ]; 
	 unset($this->columnDescriptions[5]); 
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
