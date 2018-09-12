<?php  
/** create by application/controllers/createEntityClassLibrary , since 09:50:30 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devQuizAnswerSubjectives extends entity{	 
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
	 * validate that Class Enrollist, Class, Exam paper, Quiz, and Quiz Choice Selection must be match.
	 * @param string $sql
	 * @return string
	 */
	public function doDbTransactions($sql) { //overide method doDbTransactions in class "Entity"
		$newSql = $sql." ".$this->_verify_CE_CL_EP_Q_QC_is_Match();				
		return parent::doDbTransactions($newSql);
	}
	
	/**
	* Additional SQL to prevent Class Enrollist, Class, Exam paper, Quiz, and Quiz Choice Selection mismatch
	*/
	private function _verify_CE_CL_EP_Q_QC_is_Match(){
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
			
			if (select COUNT(*) from hds_devClassEnrollists cel inner join hds_devClassExamPapers cep on cel.classId=cep.classId inner join hds_devQuizAnswerSubjectives qao on cel.id=qao.classEnrollListId and cep.id=qao.classExamPaperId where qao.id=@thisFieldVal) = 0
			begin 
				set @errorMessage = 'Class Enrollment and Exam paper Mismatch.';  
				goto Skipper; 
			end			
			
			";
		}else{ 
			//if want to validate other operation such as delete code goes here
			$sql = "";
		}		
		return $sql;		
	}
} 
