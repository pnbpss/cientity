<?php
/**
 * Class mainForm
 * @author Panu Boonpromsook
 * 
 * mainForms is Class which use for create HTML components such as input field in filter row, or input component in mainModal. 
 * It also perform form validation.
 */
class mainForms{
	
	/**
	 * maximum of select2 input 
	 * @var type 
	 */
	private $maxSelectOptionShow = 10;
	
	/**
	 * codeIgniter Instance
	 * @var object 
	 */
	protected $CI;

	/**
	 * store entity extra info stored in extraEntityInfos.php
	 * @var array  
	 */
	protected $libExtraInfo;
	/**
	 * name of library (name of entity)
	 * @var string
	 */
	protected $libName;
	
	/**
	 * HTML for warning user if extraEntityInfo[$libName]
	 */
	const notFoundLibExtraInfoKey ="<div class=\"row filter-row\"><div class=\"col-sm-12 col-xs-6\"><div class=\"form-group form-focus\"><label class=\"control-label\">Not found ##### key of Entity in infos::extraEntityInfo</label></div></div></div>";
	
	/**
	 * HTML for warning user if extraEntityInfo[$libName]['descriptions']
	 */
	const searchAttributesNotExist = "<div class=\"row filter-row\"><div class=\"col-sm-12 col-xs-6\"><div class=\"form-group form-focus\"><label class=\"control-label\">Filter informations of ###### is not created in extraEntityInfos.php.</label></div></div></div>";
	
	/**
	 * session data for use in this class and extended.
	 * @var array 
	 */
	public $session;
	
	/**
	 * Store cleaned server $_REQUEST
	 * @var array 
	 */
	public $_REQUESTM;

	/**
	 * 
	 * @param string $libName
	 */
	public function __construct($libName){
		/**
		 * get CodeIniter Instance
		 */
		$this->CI =& get_instance();
		
		/**
		 *  load codeIgniter database library
		 */
		$this->CI->load->database();

		/**
		 * libExtraInfo is extra information of each libObject that will be fetch for compose input or form or sub-form
		 */
		$this->libExtraInfo = $this->_libExtraInfo($libName);
		
		/*
		 * libObject is class entity in entity.php that will be loaded for use.
		 */
		$this->libObject = $this->_loadLibrary($libName);		
		
		/*
		 * name of the library or entity
		 */
		$this->libName = $libName;
		
		if((isset($this->libExtraInfo['customized'])) && ($this->libExtraInfo['customized']===true)){
			/*
			 * do something else...
			 */
		}else{
			/*
			 * standard response format, will be used to response to front-end
			 */
			$this->response = $this->stdResponseFormat();
		}
	}
	
	/**
	 * store session in to some variable 
	 * @param type $index
	 * @param type $val
	 */
	public function _setRequestME($index, $val){
		//_REQUESTM use in m controller
		$this->_REQUESTM[$index] = $val;
		//libObject->_REQUESTE use in libObject, especially for additional validation. See example in APPPATH/libraries/custom/devClassEnrollists.php
		$this->libObject->_REQUESTE[$index] = $val;
	}
	
	/**
	 * return Library Extra entity info
	 * @return array
	 */
	public function _getLibExtraInfo(){
		return $this->libExtraInfo;
	}
	/**
	* return the library object
	* @return object
	* 	object of loaded entity
	*/
	public function libObjectInfo(){
		return $this->libObject;
	}
	/**
	* return library name or entity name
	* @return string
	* 	name of the entity or library
	*/
	public function libName(){
		return $this->libName;
	}
	
	/**
	* validate the submit $_REQUEST for update or insert by using stdValidationRules.
	* Looping through columnListInfo and validate one by one of column. if valdation error occured then put error message into response.
	* @return boolean
	*		true if passed, or false if not passed
	*/
	protected function formValidate($_request){
		
		$this->CI->load->library('form_validation');		
		list($columnsWithOrdered, $allLibExtraInfo)=$this->getColumnOrdered();

		//convert ordering into column name
		$request = $this->_convertOrdeingToColumnName($_request,$columnsWithOrdered,$allLibExtraInfo);
		
		$this->CI->form_validation->set_data($request);
		$rules = $this->libObject->stdValidationRules[$this->libName];
		
		foreach($rules as $val){
			//no need to validate hidden fields
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ 
				if(in_array($val['field'], $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					continue;
				}
			}
			$this->CI->form_validation->set_rules($val['field'], $val['label'], $val['rules'], additionalValidation::validationErrorMessage());
		}

		if ($this->CI->form_validation->run() == FALSE){
			$errorArray = $this->CI->form_validation->error_array();
			foreach($errorArray as $val){
				$this->notify('danger',"Error : ".$val);
			}
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * call from formValidate to maintain 20 lines per method
	 * @param array $_request
	 * @param array $columnsWithOrdered
	 * @param array $allLibExtraInfo
	 * @return array
	 */
	private function _convertOrdeingToColumnName($_request,$columnsWithOrdered,$allLibExtraInfo){
		$request = [];
		$index = 0;
		foreach($columnsWithOrdered as $fieldName=>$colInfos){
			//no need to validate hidden fields
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ 
				if(in_array($fieldName, $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					$index++;
					continue;
				}
			}
			if(isset($_request[''.$index])){ $request[$fieldName] = $_request[''.$index]; }
			//if(CODING_ENVIROMENT=='develop') $this->response['converted'][$fieldName] = @$_request[''.$index];
			if(isset($colInfos['references'])){
				if(!(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]))){
					$this->notify('danger'," {$fieldName} got references in db, but references for {$fieldName} not defined in addEditModal. ");
				}
			}
			$index++;			
			
		}
		return $request;
	}
	
	/**
	 * validate $_REQUEST sent from front-end in case of updating sub-entity
	 * @param type $columnName
	 *	name of column 
	 * @param type $value
	 *	value of column submitted from front-end
	 * @return boolean
	 *	result of validation
	 */
	protected function formValidateForSubEntity($columnName, $value){
		$this->CI->load->library('form_validation');
		$this->CI->form_validation->set_data([$columnName=>$value]);
		$rules = $this->libObject->stdValidationRules[$this->libName];		
		$haveRules = false;
		foreach($rules as $cRules){
			if($cRules['field']===$columnName){
				$conlumnRules = $cRules['rules'];
				$columnLabel = $cRules['label'];
				$haveRules = true;
				break;
			}
		}
		if($haveRules===false){
			return true;
		}		
		$this->CI->form_validation->set_rules($columnName, $columnLabel, $conlumnRules, additionalValidation::validationErrorMessage());
		if ($this->CI->form_validation->run() == FALSE){
			$errorArray = $this->CI->form_validation->error_array();
			foreach($errorArray as $val){				
				$this->notify('danger',"Error : ".$val);
			}
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * construct an SQL string and perform insert data to table by sending SQL string to this->libObject->doDbTransactions.
	 * It will put message into response in case of error, or no error
	 * @return string
	 */
	protected function insertData(){		
		
		//preparing field and data
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();		
		
		//for use in case of additional validation, for example see devClassExtInstructors.php
		$this->libObject->infoForAdditionalValidate = [$columnsWithOrdered, $allLibExtraInfo, $columns,'addEditMainEntity'=>true];
		
		$index = 0;
		$insertFields=""; $insertValues="";
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{
			// if field is auto increment thn by pass
			if($colInfos['is_identity']==1){ 
				$index++;
				continue;
			}
			
			//if the field is specied as hidden, then verify that it have been specified default value.
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ 
				if(in_array($fieldName, $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					if(isset($allLibExtraInfo[$this->libName]['addEditModal']['default'][$fieldName])){
						$insertFields.="{{".$fieldName."}}";
						$thisFieldVal = $this->makeSqlFromSpecifiedDefault($allLibExtraInfo[$this->libName]['addEditModal']['default'][$fieldName]);
						$thisFieldVal = $thisFieldVal==""?"NULL":"".$thisFieldVal."";
						$insertValues .= "{{".$thisFieldVal."}}";						
						$index++;
						continue;
					}else{
						$method = __METHOD__;
						$this->notify('danger','Fatal Error _001: The hidden keys in extraEntityInfos is defined, but not yet declared default. =&gt; '.$method);
						return;
					}
				}
			}
			$insertFields.="{{".$fieldName."}}";
			$thisFieldVal = $this->_REQUESTM[''.$index];
			//bug-id 20180805-00
			if(in_array($colInfos['Datatype'], ['date','datetime'])){
				list($year,$month,$day) = $this->splitAndConvertDate($thisFieldVal);
				$thisFieldVal = "{$year}/{$month}/{$day}";
			}
			//bug-id 20180805-00

			$thisFieldVal = $thisFieldVal===""?"NULL":"'".$this->escapeSQL($thisFieldVal)."'";
			$insertValues .= "{{".$thisFieldVal."}}";

			$index++;
		}
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['insertValue'] = $insertValues;}
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['insertFields'] = $insertFields;}
		

		//create sql for insert
		$insertFieldSql = str_replace("}}",")",str_replace ("{{","insert into {$this->CI->db->dbprefix}{$this->libName}(",str_replace("}}{{",",",$insertFields)));
		$insertValuesSql = str_replace("}}",");",str_replace ("{{"," values (",str_replace("}}{{",",",$insertValues)));
		
		$sqlInsert = $insertFieldSql.$insertValuesSql;
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['sqlInsert'] = $sqlInsert;}

		//insert data by using doDbTransactions
		$insertResult = $this->libObject->doDbTransactions($sqlInsert);
		if($insertResult[0]=='ok'){
			$this->notify('success',"Added {$allLibExtraInfo[$this->libName]['descriptions']} ");
		}else{
			$insertResult[1] = $this->convertDBErrorMessageToUser($insertResult['errorCode'],$insertResult['errorMessage'],$allLibExtraInfo);
			$this->notify('danger',"Unable To add {$allLibExtraInfo[$this->libName]['descriptions']}, because {$insertResult[1]} ");
		}
	}
	
	/**	
	 * get id value of related main-entity for use in insert sql of sub-entity. $_REQUEST contain the value id of main-entity
	* @return string 
	*	field value of main-entity which sub-entity is referenced from
	*/
	public function getIdToInsertForSubEntity(){
		//list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();		
		list($columnsWithOrdered)=$this->getColumnOrdered();		
		$index = 0;
		$idToEdit="";
		foreach($columnsWithOrdered as $colInfos){

			// is_identity mean id of main entity
			if($colInfos['is_identity']==1){ 
				$this->_REQUESTM[''.$index] = $this->_REQUESTM[''.$index];
				$idToEdit ="{$this->_REQUESTM[''.$index]}";
				$index++;
				break;
			}
		}
		return $idToEdit;
	}
	
	/**
	 *  construct "update.." SQL string for update data of entity and send it to execute at libObject->doDbTransactions. 
	 * After execution is finished it put the message result into response 
	 * @return null
	 */	 
	protected function editData(){		
		
		//preparing fields for update sql
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		
		//for use in case of additional validation, for example see devClassExtInstructors.php
		$this->libObject->infoForAdditionalValidate = [$columnsWithOrdered, $allLibExtraInfo, $columns,'addEditMainEntity'=>true];
		
		$index = 0;
		$editSql="update {$this->CI->db->dbprefix}{$this->libName} set ";
		$idToEdit="";
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{
			
			// if field is auto increment thn by pass
			if($colInfos['is_identity']==1){ 				
				$idToEdit ="".$this->escapeSQL($this->CI->input->post(''.$index,true))."";
				$index++;
				continue;
			}
			
			//if the field is specied as hidden, then verify that it have been specified default value.
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))) 
			{
				if(in_array($fieldName, $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					if(isset($allLibExtraInfo[$this->libName]['addEditModal']['default'][$fieldName])){
						$index++;
						continue;
					}else{
						$method = __METHOD__;
						$this->notify('danger','Fatal Error _002: The hidden keys in extraEntityInfos is defined, but not yet declared default. =&gt;  '.$method);
						return;
					}
				}
			}
			
			$thisFieldVal = $this->escapeSQL($this->CI->input->post(''.$index, true));

			if(in_array($colInfos['Datatype'], ['date','datetime'])){
				list($year,$month,$day) = $this->splitAndConvertDate($thisFieldVal);
				$thisFieldVal = "{$year}/{$month}/{$day}";
			}

			$thisFieldVal = $thisFieldVal===""?"NULL":"'".$this->escapeSQL($thisFieldVal)."'";
			$editSql.="{{{$fieldName}={$thisFieldVal}}}";
			$index++;
		}

		//create sql for update
		$editSql .= " where id = '{$idToEdit}' ";
		$finalSqlToEdit = str_replace("}}","",str_replace ("{{","",str_replace("}}{{",",",$editSql)));

		if(CODING_ENVIROMENT=='develop') $this->response['converted']['editSql'] = $editSql;

		//perform updating by using doDbTransaction method
		$editResult = $this->libObject->doDbTransactions($finalSqlToEdit);
		if($editResult[0]=='ok'){
			$this->notify('success',"Updating of {$allLibExtraInfo[$this->libName]['descriptions']} is saved");
		}else{
			$editResult[1] = $this->convertDBErrorMessageToUser($editResult['errorCode'],$editResult['errorMessage'],$allLibExtraInfo);
			$this->notify('danger',"Unable to update {$allLibExtraInfo[$this->libName]['descriptions']}, because {$editResult[1]} ");
		}
	}
	
	/**	
	 * convert DBErrorMessageToUser, 	
	* @param int errorCode
	*	error code from performed SQL execution
	* @param string errorMessage
	*	error Message from performed SQL execution
	* @param array allLibExtraInfo
	*	all library extra information
	* @return string str
	*	message that easy to be understood by user
	*/
	protected function convertDBErrorMessageToUser($errorCode, $errorMessage, $allLibExtraInfo){
		$str=$errorMessage;
		
		//for example "an not insert duplicate key row in object 'dbo.hds_devSubjectCourse' with unique index 'devSubjectCourseIdx'"
		if($errorCode==2601){
			$strposFrom = strpos($errorMessage, "' with unique index '")+21;
			$strposTo = strpos($errorMessage,"'",$strposFrom);
			$objectNameLength = $strposTo-$strposFrom;
			$idxObjectName = substr($errorMessage, $strposFrom, $objectNameLength);
			//var_dump($idxObjectName);
			$fieldInvolves = $this->getFieldNameInvolveToUniqueObject($idxObjectName);			
			$str = "{$allLibExtraInfo[$this->libName]['descriptions']}  which used given {$fieldInvolves} is already exists, unable to save.";
		}
		return $str;
	}
	
	/**
	* in case of user submit data to insert to table, and duplication error on unique index, this method fetch entity name for display to user
	* @param string idxObjectName
	*	unique index name of duplication error	
	* @return string
	*	name list of involved library
	*/
	private function getFieldNameInvolveToUniqueObject($idxObjectName)	{
		$columnNameList = "";
		$columnListInfo = $this->libObject->columnListInfo;
		foreach($columnListInfo as $columnInfo){
			if($columnInfo['index_name']==$idxObjectName){
				$revisedColumnDescriptions = $this->libObject->revisedColumnDescriptions;
				//$columName = $columnInfo['ColumnName'];
				foreach($revisedColumnDescriptions as $k=>$v){
					if($k==$columnInfo['ColumnName']){
						$columnNameList.="{{".$v[0]."}}";
					}
				}
			}
		}
		$rtcolumnNameList = str_replace("}}","",str_replace ("{{","",str_replace("}}{{"," and ",$columnNameList)));
		return $rtcolumnNameList;
	}
	
	/**
	 * construct "delete from.." SQL string for delete data of entity and send it to execute at libObject->doDbTransactions. 
	 * After execution is finished it put the message result into response 
	*/
	protected function deleteData(){
		if(!($this->libObject->insertUpdateAllowed($this->session['id']))){
			$this->notify('danger',"You're not authorized to insert, update or delete {$this->libExtraInfo['descriptions']}.");
			return;
		}		
		
		$allLibExtraInfo = $this->getColumnOrdered()[1];		
		$idToDelete = $this->escapeSQL($this->CI->input->post('dataId',true));
		$deleteSql="delete from {$this->CI->db->dbprefix}{$this->libName} where id= '{$idToDelete}' ";
		$deleteResult = $this->libObject->doDbTransactions($deleteSql);
		if($deleteResult[0]=='ok'){
			$this->notify('success',"Deleting of {$allLibExtraInfo[$this->libName]['descriptions']} is completed.");
		}else{
			$deleteResult[1] = $this->convertDBErrorMessageToUser($deleteResult['errorCode'],$deleteResult['errorMessage'],$allLibExtraInfo);
			$this->notify('danger',"Unable to delete {$allLibExtraInfo[$this->libName]['descriptions']}, because {$deleteResult[1]} ");
		}
	}
	
	/**
	*  In case of specified specified [default] value in [addEditModal] in extraEntityInfo, this method apply expression to "value" of "insert" SQL string.
	*  There are two type of default, "SQL" or "function", if default is "sql" then it return key field value of temp[0], otherwise it perform call_user_func and return the value.
	* @param array defaultExpressions
	*	array of type of expression, for instance "sql::getdate()", getSession::IDNo
	* @return string 
	*/	
	private function makeSqlFromSpecifiedDefault($defaultExpressions){
		$temp = explode("::",$defaultExpressions);
		if($temp[0]=='sql'){
			return $temp[1];
		}else{
			if(method_exists($this->libObject, $temp[0])){
				return "'".call_user_func([$this->libObject,$temp[0]],$temp[1])."'";
			}else{
				$this->notify('danger',"Operation Failed: There's no method for generated default value field.");				
				echo json_encode(['results'=>$this->response]);
				exit;
			}
		}
	}
	
	/**
	*  extract field of filter row to perform search by using searchAttributes in extraEntityInfo[entityName][searchAttributes]
	* @param array libDisplaySearchAttribute
	*	array of search attributes which specified in extraEntityInfo[entityName][searchAttributes]
	* @param string ordinal
	*	string of numeric, represent the order number in libDisplaySearchAttribute
	* @return array
	*	two element array [table name, column name]
	*/	
	protected function _getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $ordinal){
		
		/**
		 * Get the first element of array split by "::". The first element is table and column name of selected.
		 * For example devClassStatuses.descriptions::devClasses.statusId
		 */
		$temp1 = explode(";;",$libDisplaySearchAttribute[(int)$ordinal]);
		$temp2 = explode("::",$temp1[0]);
		
		$index = 0;

		/**
		 * Get the first element of array split by ".". The first element is table name.
		 */
		$temp3 = explode(".",$temp2[$index]);
		
		//if splited to two elements array then the first element is table name.
		if(sizeof($temp3)>1){
			
			//for example return devClasses.descriptions
			$returnTableAndColumName = explode(".",$temp2[$index]); 
			
			//if searchAtributes of current field contains more than one table, then send key 'id' back
			$returnTableAndColumName[1] = (sizeof($temp2))>1?'id':$returnTableAndColumName[1];

			return $returnTableAndColumName;
		}		
		//if not split to two element, return blank string. This will use current library in select SQL.
		else 
		{
			return array('',$temp3[0]); //for example return devClasses.descriptions
		}
	}
	
	/**
	*  create HTML of each input in extraEntityInfo[entityName][searchAttributes]
	* @param array OBJ
	*	array of information of current entity
	* @param string entityName
	*	name of current entity
	* @param string columnName
	*	column name
	* @param string filterOrdinal
	*	order number of column specified in extraEntityInfo[entityName][searchAttributes]
	* @param array fields
	*	array of fields in extraEntityInfo[entityName][searchAttributes] that was split by '::'
	* @return array
	*	two element array [table name, column name]
	*/	
	private function _eachFilter($obj, $columnName, $filterOrdinal, $fields){
		
		//if data to display is derived from other table then create select input by using _createSelectOptionFilter
		if(sizeof($fields)>1){
			return $this->_createSelectOptionFilter($filterOrdinal, $fields[0]);
		}

		$dataType = $this->_getColumnDataType($obj, $columnName);
		switch($dataType){
			case 'int':
				$inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
			break;
			case 'char':	case 'varchar':case 'text':case 'nchar':case 'nvarchar':case 'ntext':
				$maxLength = $this->_getColumnLength($obj, $columnName);
				$inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" maxlength=\"{$maxLength}\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
			break;
			case 'datetime': case 'date': // return function _inputDateItem if datatype is date or datetime
				return $this->_inputDateItem($filterOrdinal);			
			default: $inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
		}
		$fromToStr = explode('_',$filterOrdinal);
		$additionalLabel=""; if(isset($fromToStr[1])){ if($fromToStr[1]=='from') {$additionalLabel='(from)';} elseif($fromToStr[1]=='to'){$additionalLabel='(to)';}}
		
		$str = "<div class=\"col-sm-3 col-xs-6\">".PHP_EOL."<div class=\"form-group form-focus\">".PHP_EOL.
			"<label class=\"control-label\">#!#!#!#!#!#{$additionalLabel}</label>".PHP_EOL."{$inputItem}</div>".PHP_EOL."</div>".PHP_EOL."";
		return $str;
	}	
	
	/**	
	*  create html of each input in extraEntityInfo[entityName][searchAttributes] in case of referenced from other table
	* @param string filterOrdinal
	*	order number of column specified in extraEntityInfo[entityName][searchAttributes]
	* @param array fields
	*	table.column
	* @return string
	*	html of input of filter row
	*/	
	
	private function _createSelectOptionFilter($filterOrdinal, $fields){
		$options = "";
		//$maxSelectOptionShow = 5;
		list($tableName,$field) = explode(".",$fields);
		$sql = "select top ".($this->maxSelectOptionShow+1)." id, {$field} name from {$this->CI->db->dbprefix}{$tableName} order by id desc";
		$q = $this->CI->db->query($sql);
		$i = 0;
		$cientityClassOptionOverflow="";
		$infoForAjaxOptions="";
		foreach($q->result() as $row)
		{
			if ($i==$this->maxSelectOptionShow){
				$cientityClassOptionOverflow="cientitySelectOptionsOverflow";
				$options="";				
				//if selected <option> count is greater than $maxSelectOptionShow then use select2
				//if not, just create "select" input and not attached to select2
				$infoForAjaxOptions = $this->_getInfoForAjaxOptions($fields); 
				break;
			}
			$options.="<option value=\"{$row->id}\">{$row->name}</option>";
			$i++;
		}
		$str = "

								<div class=\"col-sm-3 col-xs-6\">
									<div class=\"form-group form-focus select-focus\">
											<label class=\"control-label\">#!#!#!#!#!#</label>
											<select cientityFormFilterOrder=\"{$filterOrdinal}\" class=\"select floating select2-hidden-accessible {$cientityClassOptionOverflow} cientityFilter\" tabindex=\"-1\" aria-hidden=\"true\" {$infoForAjaxOptions}>
												<option value=\"\">#!#!#!#!#!#</option>
												{$options}
											</select>
										</div>
								</div>

			";
		return $str;
	}	
	
	/**
	*  create html of each input  in extraEntityInfo[entityName][searchAttributes] in case of its type is date.
	*  In this case,datetime datatype, the input will be created twice, from and to.
	* @param string filterOrdinal
	*	order number of column specified in extraEntityInfo[entityName][searchAttributes]	
	* @return string
	*	html of input of filter row
	*/	
	private function _inputDateItem($filterOrdinal){
		$inputItem = "
						<div class=\"col-sm-3 col-xs-6\">".PHP_EOL."
							<div class=\"form-group form-focus\">".PHP_EOL."
								<label class=\"control-label\">#!#!#!#!#!# (from)</label>".PHP_EOL."
								<div class=\"cal-icon\">".PHP_EOL."
									<input  cientityFormFilterOrder=\"{$filterOrdinal}_from\" cientityFormDateTimeFilter=\"from\" type=\"text\" class=\"form-control floating datetimepicker cientityFilter cientityFormDate\" /> ".PHP_EOL."
								</div>".PHP_EOL."
							</div>".PHP_EOL."
						</div>".PHP_EOL."

						<div class=\"col-sm-3 col-xs-6\">".PHP_EOL."
							<div class=\"form-group form-focus\">".PHP_EOL."
								<label class=\"control-label\">#!#!#!#!#!# (to)</label>".PHP_EOL."
								<div class=\"cal-icon\">".PHP_EOL."
									<input cientityFormFilterOrder=\"{$filterOrdinal}_to\" cientityFormDateTimeFilter=\"to\" type=\"text\" class=\"form-control floating datetimepicker cientityFilter cientityFormDate\" /> ".PHP_EOL."
								</div>".PHP_EOL."
							</div>".PHP_EOL."
						</div>".PHP_EOL."
				";
		return $inputItem;
	}
	
	/**	
	*  compose information for select2 to initialize after page is loaded. the information consists of entity order number, field order number, etc. 		
	* @param string filterOrdinal
	*	order number of column specified in extraEntityInfo[entityName][searchAttributes]	
	* @return string
	*	string of ordinal position is in following format: i_j_k_l, i=order of entity, j= order of 'searchAttribute', k=order of 'display', l=order of filed
	*/	
	private function _getInfoForAjaxOptions($fields){
		$allLibExtraInfo = $this->_AllLibExtraInfo();
		$properties = "";
		$i = 0;
		foreach($allLibExtraInfo as $key1=>$val1){
			if($key1==$this->libName){
				$j=0;
				foreach($val1 as $key2=>$items){
					if($key2=='searchAttributes'){
						$k=0;
						foreach($items as $key3=>$itemss){
							if($key3=='display'){
								$l = 0;
								foreach($itemss as $itemssss){
									$fieldHere = explode("::",$itemssss);
									if($fieldHere[0]==$fields){
										$properties="{$i}_{$j}_{$k}_{$l}";
										break;
									}
									$l++;
								}
								break;
							}
							$k++;
						}
						break;
					}
					$j++;
				}
				break;
			}
			$i++;
		}
		$baseUrl=base_url();
		return "infoForAjaxOptions=\"{$baseUrl}m/infoForAjaxOptions/{$properties}\" ";
	}
	
	/**
	* fetch specified column informations
	* @param object obj
	*	this entity information 
	* @param string columnName
	*	name of column you want to all info.
	* @return array
	*/
	private function _getColumnInfos($obj, $columnName)	{
		//fetch all column datatype and store in $columnInfos array
		foreach($obj->columnListInfo as $columnInfos){
			if($columnInfos['ColumnName']==$columnName){
				return $columnInfos;
			}
		}
		return [];
	}
	
	/**
	* return datatype of column such as varchar, char, int
	* @param object obj
	*	this entity information 
	* @param string columnName
	*	name of column you want to know data type
	* @return string
	*	data type of column, such as varchar, char
	*/
	protected function _getColumnDataType($obj, $columnName){
		$columnInfo = $this->_getColumnInfos($obj, $columnName);
		return $columnInfo['Datatype'];
	}
	
	/**
	* return max-length of column 
	* @param object obj
	*	this entity information 
	* @param string columnName
	*	name of column you want to know data type
	* @return string
	*	data type of column, such as varchar, char
	*/
	private function _getColumnLength($obj, $columnName){
		$columnInfo = $this->_getColumnInfos($obj, $columnName);
		return $columnInfo['MaxLength'];
	}
	
	/**
	*	construct html code of search button in filter row
	* @return string
	*	HTML code of "search" button in filter row which contains important information such as entityOrdinal.
	*/
	private function _searchButton()	{
		$entityOrdinal = $this->entityOrdinal($this->libName);
		return "
						<div class=\"col-sm-3 col-xs-6\">  ".PHP_EOL."
							<a href=\"#\" entityOrdinal=\"{$entityOrdinal}\" class=\"btn btn-success btn-block cientityFilterStartSearch\">Search</a>  ".PHP_EOL."
						</div>".PHP_EOL."
		";
	}
	
	/**
	*	loop through this->_AllLibExtraInfo until found the specified libName.
	 * This method return the ordinal position of library(entity) in array of $this->_AllLibExtraInfo.
	* @param string libName
	* @return int
	*	in case of not found return -1
	*/
	private function entityOrdinal($libName){
		$allLibInfo = $this->_AllLibExtraInfo();
		$entityOrdinal=0;
		$notFound = true;
		foreach(array_keys($allLibInfo) as $key)	{
			if($key==$libName){
				$notFound = false;
				break;
			}
			$entityOrdinal++;
		}
		return $notFound?(-1):$entityOrdinal;
	}
	
	/**	
	*	load library of entity in APPPATH/libraries/custom folder, this is important part of using cientity
	* @param string libName
	*	name of specified library
	* @return object
	*	
	*/
	protected function _loadLibrary($libName){

		$this->CI->load->library('custom/'.$libName);
		$obj = new $libName;
		return $obj;
	}
	
	/**
	*	load extra information of specified entity of APPPATH/libraries/extraEntityInfos.php, this is important part of using cientity
	* @param string libName
	*	name of specified library
	* @return array
	*	In case of undefined extra entity info in extraEntityInfos.php it will return blank array.
	*/
	private function _libExtraInfo($libName){
		$this->CI->load->library('extraEntityInfos');
		return isset(extraEntityInfos::infos[$libName])?extraEntityInfos::infos[$libName]:[];
	}

	/**
	* fetch all library extra info 
	* @return array	
	*/
	private function _AllLibExtraInfo(){
		$this->CI->load->library('extraEntityInfos');
		return extraEntityInfos::infos;
	}
	
	 /**
	  * find description of field in columnDescriptionsColumnIndexed by exploded it with "||" and extract only first element of array
	  * @param object obj
	  *	object of entity which working on
	  * @param string columnName
	  *	name of column
	  * @return string
	  *	the description of field that use as label in add or edit form at front-end.
	 */	
	protected function _getFieldDescriptions($obj, $columnName){

		$tempData = explode("||",$obj->columnDescriptionsColumnIndexed[$columnName]['descriptions']);
		return $tempData[0];
	}

	 /**	  
	  * construct the filter row on first page of each entity at front end
	  * @return string
	  *	HTML string of filter row
	 */
	public function createFilterRow()	{
				
		//if search searchAttributes haven't been declared yet, then return searchAttributesNotExist
		if( !(isset($this->libExtraInfo['searchAttributes']))){
			return str_replace('######',$this->libName,self::searchAttributesNotExist);
		}else{
			$searchAttributes = $this->libExtraInfo['searchAttributes'];
		}

		//if search searchAttributes['display'] haven't been declared yet, then return searchAttributesNotExist
		if( !(isset($searchAttributes['display']))){
			return str_replace('######',$this->libName,selff::searchAttributesNotExist);
		}else{
			$searchAttributes = $this->libExtraInfo['searchAttributes'];
		}
		
		//load library of entity that involved this current entity
		foreach($searchAttributes['display'] as $key=>$item){ 
			$fieldInfo = explode(";;",$item);
			$fields = explode("::",$fieldInfo[0]);
			list($entityName, $columnName) = explode(".",$fields[0]);
			$entities[$entityName] = $this->_loadLibrary($entityName);
		}
		
		reset($searchAttributes['display']);
		$str = ""; $filterOrdinal=0;
		foreach($searchAttributes['display'] as $key=>$item){
			$fieldInfo = explode(";;",$item);
			//FieldInfo[0] is first entity.column which will be used to create filter in filter row.
			$fields = explode("::",$fieldInfo[0]); 

			list($entityName, $columnName) = explode(".",$fields[0]);

			//if between is specified
			if(isset($searchAttributes['between'])){  
				// if "between" is specified, the create from and to filter.
				if(in_array($fields[0], $searchAttributes['between'])){ 
					$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal.'_from',$fields)
						 .$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal.'_to',$fields);
				}else{
					$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal,$fields);
				}
			}else{
				$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal,$fields);
			}

			//if specified custom descriptin in extraEntityInfos, then use the specified
			$columnDescriptions = isset($fieldInfo[1]) ?$fieldInfo[1] :$this->_getFieldDescriptions($entities[$entityName],  $columnName) ;

			$replacedCStr = str_replace("#!#!#!#!#!#",$columnDescriptions,$cStr);

			$str.=$replacedCStr;

			$filterOrdinal++;
		}
		$str.=$this->_searchButton();

		return "<div class=\"row filter-row\">".$str."</div>";

	}
	
	 /**	  
	  * get option list for select2 which will be used at filter row or add/edit interface
	  * create SQL string and of involved to "properties"-the information which tell this
	  *  method that what is table and colum name should be query , and execute the SQL string
	  * @param string properties
	  *	The "properties" contains ordinal position of table and column name in allLibExtranInfo 
	  * and is in the following format: i_j_k_l, i=order of entity, j= order of 'searchAttribute', 
	  * k=order of 'display', l=order of filed should be query 
	  * @param string condition
	  *	user search keyword, typed on select2 search bar
	  * @return array 
	  *	array of search result, this will be used for select2 option
	 */
	public function infoForAjaxOptions($properties, $conditions){
		$property = explode("_", $properties);
		foreach($property as $key => $val){
			$loopCompare[$key] = (int) $val;
		}
		$allLibExtraInfo = $this->_AllLibExtraInfo();
		$i=0;
		foreach($allLibExtraInfo as $val1){
			if($i==$loopCompare[0]){
				$j=0;
				foreach($val1 as $items){
					if($j==$loopCompare[1]){
						$k=0;
						foreach($items as $itemss){
							if($k==$loopCompare[2]){
								$l = 0;
								foreach($itemss as $itemssss){
									if($l==$loopCompare[3]){
										$temp1 = explode(";;",$itemssss);
										$temp2 = explode("::",$temp1[0]);
										$temp3 = explode(".",$temp2[0]);

										$tableName = $temp3[0];
										$columnName = $temp3[1];
										break;
									}
									$l++;
								}
								break;
							}
							$k++;
						}
						break;
					}
					$j++;
				}
				break;
			}
			$i++;
		}
		$sql = "select top ".($this->maxSelectOptionShow)." id, {$columnName} name from {$this->CI->db->dbprefix}{$tableName} where {$columnName} like '{{%".$this->CI->db->escape($conditions)."%}}' order  by id desc";
		$sql2 = str_replace("'{{%'","'%",$sql);
		$sqlFinal = str_replace("'%}}'","%'",$sql2);
		
		$q = $this->CI->db->query($sqlFinal);		
		$response['results'] = [];
		array_push($response['results'], ['id'=>'','text'=>'select for search']);
		foreach($q->result() as $row)		{
			array_push($response['results'], ['id'=>$row->id,'text'=>$row->name]);			
		}
		return $response;
	}
	
	 /**	  
	  * get ordering of column of entity which should be displayed in add/edit in main page of entity and in sub-entity
	  * if the key "columnOrdering" did not specified, this method will return table column ordinal.	  
	  * involved entity's properties
		*	1. syncedColumnlistInfoWithRefKey list 
		*	2. revisedColumnDescriptions 
		*	3. stdValidationRules
	  * @return array [array columnsWithOrdered, array allLibExtraInfo, array columns]
	  *	the main purpose of this method is find columnsWithOrdered=column ordering, other return value, such as allLibExtraInfo, columns is junk because we can get these value in everywhere in this object
	  */
	protected function getColumnOrdered(){
		
		$tempColumns = $this->libObject->syncedColumnlistInfoWithRefKey;
		$columns = [];
		
		//re-organize syncedColumnlistInfoWithRefKey, make column name as key
		foreach($tempColumns as $key => $val){  
			$newKey = $val['ColumnName'];
			unset($val['ColumnName']);
			$columns[$newKey] = $val;
		}

		$allLibExtraInfo = $this->_AllLibExtraInfo();
		
		//if columnOrdering is declared in AddEditModal, then use columnOrdering
		if (isset($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'])){ 
			$columnsWithOrdered = [];
			foreach($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'] as $key=>$val){
				$columnsWithOrdered[$val] = $columns[$val];
			}
		}
		//if columnOrdering isn't declared in AddEditModal, then use column ordinal of table
		else{
			$columnsWithOrdered = $columns;
		}
		
		return [$columnsWithOrdered, $allLibExtraInfo, $columns];
	}
	
	 /**
	  * get ordering of column of entity which should be displayed in add/edit in main page of entity and in sub-entity
	  * if the key "columnOrdering" did not specified, this method will return table column ordinal.
	  * the difference between getColumnOrdered() and p_getColumnOrdered() is protected and public respectively.
	  * 
	  * @return array [array columnsWithOrdered, array allLibExtraInfo, array columns]
	  */
	public function p_getColumnOrdered(){
		return $this->getColumnOrdered();
	}
	
	 /**
	  * compose the front-end add/edit modal. if also compose html of sub-entity(this should call sub-entity) is the sub-entity information is specified.
	  * 
	  * @return string 
	  *	front-end HTML for construct add/edit modal.
	  */
	public function createAddEditModal(){		
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		$eachInputItem="";
		foreach($columnsWithOrdered as $key => $column){			
			//if it is hidden, specified in $this->libName]['addEditModal'], then by pass
			if (isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){ 
				if(array_search($key,$allLibExtraInfo[$this->libName]['addEditModal']['hidden'])!==false){
					continue;
				}
			}
			$eachInputItem.=$this->createEachInputForModal($allLibExtraInfo,$key,$column,$columns);
		}
		$modalHtml = str_replace("___i#n#p#u#t#_#I#t#e#m#s#__",$eachInputItem,$this->addEditModalWrapper((isset($allLibExtraInfo[$this->libName]['descriptions'])?$allLibExtraInfo[$this->libName]['descriptions']:"_".$this->libName)));
		$subEntityHtml = $this->getSubEntityModalNavsAndEntityPanel($allLibExtraInfo);
		$allModalHtml = str_replace('#h#r#d#s##s#u#b#e#n#t#i#t#y#m#o#d#a#l#',$subEntityHtml,$modalHtml);
		return $allModalHtml;
	}
	
	 /**	  
	  * compose the front-end add/edit sub-entity. this will displayed in nav bars under edit area of main modal	  
	  * 
	  * @return string 
	  *	front-end HTML for construct add/edit sub-entity.
	  */
	private function createAddEditSubEntity($suppressedFieldsInAdd){
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		$eachInputItem="";
		foreach($columnsWithOrdered as $key => $column)
		{
			//if it is hidden column, declared in addEditModal
			if (isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){ 
				if(array_search($key,$allLibExtraInfo[$this->libName]['addEditModal']['hidden'])!==false){
					continue;
				}
			}
			//$suppressedFieldsInAdd is array of none-display attribute of entity in AddEditSubEntity
			if(in_array($key,$suppressedFieldsInAdd)){
				continue;
			}
			$eachInputItem.=$this->createEachInputForModal($allLibExtraInfo,$key,$column,$columns);
		}
		$modalHtml = str_replace("___i#n#p#u#t#_#I#t#e#m#s#__",$eachInputItem,$this->addEditSubEntityWrapper());
		return $modalHtml;
	}

	/**
	 * create input for each column for use in addEditModal
	 * @param array allLibExtraInfo
	 *	all extra entity info
	 * @param int key
	 *	ordinal position of "columnWidth" which use to specified column
	 * @param string column
	 *	name of column that HTML of input will be constructed 
	 * @param array columns
	 *	all column info, such as, datatype, is_primary, maxLength, etc.
	 * @return string 	 *	
	 *	front-end HTML for input of each column
	 */
	private function createEachInputForModal($allLibExtraInfo,$key,$column,$columns)
	{
		if(!(isset($allLibExtraInfo[$this->libName]))){
			return str_replace('#####',$this->libName,self::notFoundLibExtraInfoKey);
		}
		$columnWitdth = isset($allLibExtraInfo[$this->libName]['addEditModal']['columnWidth'][$key])?$allLibExtraInfo[$this->libName]['addEditModal']['columnWidth'][$key]:6;
		$requiredIndicator = "";

		//if column is not null, display asterisk symbol at the end of field label, means must not left blank
		if($column['is_nullable']==0){ 
			$requiredIndicator="<span class=\"text-danger\">*</span>";
		}
		$disabledString="";
		if($key=='id'){
			$disabledString = "disabled";
		}
		$inputString="<span class=\"text-danger\">Unable to retreived input type.</span>";
		$fieldReferenceNumber = $this->_getReferenceNumber($key,$allLibExtraInfo);
		//if the column is references from other table.
		if(isset($column['references'])){ 
			$select2Info=$this->_getSelect2Info($key,$allLibExtraInfo[$this->libName]);
			$inputString="<select {$disabledString} class=\"form-control cientityInputField cientitySelectFromReference\" tabindex=\"-1\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' {$select2Info}></select>";
		}
		
		/**
		 *  if the column isn't references from other table in database design, but it was specified that it have to be referenced 
		 * from other table in in extraEntityInfos[$libName][addEditModal][reference] then it must create input as select2
		 */
		elseif(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$key])){
			$select2Info=$this->_getSelect2Info($key,$allLibExtraInfo[$this->libName]);
			$inputString="<select {$disabledString} class=\"form-control cientityInputField cientitySelectFromReference\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' {$select2Info}></select>";
		}else{			
			$dataType = $columns[$key]['Datatype'];
			switch($dataType)
			{
				case 'int':
					$inputString = "<input {$disabledString} class=\"form-control cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
				break;
				case 'char':	case 'varchar': case 'nchar': case 'nvarchar': case 'text': case 'ntext':
					$maxLength = $columns[$key]['MaxLength'];
					$inputString = "<input {$disabledString} maxLength='{$maxLength}' class=\"form-control cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
				break;
				case 'datetime': case 'date':
					$inputString = "<input {$disabledString} class=\"form-control datetimepicker cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
				break;

					default: $inputString = "<input {$disabledString} class=\"form-control cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
			}
			
		}

		//if field label is declared in extraEntityInfos
		$fieldLabels = (isset($allLibExtraInfo[$this->libName]['addEditModal']['fieldLabels'][$key]))
					?$allLibExtraInfo[$this->libName]['addEditModal']['fieldLabels'][$key]
					:$this->libObject->revisedColumnDescriptions[$key][0]
					;
		$eachIputItem="
								<div class=\"col-md-{$columnWitdth}\">
									<div class=\"form-group\">
										<label>{$fieldLabels}{$requiredIndicator}</label>
											{$inputString}
									</div>
								</div>
		";
		return $eachIputItem;
	}

	/**
	 * looking for ordinal position of column in 'columnOrdering' in 'addEditModal' in libraryInfo of current entity. 
	 * In case of 'columnOrdering' in 'addEditModal' in libraryInfo did not specified it will looking for key in syncedColumnlistInfoWithRefKey
	 * @param string columnName
	 *	name for specified column
	 * @param array allLibExtraInfo
	 *	all library information	 
	 * @return int
	 *	
	 */
	private function _getReferenceNumber($columName, $allLibExtraInfo){	
		//if columnOrdering is specified, then use it, else use table column ordinal which stored in syncedColumnlistInfoWithRefKey
		if (isset($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'])){ 
			foreach($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'] as $key=>$val){
				if($val==$columName)	{
					return $key;
				}
			}
		}else{
			$tempsyncedColumns = $this->libObject->syncedColumnlistInfoWithRefKey;
			foreach($tempsyncedColumns as $key => $val){
				if($val['ColumnName']==$columName){
					return $key;
				}
			}
		}
	}
	
	/**
	 *	construct the string of information for select2 initialization in case of that input fileld use dropdown
	 * @param string columnName
	 *	name for specified column
	 * @param array libInfo
	 *	information	of current library(entity)
	 * @return string
	 *	information of ajax url that select2 must use to initialized, in case of libInfos['addEditModal']['references'][$columnName] doesn't exists it will return '' 
	 */
	protected function _getSelect2Info($columnName, $libInfos)
	{
		if(!(isset($libInfos['addEditModal']['references'][$columnName]))){
			return '';
		}else{
			return $this->_getInfoForAjaxOptionsInAddEditModal($columnName);
		}
	}

	/**
	 * construct the string of information for select2 initialization in case of that input fileld use dropdown
	 * @param string columnName
	 *	name for specified column
	 * @param array libInfo
	 *	information	of current library(entity)
	 * @return string
	 *	information of ajax url that select2 must use to initialized
	 *	string of ordinal $properties is in following format: i_j_k_l, i=order of entity, j= order of 'addEditModal', k='references', l=order of filed
	 */
	private function _getInfoForAjaxOptionsInAddEditModal($columnName){
		$allLibExtraInfo = $this->_AllLibExtraInfo();
		$properties = "";
		$i = 0;
		foreach($allLibExtraInfo as $key1=>$val1)
		{
			if($key1==$this->libName){
				$j=0;
				foreach($val1 as $key2=>$items)
				{
					if($key2=='addEditModal')
					{
						$k=0;
						foreach($items as $key3=>$itemss)
						{
							if($key3=='references')
							{
								$l = 0;
								foreach($itemss as $key4=>$itemssss)
								{
									if($key4==$columnName)
									{
										$properties="{$i}_{$j}_{$k}_{$l}";
										break;
									}
									$l++;
								}
								break;
							}
							$k++;
						}
						break;
					}
					$j++;
				}
				break;
			}
			$i++;
		}
		$baseUrl=base_url();
		return "infoForAjaxOptions=\"{$baseUrl}m/infoForAjaxAddEditModalOptions/{$properties}\" ";
	}

	/**
	 * get a table name and column which will be use to construct sql string for retreiving data and sent to select2 in front-end
	 * , on the other hand it reverses the _getInfoForAjaxOptionsInAddEditModal method.
	 * @param string properties
	 *	properties is ordinal position information, table and column name, of key that will be fetched to construct sql 
	 * @param string condition 
	 *	user search keyword
	 * @return array
	 *	result of selected from constructed sql string 
	 */
	public function infoForAjaxAddEditModalOptions($properties, $conditions){
		$property = explode("_", $properties);
		foreach($property as $key => $val){
			$loopCompare[$key] = (int) $val;
		}
		$allLibExtraInfo = $this->_AllLibExtraInfo();
		$i=0;
		foreach($allLibExtraInfo as $val1){
			if($i==$loopCompare[0]){
				$j=0;
				foreach($val1 as $items){
					if($j==$loopCompare[1]){
						$k=0;
						foreach($items as $itemss){
							if($k==$loopCompare[2]){
								$l = 0;
								foreach($itemss as $itemssss){
									if($l==$loopCompare[3]){
										$temp1 = explode(".",$itemssss);
										$tableName = $temp1[0];
										$columnName = $temp1[1];
										break;
									}
									$l++;
								}
								break;
							}
							$k++;
						}
						break;
					}
					$j++;
				}
				break;
			}
			$i++;
		}
		$sql1 = "select top ".($this->maxSelectOptionShow)." id, {$columnName} name from {$this->CI->db->dbprefix}{$tableName} where {$columnName} like '{{%".$this->CI->db->escape($conditions)."%}}' order  by id desc";
		$sql2 = str_replace("'{{%'","'%",$sql1);
		$sqlFinal = str_replace("'%}}'","%'",$sql2);
		
		$q = $this->CI->db->query($sqlFinal);		
		$response['results'] = [];
		array_push($response['results'], ['id'=>'','text'=>'select..']);
		foreach($q->result() as $row)		{
			array_push($response['results'], ['id'=>$row->id,'text'=>$row->name]);			
		}
		return $response;
	}
	
	/**	 
	 * create HTML string of modal wrapper of each entity.
	 * the string "___i#n#p#u#t#_#I#t#e#m#s#__" will be replaced by inputs HTML
	 * the string "#h#r#d#s##s#u#b#e#n#t#i#t#y#m#o#d#a#l#" will be replaced by HTML of sub-entity 	 
	 * @param string entityDescriptions
	 *	the "descriptions" key of entity in array at file extraEntityInformation.php.
	 * @return string
	 *	HTML string of modal wrapper for front-end of each entity
	 */
	private function addEditModalWrapper($entityDescriptions){		
		//seek for ordinal number in extraEntityInfo by usning method entityOrdinal(). 
		//The ordinal will be use for reverse back to table name in add, edit operation.
		$cientityEntRefNumber= $this->entityOrdinal($this->libName);		
		
		return "
				<div class=\"modal-dialog\" style=\"height:2200px;\">
					<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
					<div class=\"modal-content modal-lg\">
						<div class=\"modal-header\">
							<h4 class=\"modal-title\"><span id='cientityOperationAddOrEditDesc'>_operation_</span>{$entityDescriptions}</h4>
						</div>
						<div class=\"modal-body\">
							<!--form!-->
								<div class=\"row\">
								___i#n#p#u#t#_#I#t#e#m#s#__
								</div>
								<div class=\"row\">
									<div class=\"m-t-20 text-center\">
										<input type='hidden' class='cientityOperationForAddEditModal'  />
										<button entityOrdinal='{$cientityEntRefNumber}' class=\"btn btn-primary addEditModalSubmitButton\">Save</button>
									</div>
								</div>
							<!--/form!-->
							<!--/subentity start!-->
							<div class='row'><div class='col-sm-12'>&nbsp;</div></div>
							<div class='row cientitySubEntityRow hide'>
								#h#r#d#s##s#u#b#e#n#t#i#t#y#m#o#d#a#l#
							</div>
							<!--/subentity end!-->
						</div>
					</div>
				</div>
		";
	}
	/**
	 * create HTML string of sub-modal(sub-entity) wrapper of each entity.
	 * the string "___i#n#p#u#t#_#I#t#e#m#s#__" will be replaced by inputs HTML of sub-entity	 	 
	 * @param string entityDescriptions
	 *	the "descriptions" key of entity in array at file extraEntityInformation.php.
	 * @return string
	 *	HTML string of modal wrapper for front-end of each entity
	 */
	private function addEditSubEntityWrapper(){
		//seek for ordinal number in extraEntityInfo by usning method entityOrdinal(). 
		//The ordinal will be use for reverse back to table name in add, edit operation.		
		$cientityEntRefNumber= $this->entityOrdinal($this->libName);
		//  style=\"height:2200px;\"
		return "
							<!--form!-->
								<div class=\"row\">
								___i#n#p#u#t#_#I#t#e#m#s#__
								</div>
								<div class=\"row\">
									<div class=\"m-t-20 text-center\">
										<input type='hidden' class='cientityOperationForAddEditModalInsubEntity' value='1' />
										<button entityOrdinal='{$cientityEntRefNumber}' class=\"btn btn-primary cientitySubEntityConfirmAddButton\">Add</button>
									</div>
								</div>
							<!--/form!-->
		";
	}
	/**
	 * create nav-bars and sub-entity panels	 
	 * @param array allLibExtraInfo
	 *	all library extra information 
	 * @return string
	 *	HTML string of nav-bars and panels of sub-entity
	 */
	private function getSubEntityModalNavsAndEntityPanel($allLibExtraInfo){
		$nav = '';
		$panel = '';
		if(isset($allLibExtraInfo[$this->libName]['addEditModal']['subEntity']))
		{
			$subEntitys = $allLibExtraInfo[$this->libName]['addEditModal']['subEntity'];
			$firstTime = true;
			foreach($subEntitys as $entityName => $entityInfos){
				
				//if label was re-declared in extraEntityInfo then use the re-declared, else use column descriptions 
				//which specified in database design.
				$label = (isset($entityInfos['label'])) 
				?$entityInfos['label']
				:(isset($allLibExtraInfo[$entityName]['descriptions'])?$allLibExtraInfo[$entityName]['descriptions']:"?".$entityName); 

				$subEntitycientityForms = new mainForms($entityName);
				$suppressedFieldsInAdd = array();
				if(isset($entityInfos['suppressedFieldsInAdd']))	{
					$suppressedFieldsInAdd = $entityInfos['suppressedFieldsInAdd'];
				}
				$eachInputForSubEntity = $subEntitycientityForms->createAddEditSubEntity($suppressedFieldsInAdd);

				$eachInputForSubEntity = str_replace("cientityInputField","cientityInputFieldForSubEntity",$eachInputForSubEntity);
				$eachInputForSubEntity = str_replace("cientitySelectFromReference","cientitySelectFromReferenceForSubEntity",$eachInputForSubEntity);
				$entityOrdinal = $this->entityOrdinal($entityName);
				
				$nav.="<li role=\"presentation\" class=\"".($firstTime?"active":"")."\"  cientitySubEntityModalPanelId='{$entityOrdinal}'><a href=\"#\">{$label}</a></li>";
				$panel.="
				<div class=\"panel panel-default cientitySubmodelPanel ".($firstTime?"":"hide")."\"  cientitySubEntityModalPanelId='{$entityOrdinal}'>
					<div class=\"panel-body \">
						 <div class='row'>
							<div class='col-xs-3'><strong>{$label}</strong></div>
							<div class='col-xs-8'>
								<span class='pull-right'>
									<button type=\"button\" class=\"btn btn-sm btn-secondary cientityShowAddEditSubEntityPanel\" cientitySubEntityModalPanelId='{$entityOrdinal}' role='button'>
										<i class=\"fa fa-plus\"></i> add {$label}
									</button>
								</span>
							</div>
							<div class='col-xs-1'>
								<div class=\"dropdown\">
										<a href=\"#\" class=\"action-icon dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"fa fa-ellipsis-v\"></i></a>
										<ul class=\"dropdown-menu pull-right\">
											<li class='cientityToggleSelectedSubEntityCheckbox' cientityEntityReference='{$entityOrdinal}'><a href=\"#\"><i class=\"fa fa-pencil m-r-5\"></i>toggle selected</a></li>
											<li class='cientityDeleteExistingSubEntityRecord' cientityEntityReference='{$entityOrdinal}'><a href=\"#\"><i class=\"fa fa-trash-o m-r-5\"></i>delete selected</a></li>
										</ul>
									</div>
							</div>
						 </div>
						 <div class='row'><div class=\"col-md-12\">&nbsp;</div></div>
						 <div class='panel cientitySubPanel hide' cientitySubEntityModalPanelId='{$entityOrdinal}'>
							<div class=\"panel-heading text-right\">
									<div class=\"btn-group btn-group-xs\">
										<button class=\"btn btn-danger cientityHidePanel\" type=\"button\" cientitySubEntityModalPanelId='{$entityOrdinal}'>&times;</button>
									</div>
							  </div>
							<div class='panel-body'>
								<div class='row'>
									<div class='col-md-12'>
										{$eachInputForSubEntity}
									</div>
								</div>
							</div>
						 </div>
						 <div class=\"row searchProgressBarRowSubEntity hide\" cientitySubEntityModalPanelId='{$entityOrdinal}'>
							<div class=\"col-md-12\">&nbsp;</div>
							<div class=\"col-md-12\">
									<div class=\"progress\">
									  <div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\">
										<span class=\"sr-only\">100% Complete</span>
									  </div>
									</div>
							</div>
						</div>
						 <div class=\"row searchResultsDataTableRowSubEnitity\">
							<div class=\"col-md-12\">
								<div class=\"table-responsive cientityDisplaySearchResultSubEnitity\" cientitySubEntityModalPanelId='{$entityOrdinal}'>
								</div>
							</div>
						</div>
					</div>
					<div class=\"panel-footer\"></div>
				</div>
				";
				$firstTime=false;
			}
		}
		return $nav==""?"":"<ul class=\"nav nav-tabs cientitySubEntityModalNavBar\">".$nav."</ul>".$panel;		
	}
	
	/**
	 * define the standard response for AJAX
	 * @return array 
	 *	array of standard response
	 */
	protected function stdResponseFormat(){
		return ['converted' => [],'fields'=>[],'notifications' => ['info' => [],'warning'=>[],'danger'=>[],'success'=>[]],'references'=>[]];
	}
	/**
	 *	put notification message to display for end-users
	 * @param string notificationType
	 *	type of notification, such as success, info, danger, warning.	 
	 * @param string $msg
	 *	message to end-user
	 */
	protected function notify($notificationsType,$msg)	{
		if(!(isset($this->response['notifications'][$notificationsType]))){
			array_push($this->response['notifications']['danger'], "Program error, notification type {$notificationsType} is not defined.");
		}else{			
			// this should be checked that the danger type message could not more than 5 items, I'll do it later.
			array_push($this->response['notifications'][$notificationsType], $msg);
		}
	}

	/**
	 * @deprecated since version 1.0.0
	 *	to ensure that the data of submitted do doesn't already exists, this will be applied with unique column only
	 * @param string rule
	 * @param string $request
	 *	message to end-user
	 */
	public function recordExistsInOther($rule,$request){
		$item = str_replace("]","",(str_replace("is_unique[","",$rule)));
		list($tableName, $fieldName) = explode(".",$item);

		$conditions = $request[$fieldName];

		$sql = "select top 1 * from {$this->CI->db->dbprefix}{$tableName} where {$fieldName} = '{{".$this->CI->db->escape($conditions)."}}' and id <> {$request['id']} ";
		$sql = str_replace("'{{'","'",$sql);
		$sql = str_replace("'}}'","'",$sql);

		$q = $this->CI->db->query($sql);
		$row = $q->row();

		if (isset($row)){
                        return TRUE;
                }else{
                        return FALSE;
                }
	}
	
	/**	 
	 * Escape for SQL server for prevent sql injection.
	 * @param string, or array str
	 *	_REQUEST or something else, especially input from user.
	 * @return string, or array 
	 *	escaped string or array of escaped string
	 */	
	protected function escapeSQL($str){
		return is_array($str)?$this->escapeArray($str):str_replace("'","''", $str);
	}
	private function escapeArray($arr){
		reset($arr);
		$newArray = array();
		
		foreach($arr as $key=>$val)	{			
			if (is_array($val)){
				return $this->escapeArray($val);
			}else{
				$newArray[$key] = is_string($val)?str_replace("'","''", $val):$val;
			}
		}		
		return $newArray;
	}
	
	/**	 
	 * converted date from dd/mm/yyyy to yyyy/mm/dd format, and also convert Buddist to Christ. In case of sperator is "-" it also converted to "/"
	 * @param string date
	 *	date in dd/mm/yyyy format
	 * @param array 
	 *	array of string ['yyyy','mm','dd]
	 */
	protected function splitAndConvertDate($datetime){
		$splitBySpaceDate = explode(" ", $datetime);
		if(isset($splitBySpaceDate[1])){
			$time=' '.$splitBySpaceDate[1].(isset($splitBySpaceDate[2])?' '.$splitBySpaceDate[2]:'');
		}else{
			$time='';
		}
		$temp = explode("/",$splitBySpaceDate[0]);
		if(sizeof($temp)==3){
			$temp[2] = $this->toBCYear($temp[2]);			
			return array($temp[2],$temp[1],$temp[0].$time);
		}else{
			$temp = explode("-",$splitBySpaceDate[0]);
			if(sizeof($temp)==3)
			{
				$temp[2] = $this->toBCYear($temp[2]);
				return array($temp[2],$temp[1],$temp[0].$time);
			}
		}
		return array(null,null,null);
	}
	
	/**	 
	 *	convert year from buddhist year to christian year, if the differences of  "year" and current year is greater than 400 then year-543
	 * @param string year
	 *	year
	 * @return int
	 *	converted year
	 */
	private function toBCYear($year){		
		//if year $year is more than current year, that user should be supplied buddist year.
		if(((int)$year)-((int)date("Y"))>400){
			$year = ((int) $year) - 543;
		}
		return $year;
	}	
	
	/**	 
	 * get column datatype for compose input tag in data-table of sub-entity 
	 * @param string key
	 *	column name
	 * @param array libInfo
	 *	library information 
	 * @return string
	 *	datatype of specified column
	 */
	protected function _getColumnDataTypeDirectlyForSubEntityRowInput($key, $libInfos){
		foreach($libInfos['selectAttributes']['fields'] as $val){
			$temp1 = explode(";;",$val);
			$temp2 = explode(".",$temp1[0]);
			if ($temp2[1]==$key){
				$tableName = $this->CI->db->dbprefix.$temp2[0];
				$columnName = $temp2[1];
				break;
			}
		}
		if(isset($tableName)){
			$sql = "SELECT c.name 'columnName',t.Name 'datatype',c.max_length 'maxLength',c.precision 'precision',c.scale ,c.is_nullable,c.is_identity,c.default_object_id,ISNULL(i.is_primary_key, 0) 'PrimaryKey',i.is_unique,i.is_unique_constraint,i.name index_name
				FROM sys.columns c INNER JOIN sys.types t ON c.user_type_id = t.user_type_id LEFT OUTER JOIN sys.index_columns ic ON ic.object_id = c.object_id AND ic.column_id = c.column_id LEFT OUTER JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id 
				WHERE c.object_id = OBJECT_ID('{$tableName}') and c.name='{$columnName}'";
				$q = $this->CI->db->query($sql);
				$row = $q->row();
				return $row->datatype;
		}else{
			return '';
		}
	}
} //end of class mainForm
