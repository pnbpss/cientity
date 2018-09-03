<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:31 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassInstructors extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	public function doDbTransactions($sql) {		
		$newSql = $sql." ".$this->devClassInstructors_verifyPercentLoadNotExceed();				
		return parent::doDbTransactions($newSql);
	}
	private function devClassInstructors_verifyPercentLoadNotExceed(){
		$tableName = $this->getTableName();
		if(isset($this->infoForAdditionalValidateSubEntity['idValue'])){
			$idValue=$this->infoForAdditionalValidateSubEntity['idValue'];
			$addsql =" 
				declare @classId int; set @classId = (select classId from {$tableName} where id='{$idValue}'); if (select sum(percentLoad) from {$tableName} where 1=1 and classId=@classId) > 100 begin set @errorMessage = 'Sum of percent load must not exceeded 100.'; goto Skipper; end
			";
		}elseif(isset($this->infoForAdditionalValidate['addEditMainEntity'])){
			list($columnsWithOrdered)=$this->infoForAdditionalValidate;
			//var_dump($columnsWithOrdered);
			$index = 0;
			foreach(array_keys($columnsWithOrdered) as $fieldName)	{
				if($fieldName=='classId'){
					$thisFieldVal = str_replace("'","''",$this->_REQUESTE[''.$index]);
				}
				$index++;
			}
			$addsql = "; if (select sum(percentLoad) from {$tableName} where 1=1 and classId='{$thisFieldVal}') > 100 begin set @errorMessage = 'Sum of percent load must not exceeded 100.'; goto Skipper; end";
		}else{ 
			//if want to validate other operation such as delete code goes here
			$addsql = "";
		}
		
		//echo $addsql;
		return $addsql;
	}
} 
