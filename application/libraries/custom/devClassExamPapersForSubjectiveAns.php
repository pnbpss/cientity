<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:52:33 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassExamPapersForSubjectiveAns extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'quizId','descriptions'=>'quizId||quizId'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'classId','descriptions'=>'classId||classId'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'score','descriptions'=>'score||score'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'classDescriptions','descriptions'=>'classDescriptions||classDescriptions'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'quizTypeId','descriptions'=>'quizTypeId||quizTypeId'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'questions','descriptions'=>'questions||questions'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'classAndQuestion','descriptions'=>'classAndQuestion||classAndQuestion'],   
			[ 'tableName'=>'hds_devClassExamPapersForSubjectiveAns','columnName'=>'classAndQuestion','descriptions'=>'classAndQuestion||classAndQuestion'],   
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
