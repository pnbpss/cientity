<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:14:09 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class gntUsers extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_gntUsers','columnName'=>'employeeCode','descriptions'=>'employeeCode||employeeCode'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'PW','descriptions'=>'PW||PW'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'IDNo','descriptions'=>'IDNo||IDNo'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'Tname','descriptions'=>'Tname||Tname'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'Fname','descriptions'=>'Fname||Fname'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'Lname','descriptions'=>'Lname||Lname'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'Nickname','descriptions'=>'Nickname||Nickname'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'gender','descriptions'=>'gender||gender'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'hrmCorpId','descriptions'=>'hrmCorpId||hrmCorpId'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'empStatus','descriptions'=>'empStatus||empStatus'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'groupuser','descriptions'=>'groupuser||groupuser'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'updateBy','descriptions'=>'updateBy||updateBy'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'lastupdate','descriptions'=>'lastupdate||lastupdate'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'firstTime','descriptions'=>'firstTime||firstTime'],   
			[ 'tableName'=>'hds_gntUsers','columnName'=>'firstTime','descriptions'=>'firstTime||firstTime'],   
	 ]; 
	 unset($this->columnDescriptions[14]); 
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
