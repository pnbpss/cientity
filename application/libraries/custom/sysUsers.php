<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:17:00 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class sysUsers extends entity{	 
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
	 * This method update the lastUpdate column of updated row in table sysUsers to current datetime.
	 * @param string $sql
	 * @return string
	 */
	public function doDbTransactions($sql) { //overide method doDbTransactions in class "Entity"	
		//if operation is Updating
		if((isset($this->_REQUESTE['operation']))&&($this->_REQUESTE['operation']=='0')){
			$dbPrefix = $this->_returnDbPrefix();
			$id =  $this->_getIdOfCurrentOperation();
			$newSql = $sql."; update {$dbPrefix}sysUsers set lastUpdate=getdate() where id={$id}";	
		}else{
			$newSql = $sql;
		}
		return parent::doDbTransactions($newSql);
	}
	private function _getIdOfCurrentOperation(){
		list($columnsWithOrdered)=$this->infoForAdditionalValidate;
		$index = 0;
		foreach(array_keys($columnsWithOrdered) as $fieldName)	{
			if($fieldName=='id'){
				$id = str_replace("'","''",$this->_REQUESTE[''.$index]);
			}
			$index++;
		}
		return $id;
	}
	public function _getUserSessionValue($key){
		$session = $this->_retSessionData();
		return $session[$key];
	}
}
