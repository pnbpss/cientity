<?php  
/** create by application/controllers/createEntityClassLibrary , since 07:56:27 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devClassEvaluations extends entity{	 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
	/**
	 * Override parent parent::doDbTransactions() for additional validation, or business rule validation.
	 * validate that evaluator and student must in the same class
	 * @param string $sql
	 * @return string
	 */
	public function doDbTransactions($sql) { //overide method doDbTransactions in class "Entity"
		$newSql = $sql." ".$this->devClassEnrollists_verify_evaluatorAndStudentInSameClass();				
		return parent::doDbTransactions($newSql);
	}
	/**
	* Additional SQL to prevent user add class evaluator and class enrolllment mismatch
	*/
	private function devClassEnrollists_verify_evaluatorAndStudentInSameClass(){
		$tableName = $this->getTableName();
		$dbPrefix = $this->_returnDbPrefix();
		
		//if the submited form came from sub-entity
		if(isset($this->infoForAdditionalValidateSubEntity['idValue'])){			
			//$idValue is main entity id value. In this case, idValue is id of table devClassEnrollists
			$idValue=$this->infoForAdditionalValidateSubEntity['idValue'];
			$sql ="; 
					--Sub-entity not declared 
				";
		}
		
		//if the submited form came from main-entity
		elseif(isset($this->infoForAdditionalValidate['addEditMainEntity'])){
			list($columnsWithOrdered)=$this->infoForAdditionalValidate;	
			$index = 0;
			foreach(array_keys($columnsWithOrdered) as $fieldName)	{
				if($fieldName=='id'){
					$thisFieldVal = str_replace("'","''",$this->_REQUESTE[''.$index]);
				}
				$index++;
			}
			///check if operation = 1 (add);
			if((isset($this->_REQUESTE['operation']))&&($this->_REQUESTE['operation']=='1')){
				$operation='insert';
			}else{
				$operation='update';
			}
			
			$sql = "; 			
			declare @classIdInEnrollist int, @classIdInClassEvaluator int, @thisFieldVal int,@operation varchar(max); 
			set @operation = '{$operation}';
			if @operation='insert'
			begin
				set @thisFieldVal = SCOPE_IDENTITY();
			end
			else
			begin
				set @thisFieldVal ='{$thisFieldVal}';
			end
			
			set @classIdInEnrollist = (
									select cel.classId  from {$dbPrefix}devClassEnrollists cel  
									inner join {$tableName} tt on cel.id=tt.classEnrollistId
									where tt.id=@thisFieldVal
								); 
			set @classIdInClassEvaluator = (
									select cet.classId  from {$dbPrefix}devClassEvaluators cet  
									inner join {$tableName} tt on cet.id=tt.classEvaluatorId
									where tt.id=@thisFieldVal
								); 
			if @classIdInClassEvaluator <> @classIdInEnrollist
			begin 
				set @errorMessage = 'Class Evaluator and Class Enrollment mismatch.';  
				goto Skipper; 
			end";
		}else{ 
			//if want to validate other operation such as delete code goes here
			$sql = "";
		}		
		return $sql;
		
	}
} 
