<?php  
/** create by application/controllers/createEntityClassLibrary , since 04:18:25 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizChoicesForObjectiveAnswer extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'quizId','descriptions'=>'quizId||quizId'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'ordinalId','descriptions'=>'ordinalId||ordinalId'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'answerOption','descriptions'=>'answerOption||answerOption'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'correctId','descriptions'=>'correctId||correctId'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'questions','descriptions'=>'questions||questions'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'choice','descriptions'=>'choice||choice'],   
			[ 'tableName'=>'hds_devQuizChoicesForObjectiveAnswer','columnName'=>'choice','descriptions'=>'choice||choice'],   
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
