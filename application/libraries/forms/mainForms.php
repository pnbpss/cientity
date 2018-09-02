<?php
/**
* mainForms เป็นคลาสที่เอาไว้สร้างฟอร์ม input ต่างๆ เช่น ตัวเลือกสำหรับค้นหา, ฟอร์มสำหรับกรอกข้อมูลต่างๆ
* และอาจจะ validate input จากฟอร์มก่อน update, insert, delete ไปยัง database
*/
class mainForms
{
	protected $CI, $obj,$libExtraInfo,$libName;
	const notFoundLibExtraInfoKey ="<div class=\"row filter-row\"><div class=\"col-sm-12 col-xs-6\"><div class=\"form-group form-focus\"><label class=\"control-label\">Not found ##### key of Entity in infos::extraEntityInfo</label></div></div></div>";
	const searchAttributesNotExist = "<div class=\"row filter-row\"><div class=\"col-sm-12 col-xs-6\"><div class=\"form-group form-focus\"><label class=\"control-label\">Filter informations of ###### is not created in extraEntityInfos.php.</label></div></div></div>";
	/**
	*	maxSelectOptionShow is number of row of every select2 component that will be selected
	*/
	private $maxSelectOptionShow = 4;

	public $session, $_REQUESTM;

	public function __construct($libName){
		$this->CI =& get_instance();
		$this->CI->load->database();

		//libExtraInfo is extra information of each libObject that will be fetch for compose input or form or sub-form
		$this->libExtraInfo = $this->_libExtraInfo($libName);
		
		//libObject is class entity in entity.php that will be loaded for use.
		$this->libObject = $this->_loadLibrary($libName);		
		
		//name of the library or entity
		$this->libName = $libName;
		
		if((isset($this->libExtraInfo['customized'])) && ($this->libExtraInfo['customized']===true)){
			//do something else...
		}else{					

			//standard response format, will be used to response to front-end
			$this->response = $this->stdResponseFormat();
		}
	}
	public function _setRequestME($index, $val){
		$this->_REQUESTM[$index] = $val;
		$this->libObject->_REQUESTE[$index] = $val;
	}
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
	* 	name of the entity or libray
	*/
	public function libName(){
		return $this->libName;
	}
	/**
	* <p><pre>
	* validate the submit $_REQUEST for update or insert by using stdValidationRules.
	* Looping through columnListInfo and validate one by one of column. if valdation error occured then put error message into response.
	* </pre><p>
	* @return boolean
	*		true if passed, or false if not passed
	*/
	protected function formValidate($_request){
		
		$this->CI->load->library('form_validation');
		list($columnsWithOrdered, $allLibExtraInfo)=$this->getColumnOrdered();

		//แปลง order ของ filterform เป็น column
		$request = [];
		$index = 0;
		foreach($columnsWithOrdered as $fieldName=>$colInfos){
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ //ไม่ต้อง validate fied hidden
				if(in_array($fieldName, $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					$index++;
					continue;
				}
			}
			if(isset($_request[''.$index])){ $request[$fieldName] = $_request[''.$index]; }
			if(CODING_ENVIROMENT=='develop') $this->response['converted'][$fieldName] = @$_request[''.$index];
			if(isset($colInfos['references'])){
				if(!(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]))){
					$this->notify('danger'," {$fieldName} got references in db, but references for {$fieldName} not defined in addEditModal. ");
				}
			}
			$index++;
			//(bugId 28010804-01) กรณี validate unique ไปใช้ วิธี insert เข้า database ไปเลย แล้วค่อยเอา error message ของ database มาใช้ดีกว่า
		}
		$this->CI->form_validation->set_data($request);
		$rules = $this->libObject->stdValidationRules[$this->libName];
		//var_dump($rules);
		foreach($rules as $val){
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ //ไม่ต้อง validate fied hidden
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
		}
		else
		{
			return true;
		}
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
		//var_dump($rules);
		//var_dump($value);
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
		//var_dump($columnLabel);
		$this->CI->form_validation->set_rules($columnName, $columnLabel, $conlumnRules, additionalValidation::validationErrorMessage());
		if ($this->CI->form_validation->run() == FALSE){
			$errorArray = $this->CI->form_validation->error_array();
			foreach($errorArray as $val){
				//echo $val;
				$this->notify('danger',"Error : ".$val);
			}
			return false;
		}else{
			return true;
		}
	}
	/**
	* <p><pre>
	 * construct an SQL string and perform insert data to table by sending SQL string to this->libObject->doDbTransactions.
	 * .it will put message into response in case of error, or no error.
	* </pre><p>
	*/
	protected function insertData(){		
		
		//preparing field and data(เตรียมฟิลด์ และข้อมูล)
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();		
		
		//for use in case of additional validation, for example see devClassExtInstructors.php
		$this->libObject->infoForAdditionalValidate = [$columnsWithOrdered, $allLibExtraInfo, $columns,'addEditMainEntity'=>true];
		
		$index = 0;
		$insertFields=""; $insertValues="";
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{
			if($colInfos['is_identity']==1){ //ถ้าเป็นฟิลด์ auto increment ให้ข้ามไปเลย
				$index++;
				continue;
			}
			//echo $fieldName."|||";
			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))){ //fied hidden ต้องไปดูว่า ระบุ default มาจาก extraEntityInfos หรือไม่ก่อน
				if(in_array($fieldName, $allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){
					if(isset($allLibExtraInfo[$this->libName]['addEditModal']['default'][$fieldName])){
						$insertFields.="{{".$fieldName."}}";
						$thisFieldVal = $this->makeSqlFromSpecifiedDefault($allLibExtraInfo[$this->libName]['addEditModal']['default'][$fieldName]);
						$thisFieldVal = $thisFieldVal==""?"NULL":"".$thisFieldVal."";
						$insertValues .= "{{".$thisFieldVal."}}";
						//$insertValues .= "{$fieldName}=>{{".$thisFieldVal."}}";
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

			//$insertValues .= "{$fieldName}=>{{".$_REQUEST[''.$index]."}}";
			//$request[$fieldName] = $_REQUEST[''.$index];
			$index++;
		}
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['insertValue'] = $insertValues;}
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['insertFields'] = $insertFields;}
		//จบ เตรียมฟิลด์ และข้อมูล

		//create sql(สร้าง sql)
		$insertFieldSql = str_replace("}}",")",str_replace ("{{","insert into {$this->CI->db->dbprefix}{$this->libName}(",str_replace("}}{{",",",$insertFields)));
		$insertValuesSql = str_replace("}}",");",str_replace ("{{"," values (",str_replace("}}{{",",",$insertValues)));
		
		$sqlInsert = $insertFieldSql.$insertValuesSql;
		if(CODING_ENVIROMENT==='develop'){ $this->response['converted']['sqlInsert'] = $sqlInsert;}
		//echo "=={$sqlInsert}=="; exit;
		//ทำการ insert
		$insertResult = $this->libObject->doDbTransactions($sqlInsert);
		if($insertResult[0]=='ok'){
			$this->notify('success',"Added {$allLibExtraInfo[$this->libName]['descriptions']} ");
		}else{
			$insertResult[1] = $this->convertDBErrorMessageToUser($insertResult['errorCode'],$insertResult['errorMessage'],$allLibExtraInfo);
			$this->notify('danger',"Unable To add {$allLibExtraInfo[$this->libName]['descriptions']}, because {$insertResult[1]} ");
		}
	}
	/**
	* <p><pre>
	 * get id value of related main-entity for use in insert sql of sub-entity. $_REQUEST contain the value id of main-entity
	* </pre><p>	
	* @return string 
	*	field value of main-entity which sub-entity is referenced from
	*/
	public function getIdToInsertForSubEntity(){
		//list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();		
		list($columnsWithOrdered, $allLibExtraInfo)=$this->getColumnOrdered();		
		$index = 0;
		$idToEdit="";
		foreach($columnsWithOrdered as $colInfos){

			if($colInfos['is_identity']==1){ // is_identity mean id of main entity
				$this->_REQUESTM[''.$index] = $this->_REQUESTM[''.$index];
				$idToEdit ="{$this->_REQUESTM[''.$index]}";
				$index++;
				break;
			}
		}
		return $idToEdit;
	}
	/**
	* <p><pre>
	 *  construct "update.." SQL string for update data of entity and send it to execute at libObject->doDbTransactions. 
	 * After execution is finished it put the message result into response 
	* </pre><p>		
	*/
	protected function editData(){
		//$this->CI->load->library('input');
		//เตรียมฟิลด์ และข้อมูล
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		
		//for use in case of additional validation, for example see devClassExtInstructors.php
		$this->libObject->infoForAdditionalValidate = [$columnsWithOrdered, $allLibExtraInfo, $columns,'addEditMainEntity'=>true];
		
		$index = 0;
		$editSql="update {$this->CI->db->dbprefix}{$this->libName} set ";
		$idToEdit="";
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{

			if($colInfos['is_identity']==1){ //ถ้าเป็นฟิลด์ auto increment ให้ข้ามไปเลย แสดงว่าเป็น id
				//$_REQUEST[''.$index] = $this->escapeSQL($_REQUEST[''.$index]);
				
				$idToEdit ="".$this->escapeSQL($this->CI->input->post(''.$index,true))."";
				$index++;
				continue;
			}

			if((isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden']))) //fied hidden ต้องไปดูว่า ระบุ default มาจาก extraEntityInfos หรือไม่ก่อน
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

			//$thisFieldVal = $_REQUEST[''.$index];
			$thisFieldVal = $this->escapeSQL($this->CI->input->post(''.$index, true));

			if(in_array($colInfos['Datatype'], ['date','datetime'])){
				list($year,$month,$day) = $this->splitAndConvertDate($thisFieldVal);
				$thisFieldVal = "{$year}/{$month}/{$day}";
			}

			$thisFieldVal = $thisFieldVal===""?"NULL":"'".$this->escapeSQL($thisFieldVal)."'";
			$editSql.="{{{$fieldName}={$thisFieldVal}}}";
			$index++;
		}
		//if(CODING_ENVIROMENT=='develop') $this->response['converted']['editSql'] = $editSql;
		//จบ เตรียมฟิลด์ และข้อมูล

		//สร้าง sql
		$editSql .= " where id = '{$idToEdit}' ";
		$finalSqlToEdit = str_replace("}}","",str_replace ("{{","",str_replace("}}{{",",",$editSql)));

		//if(CODING_ENVIROMENT=='develop') $this->response['converted']['editSql'] = $editSql;

		//ทำการ edit
		$editResult = $this->libObject->doDbTransactions($finalSqlToEdit);
		if($editResult[0]=='ok'){
			$this->notify('success',"Updating of {$allLibExtraInfo[$this->libName]['descriptions']} is saved");
		}else{
			$editResult[1] = $this->convertDBErrorMessageToUser($editResult['errorCode'],$editResult['errorMessage'],$allLibExtraInfo);
			$this->notify('danger',"Unable to update {$allLibExtraInfo[$this->libName]['descriptions']}, because {$editResult[1]} ");
		}
	}
	/**
	* <p><pre>
	 * convert DBErrorMessageToUser, 
	* </pre><p>
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
		//var_dump($errorMessage); //Cannot insert duplicate key row in object 'dbo.hds_devSubjectCourse' with unique index 'devSubjectCourseIdx'
		if($errorCode==2601){
			$strposFrom = strpos($errorMessage, "' with unique index '")+21;
			$strposTo = strpos($errorMessage,"'",$strposFrom);
			$objectNameLength = $strposTo-$strposFrom;
			$idxObjectName = substr($errorMessage, $strposFrom, $objectNameLength);
			//var_dump($idxObjectName);
			$fieldInvolves = $this->getFieldNameInvolveToUniqueObject($idxObjectName);			
			$str = "{$allLibExtraInfo[$this->libName]['descriptions']}  which used with {$fieldInvolves} is exists, unable to save.";
		}
		return $str;
	}
	/**
	* <p><pre>
	* in case of user submit data to insert to table, and duplication error on unique index, this method fetch entity name for display to user
	* </pre><p>
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
		$rtcolumnNameList = str_replace("}}","",str_replace ("{{","",str_replace("}}{{"," และ ",$columnNameList)));
		return $rtcolumnNameList;
	}
	/**
	* <p><pre>
	 *  construct "delete from.." SQL string for delete data of entity and send it to execute at libObject->doDbTransactions. 
	 * After execution is finished it put the message result into response 
	* </pre><p>		
	*/
	protected function deleteData()
	{
		if(!($this->libObject->insertUpdateAllowed($this->session['id']))){
			$this->notify('danger',"You're not authorized to insert, update or delete {$this->libExtraInfo['descriptions']}.");
			return;
		}
		
		//list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		$allLibExtraInfo = $this->getColumnOrdered()[1];
		//$idToDelete = $this->escapeSQL($_REQUEST['dataId']);
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
	* <p><pre>
	*  in case of specified specified [default] value in [addEditModal] in extraEntityInfo, this method apply expression to "value" of "insert" SQL string.
	*  there are two type of default, "sql" or "function", if default is "sql" then it return key field value of temp[0], otherwise it perform call_user_func and return the value.
	* 
	* </pre><p>
	* @param array defaultExpressions
	*	array of type of expression, for instance "sql::getdate()", getSession::IDNo
	* @return string 
	*/	
	private function makeSqlFromSpecifiedDefault($defaultExpressions){
		$temp = explode("::",$defaultExpressions);
		if($temp[0]=='sql'){
			return $temp[1];
		}else{
			return "'".call_user_func('self::'.$temp[0],$temp[1])."'";
		}
	}
	/**
	* <p><pre>
	*  extract field of filter row to perform search by using searchAttributes in extraEntityInfo[entityName][searchAttributes]
	* </pre><p>
	* @param array libDisplaySearchAttribute
	*	array of search attributes which specified in extraEntityInfo[entityName][searchAttributes]
	* @param string ordinal
	*	string of numeric, represent the order number in libDisplaySearchAttribute
	* @return array
	*	two element array [table name, column name]
	*/	
	protected function _getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $ordinal){
		$temp1 = explode(";;",$libDisplaySearchAttribute[(int)$ordinal]);
		$temp2 = explode("::",$temp1[0]);
		//ถ้ามีหลายฟิลด์ ที่เชื่อมกันมา ให้เอาฟิลด์สุดท้าย เช่น  devSubjects.name::devSubjectCourse.subjectId::scId เอา scId (ผิด2018-08-05)
		//หรือ devClassStatuses.descriptions::devClasses.statusId เอา devClasses.statusId(ผิด2018-08-05)
		// ที่ถูกคือ เอาตัวแรกนั่นแหละ เพราะมัน join กันอยู่แล้ว bug-id 20180805-01
		/*
		//ยกเลิก
		if(sizeof($temp2)>1){
			$index = sizeof($temp2) - 1;
		}else{
			$index = 0;
		}
		*/
		/*เอาใหม่*/
		$index = 0;

		//ระเบิดด้วย .
		$temp3 = explode(".",$temp2[$index]);

		//หากแตกออกเป็นสอง นั่นหมายถึงข้างหน้าคือชือตาราง
		if(sizeof($temp3)>1)
		{
			$returnTableAndColumName = explode(".",$temp2[$index]); //for example return devClasses.descriptions

			//ถ้า searchAtributes ของฟิลด์นั้น มีหลาย table ส่ง id กลับไป หาก table เดียว ส่ง ชื่อฟิลด์ของ table นั้นกลับไป(bug-id 20180805-01)
			$returnTableAndColumName[1] = (sizeof($temp2))>1?'id':$returnTableAndColumName[1];

			return $returnTableAndColumName;
		}
		else //หากไม่แตกออกเป็นสอง ส่ง '' กลับไปเพื่อให้ตัวที่เรียกมาไปเอา current library
		{
			return array('',$temp3[0]); //for example return devClasses.descriptions
		}
	}
	
	/**
	* <p><pre>
	*  create html of each input in extraEntityInfo[entityName][searchAttributes]
	* </pre><p>
	* @param array obj
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
		//ถ้าเป็นคอลัมน์ที่ต้องสร้างเป็น option โดยไปเอามาจาก table อื่น ให้ return _createSelectOptionFilter
		if(sizeof($fields)>1){
			return $this->_createSelectOptionFilter($filterOrdinal, $fields[0]);
		}

		$dataType = $this->_getColumnDataType($obj, $columnName);
		switch($dataType)
		{
			case 'int':
				$inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
			break;
			case 'char':
			case 'varchar':
			case 'text':
			case 'nchar':
			case 'nvarchar':
			case 'ntext':
				$maxLength = $this->_getColumnLength($obj, $columnName);
				$inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" maxlength=\"{$maxLength}\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
			break;
			case 'datetime':
			case 'date': //หากเป็น input date, หรือ datetime return function _inputDateItem เลย
				return $this->_inputDateItem($filterOrdinal);
			break;
				default: $inputItem = "<input cientityFormFilterOrder=\"{$filterOrdinal}\" type=\"text\" class=\"form-control floating cientityFilter\" />".PHP_EOL."";
		}
		$fromToStr = explode('_',$filterOrdinal);
		$additionalLabel=""; if(isset($fromToStr[1])){ if($fromToStr[1]=='from') {$additionalLabel='(from)';} elseif($fromToStr[1]=='to'){$additionalLabel='(to)';}}
		$str = "
						<div class=\"col-sm-3 col-xs-6\">".PHP_EOL."
							<div class=\"form-group form-focus\">".PHP_EOL."
								<label class=\"control-label\">#!#!#!#!#!#{$additionalLabel}</label>".PHP_EOL."
								{$inputItem}
							</div>".PHP_EOL."
						</div>".PHP_EOL."
			";
		return $str;
	}
	
	// ดึง option กรณีที่เป็น select input กรณีที่เป็น	
	/**
	* <p><pre>
	*  create html of each input in extraEntityInfo[entityName][searchAttributes] in case of referenced from other table
	* </pre><p>	
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
				//$infoForAjaxOptions = $this->_getInfoForAjaxOptions($obj, $entityName, $columnName, $filterOrdinal, $fields); //หาก option มากกว่า $maxSelectOptionShow ให้ใช้ datasource เป็น ajax
				$infoForAjaxOptions = $this->_getInfoForAjaxOptions($fields); //หาก option มากกว่า $maxSelectOptionShow ให้ใช้ datasource เป็น ajax
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
	
	/*
	* กรณีเป็น inputdate ให้ส่งกลับไป สอง input นั่นคือ จาก และ ถึง
	*/
	/**
	* <p><pre>
	*  create html of each input  in extraEntityInfo[entityName][searchAttributes] in case of its type is date
	* </pre><p>	
	* @param string filterOrdinal
	*	order number of column specified in extraEntityInfo[entityName][searchAttributes]	
	* @return string
	*	html of input of filter row
	*/	
	private function _inputDateItem($filterOrdinal)
	{
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
	* <p><pre>
	*  compose information for select2 to initialize after page is loaded. the information consists of entity order number, field order number, etc. 
	* (option สำหรับเอาไปสร้าง property ของ select2 ให้ดึง datasource มาจาก ajax)
	* </pre><p>	
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
	* ดึงข้อมูลของ column ออกมา
	*/
	/**
	* <p><pre>
	* fetch specified column informations
	*  </pre><p>	
	* @param object obj
	*	this entity information 
	* @param string columnName
	*	name of column you want to all info.
	* @return array
	*	
	*/
	private function _getColumnInfos($obj, $columnName)	{
		//เอา data type ออกมา
		foreach($obj->columnListInfo as $columnInfos){
			if($columnInfos['ColumnName']==$columnName){
				return $columnInfos;
			}
		}
		return [];
	}
	/**
	* ดึง dataType ของ column ออกมา เช่น varchar, char, int, datetime ฯลฯ
	*/
	/**
	* <p><pre>
	* return datatype of column such as varchar, char, int
	*  </pre><p>	
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
	* ดึง length ของ column ออกมา
	*/
	/**
	* <p><pre>
	*	return max-length of column 
	*  </pre><p>	
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
	* สร้างแต่ละ ปุ่มค้นใน filter row
	*/
	/**
	* <p><pre>
	*	construct html code of search button in filter row
	*  </pre><p>		
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
		คืนค่าลำดับที่ entity ใน array extraEntityInfos เพื่อเอาไว้ใช้งานในเรื่องต่างๆ
		หากยังไม่มีจะคืนค่า -1 (notFound)
	*/	
	/**
	* <p><pre>
	*	loop through this->_AllLibExtraInfo until found the specified libName
	*  </pre><p>
	* @param string libName
	* @return int
	*	ordinal position of specified libName in this->_AllLibExtraInfo series
	*/
	private function entityOrdinal($libName){
		$allLibInfo = $this->_AllLibExtraInfo();
		$entityOrdinal=0;
		$notFound = true;
		foreach($allLibInfo as $key=>$val)	{
			if($key==$libName){
				$notFound = false;
				break;
			}
			$entityOrdinal++;
		}
		return $notFound?(-1):$entityOrdinal;
	}

	/*
	* load library ที่เกี่ยวข้องเพื่อเอาไว้ใช้งาน
	*/
	/**
	* <p><pre>
	*	load library of entity in APPPATH/libraries/custom folder, this is important part of using cientity
	*  </pre><p>
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
	/*
	* load library extrainfo ของ libname เพื่อเอาไว้ใช้งาน
	*/
	/**
	* <p><pre>
	*	load extra information of specified entity of APPPATH/libraries/extraEntityInfos.php, this is important part of using cientity
	*  </pre><p>
	* @param string libName
	*	name of specified library
	* @return array
	*	In case of undefined extra entity info in extraEntityInfos.php it will return blank array.
	*/
	private function _libExtraInfo($libName){
		$this->CI->load->library('extraEntityInfos');
		return isset(extraEntityInfos::infos[$libName])?extraEntityInfos::infos[$libName]:[];
	}

	/*
	* load library extrainfo ของ ของทั้งหมด เพื่อเอาไว้ใช้งาน
	*/
	/**
	* <p><pre>
	*	fetch all library extra info 
	*  </pre><p>
	* @return array	
	*/
	private function _AllLibExtraInfo()
	{
		$this->CI->load->library('extraEntityInfos');
		return extraEntityInfos::infos;
	}
	 /**
	  * <p><pre>
	  * find description of field in columnDescriptionsColumnIndexed by exploded it with "||" and extract only first element of array
	  * </pre><p>
	  * @param object obj
	  *	object of entity which working on
	  * @param string columnName
	  *	name of column
	  * @return string
	  *	the description of field that use as label in add or edit form at front-end.
	 */	
	protected function _getFieldDescriptions($obj, $columnName)
	{
		//list ($entityName, $columnName) = explode(".",$fields);
		$tempData = explode("||",$obj->columnDescriptionsColumnIndexed[$columnName]['descriptions']);
		return $tempData[0];
	}

	/**
	* createFilterRow เพื่อสร้างแต่ละ item ใน filter row ในแต่ละหน้าของ entity
	*/
	 /**
	  * <p><pre>
	  * construct the filter row on first page of each entity at front end
	  * </pre><p>	 
	  * @return string
	  *	html string of filter row
	 */
	public function createFilterRow()	{
		//ถ้ายังไม่มีกำหนด searchAtributes ใน extraEntityInfos
		if( !(isset($this->libExtraInfo['searchAttributes']))){
			return str_replace('######',$this->libName,self::searchAttributesNotExist);
		}else{
			$searchAttributes = $this->libExtraInfo['searchAttributes'];
		}

		//ถ้ายังไม่มีกำหนด display ใน searchAtributes ใน extraEntityInfos
		if( !(isset($searchAttributes['display']))){
			return str_replace('######',$this->libName,selff::searchAttributesNotExist);
		}else{
			$searchAttributes = $this->libExtraInfo['searchAttributes'];
		}

		foreach($searchAttributes['display'] as $key=>$item){ //load entity ที่เกี่ยวข้องออกมาก่อน		
			$fieldInfo = explode(";;",$item);
			$fields = explode("::",$fieldInfo[0]);
			list($entityName, $columnName) = explode(".",$fields[0]);
			$entities[$entityName] = $this->_loadLibrary($entityName);
		}

		//var_dump($searchAttributes); exit;
		reset($searchAttributes['display']);
		$str = ""; $filterOrdinal=0;
		foreach($searchAttributes['display'] as $key=>$item){
			$fieldInfo = explode(";;",$item);
			$fields = explode("::",$fieldInfo[0]); //fieldInfo[0] คือ entity.column อันแรกสุดทีจะเอาไปสร้าง filter

			list($entityName, $columnName) = explode(".",$fields[0]);

			if(isset($searchAttributes['between'])){  //ถ้าระบุ between มา
				if(in_array($fields[0], $searchAttributes['between'])){ //ถ้าฟิลด์นั้นอยู่ใน between				
					$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal.'_from',$fields)
						 .$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal.'_to',$fields);
				}else{
					$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal,$fields);
				}
			}else{
				$cStr=$this->_eachFilter($entities[$entityName], $columnName,$filterOrdinal,$fields);
			}

			//ถ้ากำหนดชื่อเรียกมาเองจาก extraEntityInfos ให้เอาจาก extraEntityInfos
			$columnDescriptions = isset($fieldInfo[1]) ?$fieldInfo[1] :$this->_getFieldDescriptions($entities[$entityName],  $columnName) ;

			$replacedCStr = str_replace("#!#!#!#!#!#",$columnDescriptions,$cStr);

			$str.=$replacedCStr;

			$filterOrdinal++;
		}
		$str.=$this->_searchButton();

		return "
				<div class=\"row filter-row\">
					".$str."
				</div>
				";

	}
	 /**
	  * <p><pre>
	  * get option list for select2 which will be used at filter row or add/edit interface
	  * create sql string and of involved to "properties"-the information which tell this method that what is table and colum name should be query, and execute the sql string
	  * 
	  * </pre><p>	 
	  * @param string properties
	  *	The "properties" contains ordinal position of table and column name in allLibExtranInfo and is in the following format: i_j_k_l, i=order of entity, j= order of 'searchAttribute', k=order of 'display', l=order of filed should be query 
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
		$sql = str_replace("'{{%'","'%",$sql);
		$sql = str_replace("'%}}'","%'",$sql);
		//echo $sql; exit;
		$q = $this->CI->db->query($sql);
		$i = 0;
		$response['results'] = [];
		array_push($response['results'], ['id'=>'','text'=>'เลือกเพื่อค้น']);
		foreach($q->result() as $row)		{
			array_push($response['results'], ['id'=>$row->id,'text'=>$row->name]);
			$i++;
		}
		return $response;
	}
	 /**
	  * <p><pre>
	  * get ordering of column of entity which should be displayed in add/edit in main page of entity and in sub-entity
	  * if the key "columnOrdering" did not specified, this method will return table column ordinal.
	  * </pre><p>	 	  
	  * @return array [array columnsWithOrdered, array allLibExtraInfo, array columns]
	  *	the main purpose of this method is find columnsWithOrdered=column ordering, other return value, such as allLibExtraInfo, columns is junk because we can get these value in everywhere in this object
	  */
	protected function getColumnOrdered(){
		/**entity properties ที่เกี่ยวข้อง
		*	1. syncedColumnlistInfoWithRefKey list ของคอลัมน์ใน table และมีตัวบอก reference key
		*	2. revisedColumnDescriptions เป็นคำอธิบายของแต่ละคอลัมน์
		*	3. stdValidationRules
		*/
		$tempColumns = $this->libObject->syncedColumnlistInfoWithRefKey;
		$columns = [];
		foreach($tempColumns as $key => $val){  //จัด syncedColumnlistInfoWithRefKey ใหม่ โดยให้ชื่อคอลัมน์เป็นคีย์		
			$newKey = $val['ColumnName'];
			unset($val['ColumnName']);
			$columns[$newKey] = $val;
		}

		$allLibExtraInfo = $this->_AllLibExtraInfo();
		if (isset($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'])){ //หากระบุการเรียงคอลัมน์ในหน้า addEditModal มาใหม่		
			$columnsWithOrdered = [];
			foreach($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'] as $key=>$val){
				$columnsWithOrdered[$val] = $columns[$val];
			}
		}else{
			$columnsWithOrdered = $columns;
		}
		
			
		
		return [$columnsWithOrdered, $allLibExtraInfo, $columns];
	}
	 /**
	  * <p><pre>
	  * get ordering of column of entity which should be displayed in add/edit in main page of entity and in sub-entity
	  * if the key "columnOrdering" did not specified, this method will return table column ordinal.
	  * the difference between getColumnOrdered() and p_getColumnOrdered() is protected and public respectively.
	  * </pre><p>	 
	  * 
	  * @return array [array columnsWithOrdered, array allLibExtraInfo, array columns]
	  */
	public function p_getColumnOrdered()
	{
		return $this->getColumnOrdered();
	}
	 /**
	  * <p><pre>
	  * compose the front-end add/edit modal. if also compose html of submodal(this should call sub-entity) is the submodal information is specified.
	  * </pre><p>	 
	  * 
	  * @return string 
	  *	front-end html for construct add/edit modal.
	  */
	public function createAddEditModal(){		
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		$eachInputItem="";
		foreach($columnsWithOrdered as $key => $column){
			if (isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){ //หากเป็น column ที่ hidden ไม่ต้องแสดงออกมา
				if(array_search($key,$allLibExtraInfo[$this->libName]['addEditModal']['hidden'])!==false){
					continue;
				}
			}
			$eachInputItem.=$this->createEachInputForModal($allLibExtraInfo,$key,$column,$columns);
		}
		$modalHtml = str_replace("___i#n#p#u#t#_#I#t#e#m#s#__",$eachInputItem,$this->addEditModalWrapper((isset($allLibExtraInfo[$this->libName]['descriptions'])?$allLibExtraInfo[$this->libName]['descriptions']:"_".$this->libName)));
		$subModalHtml = $this->getSubEntityModalNavsAndEntityPanel($allLibExtraInfo);
		$allModalHtml = str_replace('#h#r#d#s##s#u#b#e#n#t#i#t#y#m#o#d#a#l#',$subModalHtml,$modalHtml);
		return $allModalHtml;
	}
	 /**
	  * <p><pre>
	  * compose the front-end add/edit sub-entity. this wil displayed in nav bars under edit area of main modal
	  * </pre><p>	 
	  * 
	  * @return string 
	  *	front-end html for construct add/edit sub-entity.
	  */
	public function createAddEditSubModal($suppressedFieldsInAdd){
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();
		$eachInputItem="";
		foreach($columnsWithOrdered as $key => $column)
		{
			if (isset($allLibExtraInfo[$this->libName]['addEditModal']['hidden'])){ //หากเป็น column ที่ hidden ไม่ต้องแสดงออกมา			
				if(array_search($key,$allLibExtraInfo[$this->libName]['addEditModal']['hidden'])!==false){
					continue;
				}
			}
			if(in_array($key,$suppressedFieldsInAdd)){ //ถ้าเป็นฟิลด์ที่ระบุมาว่าไม่ต้องแสดงออกมา
				continue;
			}
			$eachInputItem.=$this->createEachInputForModal($allLibExtraInfo,$key,$column,$columns);
		}
		$modalHtml = str_replace("___i#n#p#u#t#_#I#t#e#m#s#__",$eachInputItem,$this->addEditSubModalWrapper());
		return $modalHtml;
	}

	/**
	* @function createEachInputForModal
	* @description สร้าง input แต่ละ column เพื่อใช้ใน addEditModal
	* @parameters $allLibExtraInfo,$key,$column
	*/
	/**
	 * <p><pre>
	 * create input for each column for use in addEditModal
	 * </pre><p>	 
	 * @param array allLibExtraInfo
	 *	all extra entity info
	 * @param int key
	 *	ordinal position of "columnWidth" which use to specified column
	 * @param string column
	 *	name of column that html of input will be constructed 
	 * @param array columns
	 *	all column info, such as, datatype, is_primary, maxLength, etc.
	 * @return string 	 *	
	 *	front-end html for input of each column
	 */
	private function createEachInputForModal($allLibExtraInfo,$key,$column,$columns)
	{
		if(!(isset($allLibExtraInfo[$this->libName]))){
			return str_replace('#####',$this->libName,self::notFoundLibExtraInfoKey);
		}
		$columnWitdth = isset($allLibExtraInfo[$this->libName]['addEditModal']['columnWidth'][$key])?$allLibExtraInfo[$this->libName]['addEditModal']['columnWidth'][$key]:6;
		$requiredIndicator = "";

		if($column['is_nullable']==0){ //ถ้าไม่อนุญาต null ต้องกรอก		
			$requiredIndicator="<span class=\"text-danger\">*</span>";
		}
		$disabledString="";
		if($key=='id'){
			$disabledString = "disabled";
		}
		$inputString="<span class=\"text-danger\">ไม่สามารถดึงชนิด input ได้</span>";
		$fieldReferenceNumber = $this->_getReferenceNumber($key,$allLibExtraInfo);
		if(isset($column['references'])){ //หาก column นั้น มี reference key จาก table อื่น
			$select2Info=$this->_getSelect2Info($key,$allLibExtraInfo[$this->libName]);
			$inputString="<select {$disabledString} class=\"form-control cientityInputField cientitySelectFromReference\" tabindex=\"-1\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' {$select2Info}></select>";
		}
		//ถึงแม้จะไม่ได้ระบุว่า มี reference ใน syncedColumnlistInfoWithRefKey แต่ระบุ reference มาใน extraEntityInfos[$libName][addEditModal][reference] เช่น 'empIDNo'=>'devEmployees.IDNoAndFullName' ก็ให้สร้าง select2 ด้วย
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
				case 'char':
				case 'varchar':

					$maxLength = $columns[$key]['MaxLength'];
					$inputString = "<input {$disabledString} maxLength='{$maxLength}' class=\"form-control cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
				break;
				case 'datetime':
				case 'date':
					$inputString = "<input {$disabledString} class=\"form-control datetimepicker cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
				break;

					default: $inputString = "<input {$disabledString} class=\"form-control cientityInputField\" cientityfieldReferenceNumber='{$fieldReferenceNumber}' type=\"text\" />";
			}
			
		}

		//ถ้ากำหนด fieldLabels มาจาก extraEntityInfos
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
	* @function _getReferenceNumber
	* @description คืนค่า index ของ column ใน columnOrdering ใน  addEditModal ใน extraEntityInfos หรือหากยังไม่ก็ำหนดก็จะไปมองหาใน syncedColumnlistInfoWithRefKey เพื่อเอาไปใช้สำหรับอ้างอิงในฟอร์มตอนส่งค่าไปบันทึกหรือแก้ไข
	* @parameters $columName ชื่อของคอลัมน์, @allLibExtraInfo คือ extraEntityInfos::info
	*/
	/**
	 * <p><pre>
	 * looking for ordnal position of column in 'columnOrdering' in 'addEditModal' in libraryInfo of current entity. 
	 * In case of 'columnOrdering' in 'addEditModal' in libraryInfo did not specified it will looking for key in syncedColumnlistInfoWithRefKey
	 * </pre><p>	 
	 * @param string columnName
	 *	name for specified column
	 * @param array allLibExtraInfo
	 *	all library information	 
	 * @return int
	 *	
	 */
	private function _getReferenceNumber($columName, $allLibExtraInfo){	
		if (isset($allLibExtraInfo[$this->libName]['addEditModal']['columnOrdering'])){ //หากระบุการเรียงคอลัมน์ในหน้า addEditModal มา
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
	* @function _getSelect2Info
	* @description คืนค่า infomation เพื่อเอาไว้ให้ select2 ใช้สำหรับ ดึงค่ามาเป็น option ผ่าน ajax
	* @parameters $columnName ชื่อของคอลัมน์, @libInfos คือ information ของ entity นั้นๆ จาก extraEntityInfos::info
	*/
	/**
	 * <p><pre>
	 *	construct the string of information for select2 initialization in case of that input fileld use dropdown
	 * </pre><p>	 
	 * @param string columnName
	 *	name for specified column
	 * @param array libInfo
	 *	information	of current library(entity)
	 * @return string
	 *	information of ajax url that select2 must use to initialized, in case of libInfos['addEditModal']['references'][$columnName] doesn't exists it will return '' 
	 */
	protected function _getSelect2Info($columnName, $libInfos)
	{
		if(!(isset($libInfos['addEditModal']['references'][$columnName])))
		{
			return '';
		}
		else
		{
			return $this->_getInfoForAjaxOptionsInAddEditModal($columnName);
		}
	}

	/**
	* @function _getInfoForAjaxOptionsInAddEditModel
	* @description คืนค่า ตำแหน่งของ properties ต่างๆใน extraEntityInfos เพื่อเอาไปใช้ เป็นคุณสมบัติที่ชื่อ infoForAjaxOptions ในแต่ละ input item ในหน้า addEditModal ทั้งนี้เพื่อป้องกันไม่ให้ user เห็นชื่อฟิลด์และtable name ตอนใช้งานจริง
	* @parameters $columnName ชื่อของคอลัมน์ หรือชื่อฟิลด์นั้นๆ
	*/
	/**
	 * <p><pre>
	 *	construct the string of information for select2 initialization in case of that input fileld use dropdown
	 * </pre><p>	 
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
	* @function infoForAjaxAddEditModalOptions
	* @description คืนค่า ชื่อ table เพื่อเอาไปทำการสร้าง sql สำหรับ select2 ใน input item ในหน้า addEditModal หรือพูดอีกนัยหนึ่งว่า ทำงานย้อนกลับกับ method _getInfoForAjaxOptionsInAddEditModel
	* @parameters $properties คือตัวอ้างถึง table และชื่อฟิลด์, $conditions คือเงื่อนไขในการค้นหา
	*/
	/**
	 * <p><pre>
	 * get a table name and column which will be use to construct sql string for retreiving data and sent to select2 in front-end
	 * , in the oher hand it reverses the _getInfoForAjaxOptionsInAddEditModel method.
	 * </pre><p>	 
	 * @param string properties
	 *	properties is ordinal position information, table and column name, of key that will be fetched to construct sql 
	 * @param string condition 
	 *	user search keyword
	 * @return array
	 *	result of selected from constructed sql string 
	 */
	public function infoForAjaxAddEditModalOptions($properties, $conditions)
	{
		$property = explode("_", $properties);
		foreach($property as $key => $val)
		{
			$loopCompare[$key] = (int) $val;
		}
		$allLibExtraInfo = $this->_AllLibExtraInfo();
		$i=0;
		foreach($allLibExtraInfo as $key1=>$val1)
		{
			if($i==$loopCompare[0]){
				$j=0;
				foreach($val1 as $key2=>$items)
				{
					if($j==$loopCompare[1])
					{
						$k=0;
						foreach($items as $key3=>$itemss)
						{
							if($k==$loopCompare[2])
							{
								$l = 0;
								foreach($itemss as $key4=>$itemssss)
								{

									if($l==$loopCompare[3])
									{
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
		$sql = "select top ".($this->maxSelectOptionShow)." id, {$columnName} name from {$this->CI->db->dbprefix}{$tableName} where {$columnName} like '{{%".$this->CI->db->escape($conditions)."%}}' order  by id desc";
		$sql = str_replace("'{{%'","'%",$sql);
		$sql = str_replace("'%}}'","%'",$sql);
		//echo $sql; exit;
		$q = $this->CI->db->query($sql);
		$i = 0;
		$response['results'] = [];
		array_push($response['results'], ['id'=>'','text'=>'select..']);
		foreach($q->result() as $row)		{
			array_push($response['results'], ['id'=>$row->id,'text'=>$row->name]);
			$i++;
		}
		return $response;
	}
	/**
	* @function addEditModalWrapper
	* @description ส่งคือ wrapper ของ addEditModal หรือพูดง่ายๆว่า ส่งคืน modal addEditModal นั่นเอง
	* @parameters $entityDescriptions=คำอธิบาย หรือชือภาษาไทยของ entity นั้นๆ
	*/
	/**
	 * <p><pre>	 
	 * create html string of modal wrapper of each entity.
	 * the string "___i#n#p#u#t#_#I#t#e#m#s#__" will be replaced by inputs html
	 * the string "#h#r#d#s##s#u#b#e#n#t#i#t#y#m#o#d#a#l#" will be replaced by html of sub-entity 
	 * </pre><p>	 
	 * @param string entityDescriptions
	 *	the "descriptions" key of entity in array at file extraEntityInformation.php.
	 * @return string
	 *	html string of modal wrapper for front-end of eahc entity
	 */
	private function addEditModalWrapper($entityDescriptions){
		/** หา cientityEntRefNumber เพื่อเอาไปใช้อ้างอิงในการส่งค่าไป insert หรือ edit*/		
		$cientityEntRefNumber= $this->entityOrdinal($this->libName);
		//  style=\"height:2200px;\"
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
	 * <p><pre>	 
	 * create html string of sub-modal(sub-entity) wrapper of each entity.
	 * the string "___i#n#p#u#t#_#I#t#e#m#s#__" will be replaced by inputs html of sub-entity	 
	 * </pre><p>	 
	 * @param string entityDescriptions
	 *	the "descriptions" key of entity in array at file extraEntityInformation.php.
	 * @return string
	 *	html string of modal wrapper for front-end of eahc entity
	 */
	private function addEditSubModalWrapper(){
		/** หา cientityEntRefNumber เพื่อเอาไปใช้อ้างอิงในการส่งค่าไป insert หรือ edit*/		
		$cientityEntRefNumber= $this->entityOrdinal($this->libName);
		//  style=\"height:2200px;\"
		return "
							<!--form!-->
								<div class=\"row\">
								___i#n#p#u#t#_#I#t#e#m#s#__
								</div>
								<div class=\"row\">
									<div class=\"m-t-20 text-center\">
										<input type='hidden' class='cientityOperationForAddEditModalInsubModal' value='1' />
										<button entityOrdinal='{$cientityEntRefNumber}' class=\"btn btn-primary cientitySubEntityConfirmAddButton\">Add</button>
									</div>
								</div>
							<!--/form!-->
		";
	}
	/**
	 * <p><pre>	 
	 * create nav bars and sub-entity panels	 
	 * </pre><p>	 
	 * @param array allLibExtraInfo
	 *	all library extra information 
	 * @return string
	 *	html string of nav-bars and panels of sub-entity
	 */
	private function getSubEntityModalNavsAndEntityPanel($allLibExtraInfo){
		$nav = '';
		$panel = '';
		if(isset($allLibExtraInfo[$this->libName]['addEditModal']['subModal']))
		{
			$subModals = $allLibExtraInfo[$this->libName]['addEditModal']['subModal'];
			$firstTime = true;
			foreach($subModals as $entityName => $entityInfos){
				$label = (isset($entityInfos['label'])) //หากระบุ label มาแล้ว เอา label ที่ระบุมา
				?$entityInfos['label']
				:(isset($allLibExtraInfo[$entityName]['descriptions'])?$allLibExtraInfo[$entityName]['descriptions']:"?".$entityName); //หากไม่ได้ระบุมาให้ไปเอา descriptions ของ entity นั้น

				$subModalcientityForms = new mainForms($entityName);
				$suppressedFieldsInAdd = array();
				if(isset($entityInfos['suppressedFieldsInAdd']))	{
					$suppressedFieldsInAdd = $entityInfos['suppressedFieldsInAdd'];
				}
				$eachInputForSubModal = $subModalcientityForms->createAddEditSubModal($suppressedFieldsInAdd);

				$eachInputForSubModal = str_replace("cientityInputField","cientityInputFieldForSubModal",$eachInputForSubModal);
				$eachInputForSubModal = str_replace("cientitySelectFromReference","cientitySelectFromReferenceForSubModal",$eachInputForSubModal);
				$entityOrdinal = $this->entityOrdinal($entityName);
				// style='height:500px;'
				$nav.="<li role=\"presentation\" class=\"".($firstTime?"active":"")."\"  cientitySubEntityModalPanelId='{$entityOrdinal}'><a href=\"#\">{$label}</a></li>";
				$panel.="
				<div class=\"panel panel-default cientitySubmodelPanel ".($firstTime?"":"hide")."\"  cientitySubEntityModalPanelId='{$entityOrdinal}'>
					<div class=\"panel-body \">
						 <div class='row'>
							<div class='col-xs-3'><strong>{$label}</strong></div>
							<div class='col-xs-8'>
								<span class='pull-right'>
									<button type=\"button\" class=\"btn btn-sm btn-secondary cientityShowAddEditSubmodalPanel\" cientitySubEntityModalPanelId='{$entityOrdinal}' role='button'>
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
										{$eachInputForSubModal}
									</div>
								</div>
							</div>
						 </div>
						 <div class=\"row searchProgressBarRowSubModel hide\" cientitySubEntityModalPanelId='{$entityOrdinal}'>
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
		/*<table class='table subModalDataTable table-striped' cientitySubEntityModalPanelId='{$entityOrdinal}'></table>*/
	}
	/**
	 * <p><pre>	 
	 *	define the stand dard response for ajax
	 * </pre><p>	 
	 * @return array 
	 *	array of standard response
	 */
	protected function stdResponseFormat()
	{
		return ['converted' => [],'fields'=>[],'notifications' => ['info' => [],'warning'=>[],'danger'=>[],'success'=>[]],'references'=>[]];
	}
	/**
	 * <p><pre>	 
	 *	put notification message to display for end-users
	 * </pre><p>	 
	 * @param string notificationType
	 *	type of notification, such as success, info, danger, warning.	 
	 * @param string msg
	 *	message to end-user
	 */
	protected function notify($notificationsType,$msg)	{
		if(!(isset($this->response['notifications'][$notificationsType]))){
			array_push($this->response['notifications']['danger'], "Program error, notification type {$notificationsType} is not defined.");
		}else{
			// ควร เช็คว่า หากมี notification เยอะเกินไป ให้เหลือไว้เฉพาะ danger และไม่เกิน 5 notifications/*ค่อยทำ*/
			array_push($this->response['notifications'][$notificationsType], $msg);
		}
	}

	/**
	@function recordExists
	@descriptions  เอาไว้ตรวจสอบว่ามี ข้อมูลตามที่ post ใน table หรือเปล่า, ใช้ตอน validate is_unique[ชื่อตาราง.ฟิลด์]  (ไม่ค่อยดีเท่าไหร่ เพราะจะเป็นการเช็คซ้ำซ้อน) พยายามใช้ callback ของ codeIgniter แล้วแต่ไม่ work
	@parameter
	*/
	/**
	 * @deprecated since version 1.0.0
	 * <p><pre>	 
	 *	to ensure that the data of submitted do doesn't already exists, this will be applied with unique column only
	 * </pre><p>	 
	 * @param string rule
	 *	
	 * @param string msg
	 *	message to end-user
	 */
	public function recordExistsInOther($rule,$request)
	{
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
	 * <p><pre>	 
	 *	escaspe for sql server for prevent sql injection
	 * </pre><p>	 
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
		//while (list($key, $val) = each($arr)) {
		foreach($arr as $key=>$val)
		{
			//echo $key."===";
			if (is_array($val)){
				return $this->escapeArray($val);
			}else{
				$newArray[$key] = is_string($val)?str_replace("'","''", $val):$val;
			}
		}
		//echo "####";
		return $newArray;
	}
	/**	 
	 * <p><pre>	 
	 *	converted date from dd/mm/yyyy to yyyy/mm/dd format, and also convert Buddist to Christ. In case of sperator is "-" it also converted to "/"
	 * </pre><p>	 
	 * @param string date
	 *	date in dd/mm/yyyy format
	 * @param array 
	 *	array of string ['yyyy','mm','dd]
	 */
	protected function splitAndConvertDate($datetime)	{
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
	 * <p><pre>	 
	 *	convert year from buddist to christ, if the differences of  "year" and current year is greater than 400 then year-543
	 * </pre><p>	 
	 * @param string year
	 *	year
	 * @return int
	 *	converted year
	 */
	private function toBCYear($year)	{
		//ถ้าปี มากว่า ปีปัจจุบันอยู่ 400 แสดงว่าเป็นพศ. ให้ลบออกก่อน(คงไม่มีเงื่อนไขอะไรที่ปีน้อยไปกว่า 400 ปี)
		if(((int)$year)-((int)date("Y"))>400){
			$year = ((int) $year) - 543;
		}
		return $year;
	}
	
	// the following are methods that will be called by call_user_func	
	private function _user_func_getSession($arg){
		return $this->session['IDNo'];
	}		
	/**	 
	 * <p><pre>	 
	 *	get column datatype for compose input tag in datatble of subentity 
	 * </pre><p>	 
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
}
