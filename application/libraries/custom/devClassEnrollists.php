<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:25 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEnrollists extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	function additionalWhereInFilterRow(){
		$session = $this->_retSessionData();
		//if user group is users			
		if($session['userGroupId']===7){ 
			$dbPrefix = $this->_returnDbPrefix();
			
			//force user in users group can list only their enrolled class
			return " and {$dbPrefix}devClassEnrollists.employeeId in ('{$session['employeeId']}') "; 
		}else{
			return '';
		}
	}
	/**
	 * Override parent parent::doDbTransactions() for additional validation, or business rule validation.
	 * Suppose each class is not allow student enrolled more than capacity of class
	 * @param string $sql
	 * @return string
	 */
	public function doDbTransactions($sql) { //overide method doDbTransactions in class "Entity"
		$newSql = $sql." ".$this->devClassEnrollists_verify_enrollistNotExceededClassCapacity();				
		return parent::doDbTransactions($newSql);
	}
	/**
	 * call from doDbTransaction to add more SQL string for be verified that class enroll list is not exceed class capacity.
	 * @return string
	 */
	private function devClassEnrollists_verify_enrollistNotExceededClassCapacity(){
		$tableName = $this->getTableName();
		$dbPrefix = $this->_returnDbPrefix();
		//if the submited form came from sub-entity
		if(isset($this->infoForAdditionalValidateSubEntity['idValue'])){			
			//$idValue is main entity id value. In this case, idValue is id of table devClassEnrollists
			$idValue=$this->infoForAdditionalValidateSubEntity['idValue'];
			$addsql ="; 
				declare @classId int, @classCapacity int;
				set @classId = (select classId from {$tableName} where id='{$idValue}'); 
				set @classCapacity = (select capacity from {$dbPrefix}devClasses where 1=1 and id=@classId);
				if @classCapacity < (select count(*) from {$tableName} where classId=@classId)
				begin
					set @errorMessage = 'Class capacity is exceeded (Class capacity is '+convert(varchar(5),@classCapacity)+').'; 
					goto Skipper; 
				end
			";
		}
		//if the submited form came from main-entity
		elseif(isset($this->infoForAdditionalValidate['addEditMainEntity'])){
			list($columnsWithOrdered)=$this->infoForAdditionalValidate;			
			$index = 0;
			foreach(array_keys($columnsWithOrdered) as $fieldName)	{
				if($fieldName=='classId'){
					$thisFieldVal = str_replace("'","''",$this->_REQUESTE[''.$index]);
				}
				$index++;
			}
			$addsql = ";
				declare @classCapacity int;
				set @classCapacity = (select capacity from {$dbPrefix}devClasses where 1=1 and id='{$thisFieldVal}');
				if @classCapacity < (select count(*) from {$tableName} where classId='{$thisFieldVal}')
				begin set @errorMessage = 'Class capacity is exceeded (Class capacity is '+convert(varchar(5),@classCapacity)+').';  goto Skipper; end
			";
		}else{ 
			//if want to validate other operation such as delete code goes here
			$addsql = "";
		}		
		return $addsql;
	}
} 
