<?php  
/** create by application/controllers/createEntityClassLibrary , since 04:18:23 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassExamPapersForObjectiveAns extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'quizId','descriptions'=>'quizId||quizId'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'score','descriptions'=>'score||score'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'quizTypeId','descriptions'=>'quizTypeId||quizTypeId'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'questions','descriptions'=>'questions||questions'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'classAndQuestion','descriptions'=>'classAndQuestion||classAndQuestion'],   
			[ 'tableName'=>'hds_devClassExamPapersForObjectiveAns','columnName'=>'classAndQuestion','descriptions'=>'classAndQuestion||classAndQuestion'],   
	 ]; 
	 unset($this->columnDescriptions[8]); 
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
