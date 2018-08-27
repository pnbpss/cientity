<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:30 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassExtInstructors extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	public function doDbTransactions($sql) {		
		$newSql = $sql." ".$this->devClassExtInstructors_verifyPercentLoadNotExceed();				
		return parent::doDbTransactions($newSql);
	}
	private function devClassExtInstructors_verifyPercentLoadNotExceed(){		
		$tableName = $this->getTableName();
		if(isset($this->infoForAdditionalValidateSubEntity['idValue'])){
			$idValue=$this->infoForAdditionalValidateSubEntity['idValue'];
			$addsql =" 
				declare @classId int; set @classId = (select classId from {$tableName} where id='{$idValue}'); if (select sum(percentLoad) from {$tableName} where 1=1 and classId=@classId) > 100 begin set @errorMessage = 'sum of percent load is exceeded 100'; goto Skipper; end
			";
		}elseif(isset($this->infoForAdditionalValidate['addEditMainEntity'])){
			list($columnsWithOrdered)=$this->infoForAdditionalValidate;
			//var_dump($columnsWithOrdered);
			$index = 0;
			foreach(array_keys($columnsWithOrdered) as $fieldName)	{
				if($fieldName=='classId'){
					$thisFieldVal = str_replace("'","''",$_REQUEST[''.$index]);
				}
				$index++;
			}
			$addsql = "; if (select sum(percentLoad) from {$tableName} where 1=1 and classId='{$thisFieldVal}') > 100 begin set @errorMessage = 'sum of percent load is exceeded 100'; goto Skipper; end";
		}else{
			$addsql = "";
		}
		
		//echo $addsql;
		return $addsql;
	}
} 
