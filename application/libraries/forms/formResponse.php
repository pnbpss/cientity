<?php
require_once(APPPATH.'libraries/forms/mainForms.php');
class formResponse extends mainForms {
	private $entityOrdinal;
	public $_REQUEST;
	function __construct($request)
	{				
		$_allLibInfo = extraEntityInfos::infos;		
		$this->entityOrdinal=(int) $request['entityOrdinal'];
		
		$this->_REQUEST=$this->escapeSQL($request);		
		$this->_REQUESTM = $this->escapeSQL($request);
		
		$index=0;
		foreach(array_keys($_allLibInfo) as $key)
		{
			if($index==$this->entityOrdinal){
				$_libName = $key;
				break;
			}
			$index++;
		}				
		parent::__construct($_libName);
		
		$this->libObject->_REQUESTE = $this->_REQUEST;
	}
	/**
	 * Compose SQL string for search and send back to front end. there are two search case, first, search from filter row, second search from sub-entity
	 * @param subModalInfo array
	 *	incase of search from subModal(sub-entity) this variable will be contains an information of subModal(sub-entity)
	 * @return object 
	 *	object of search result from executed composed SQL string
	 */
	function _setSession($sessionData){
		$this->session = $sessionData;
		$this->libObject->_saveSessionData($this->session);
	}
	function searchResults($subModalInfo=[]){		
		$libInfos = $this->libExtraInfo;
		$libDisplaySearchAttribute = $libInfos['searchAttributes']['display'];
		$libHiddenSearchAttribute = isset($libInfos['searchAttributes']['hidden'])?$libInfos['searchAttributes']['hidden']:[];		
				
		$request = $this->_REQUEST;
		
		//remove ordinal because it isn't need to create SQL
		unset($request['entityOrdinal']); 
		$response = (Object) null;
		$parameters = (Object) null;
		
		//create select clause
		$sqlSelect = $this->createSelectFields($libInfos['selectAttributes']);
		//if(CODING_ENVIROMENT=='develop') $response->sql['select']  = $sqlSelect; //for view at response tab in debuging
		$parameters->sql['select'] = $sqlSelect;
		
		//create from clause and join clause
		$libInfos['join'] = (isset($libInfos['join']))?$libInfos['join']:[];
		$sqlJoin = $this->createJoin($libInfos['join'],$this->libName);
		//if(CODING_ENVIROMENT=='develop') $response->sql['join']  = $sqlJoin; //for view at response tab in debuging
		$parameters->sql['join'] = $sqlJoin;
		
		//create where clause		
		//if search conditions submited from subModal
		if(isset($subModalInfo['subModal'][$this->libName])){
			//return ['idValue'=>$valueToReturn, 'subModal'=>$this->libExtraInfo['addEditModal']['subModal']];
			$idValue = $this->escapeSQL($subModalInfo['idValue']);
			$temp = explode(".", $subModalInfo['subModal'][$this->libName]['alterView']);
			$fieldName=$temp[1];
			$sqlCondition = " where 1=1 and {$this->CI->db->dbprefix}{$this->libName}.{$fieldName}='{$idValue}' ";			
			
		}else{ //if search condition submited from filter row in mainModal
			$sqlCondition = " where 1=1 ".$this->createWhereConditions($request,$libDisplaySearchAttribute, $libHiddenSearchAttribute);
		}		
				
		if(method_exists($this->libName,'additionalWhereInFilterRow')){
			$sqlCondition.=$this->libObject->additionalWhereInFilterRow();
		}
		
		//if(CODING_ENVIROMENT=='develop') $response->sql['condition'] = $sqlCondition; //for view at response tab in debuging
		$parameters->sql['condition'] = $sqlCondition;
		
		//create column header row for search result table
		$headerArray = $this->getSelectListColumnDescriptions();
		
		$response->results = $this->_getResultFromSQL($parameters->sql,$headerArray,$subModalInfo);
		
		return $response;
	}
	/**	 
	 * execute SQL string which composed in this->searchResults and return search result in HTML format to front-end.
	 * @param array sqlObj 
	 *	consists of SQL string select, join and condition
	 * @param array headerArray
	 *	array of header informations which constructed in this->getSelectListColumnDescriptions
	 * @param array subModalInfo
	 *	incase of search from subModal(sub-entity) this variable will be contains an information of subModal(sub-entity)
	 * @return string 
	 *	HTML string of table, which will be use as dataTable
	 */
	private function _getResultFromSQL($sqlObj,$headerArray,$subModalInfo=[]){
		$sqlStr = $sqlObj['select'].$sqlObj['join'].$sqlObj['condition'];		
		$q = $this->CI->db->query($sqlStr);
		$tableRow="";
		foreach($q->result() as $row){
			$rowArray = (array)$row;
			$tableData="";
			foreach($rowArray as $key=>$val)	{
				//if key is dataId, 
				if($key=='CIEntityDataId'){
					continue;
				}
				if((is_float($val)) || (is_int($val))){
				 $textAlign='text-right';
				}else{
					$textAlign='';
				}
				$display = $this->_getTdTableData($key, $val,$subModalInfo);					
				$tableData .= "<td class='{$textAlign}'>{$display}</td>";
			}
			$actionTd="<td class=\"text-right\">
						<div class=\"dropdown\">
							<a href=\"#\" class=\"action-icon dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\"><i class=\"fa fa-ellipsis-v\"></i></a>
							<ul class=\"dropdown-menu pull-right\">
								<li class='cientityEditExistingEntityRecord' cientityEntityReference='{$this->entityOrdinal}' cientityDataId='{$row->CIEntityDataId}' data-toggle=\"modal\" data-target=\"#cientityAddEditModal\" data-backdrop=\"static\" data-keyboard=\"false\"><a href=\"#\"><i class=\"fa fa-pencil m-r-5\"></i> view or update</a></li>
								<li class='cientityDeleteExistingEntityRecord' cientityEntityReference='{$this->entityOrdinal}' cientityDataId='{$row->CIEntityDataId}'><a href=\"#\" data-toggle=\"modal\" data-target=\"#cientityDeleteModal\"><i class=\"fa fa-trash-o m-r-5\"></i> delete</a></li>
							</ul>
						</div>
					</td>";
			
			$actionTdForSubModal="<td class=\"text-center\" name='cientitydataTablesActionSubModule' style='whitespace:nowrap'>													
													<input type='checkbox' class='form-control input-group-addon cientitySelectToActionSubentity' cientityEntityReference='{$this->entityOrdinal}' cientityDataId='{$row->CIEntityDataId}'>												
											</td>";
			
			
			if(isset($subModalInfo['subModal'])) {				
				if(isset($subModalInfo['subModal'][$this->libName]['editable'])){
					if($subModalInfo['subModal'][$this->libName]['editable']){
						$tableData.=$actionTdForSubModal;
					}else{
						$tableData.="";
					}
				}else{
					$tableData.="";
					$subModalInfo['subModal'][$this->libName]['editable'] = false;
				}
			}
			else{
				$tableData.=$actionTd;				
			}			
			$tableRow .= "<tr cientityDataIdRow='{$row->CIEntityDataId}_{$this->entityOrdinal}'>".$tableData."</tr>";
		}
		//not found
		if($tableRow==""){ 
			$table = "<table  class=\"table table-striped custom-table datatable\">";
			$table.="<tbody><tr><td><div class=\"alert alert-warning\" role=\"alert\">search result not found.</div></td></tr></tbody";
			$table.="</table>";
			return $table;
		}
		//fetch column header
		reset($rowArray);
		$tableHead="<thead>";
		$colIndex=0;
		foreach($rowArray as $key=>$val)	{
			if($key=='CIEntityDataId'){
				continue;
			}
			$tableHead .= "<th>{$headerArray[$colIndex]}</th>";
			$colIndex++;
		}
		if(isset($subModalInfo['subModal'][$this->libName]['editable'])) {
			if($subModalInfo['subModal'][$this->libName]['editable']){
				$tableHead .= "<th>All <input type='checkbox' class='cientityAction cientitySelectToggleAll' cientityEntityReference='{$this->entityOrdinal}'></th>";
			}else{
				$tableHead .= "";
			}
			$datableClass='cientitysubModalDatatable';			
			$subEntityOrdinalInfo = "cientitySubEntityModalPanelId='{$this->_REQUEST['entityOrdinal']}'";
		}else{
			$tableHead .= "<th>action</th>";
			$datableClass='';
			$subEntityOrdinalInfo="";
		}
		$tableHead .="</thead>";
		
		$table = "<table  class=\"table table-striped custom-table datatable {$datableClass}\" {$subEntityOrdinalInfo}>";
		$table.=$tableHead;
		$table.="<tbody>".$tableRow."</tbody";
		$table.="</table>";
		return $table;
	}
	/**	 
	 *	distinguish datatype between date and datetime and time and return format for datepicker
	 * @param string dataType
	 *	data type
	 * @return string
	 *	format of datetime picker
	 */
	private function convertdtFunction($dataType){
		switch($dataType){
			case 'date': return ['datetimepicker',"cientityDTFormat='DD/MM/YYYY'"]; 
			case 'datetime': return ['datetimepicker',"cientityDTFormat='DD/MM/YYYY LT'"];
			case 'time': return ['datetimepicker',"cientityDTFormat='LT'"]; 
			default: return ['',''];
		}
	}
	/**	 
	 *	compose td of each search result 
	 * @param string key
	 *	display field name key 
	 * @return string
	 *	็HTML of TD
	 */
	private function _getTdTableData($key, $val,$subModalInfo){		
		$libInfos = $this->libExtraInfo;		
		if((isset($libInfos['selectAttributes']['editableInSubEntity'][$key])) && (isset($subModalInfo['subModal']))){ //if editable data in td, and called from display in submodal
			$cientityKeyReference = $this->_referenceKeyForSubModalEditTable($libInfos['selectAttributes']['editableInSubEntity'], $key);
			 //if it is input text
			if($key==$libInfos['selectAttributes']['editableInSubEntity'][$key]){
				//dont forget date or datetime input				
				$dataType = $this->_getColumnDataTypeDirectlyForSubEntityRowInput($key, $libInfos);
				if(in_array($dataType,['date','datetime','time'])){
					$datetimeInputInfo = $this->convertdtFunction($dataType);					
				}else{					
					$datetimeInputInfo = ['',''];
				}
				return "<input  type='text' class='form-control input-sm cientitySubModalEditTd {$datetimeInputInfo[0]}' {$datetimeInputInfo[1]}  cientityKeyReference='{$cientityKeyReference}' value='{$val}' cientityRollbackValue=\"{$val}\" />";
			//if not input text then create select2	
			}else{		
				return $this->_inputSelectForSubEntity($key,$val,$cientityKeyReference);
			}
		}else{
			return $val;
		}
	}
	/**	 
	 * compose select input for sub-entity, get value from text
	 * @param string key
	 *	display field name key 
	 * @param string text
	 *	text for option for display and also use for select id from references table
	 * @param string cientityKeyReference 
	 *	key of column array in "editableInSubEntity"
	 * @return string
	 *	html of "select" 
	 */
	private function _inputSelectForSubEntity($key,$text,$cientityKeyReference){		
		$linkInfo = $this->libExtraInfo['selectAttributes']['editableInSubEntity'][$key];			
		
		$linkKey = explode("::",$linkInfo);
		$ajaxInputOption = $this->_getSelect2Info($linkKey[1],$this->libExtraInfo);
		
		//get value of current text
		$referenceInfo = $this->libExtraInfo['addEditModal']['references'][$linkKey[1]];
		list($tableName,$fieldName) = explode(".",$referenceInfo);
		$sql = "select id from {$this->CI->db->dbprefix}{$tableName} where {$fieldName}='{$text}' ";
		//var_dump($sql);
		$q = $this->CI->db->query($sql);
		$row = $q->row();
		
		$html = "<select class='form-control input-sm cientitySubModalSelectTd' cientityKeyReference='{$cientityKeyReference}' {$ajaxInputOption}><option value='{$row->id}'>{$text}</select>";
		
		return $html;
	}
	/**	 
	 * looking for ordinal number of element in $libInfos['selectAttributes']['editableInSubEntity'][$key]
	 * @param array editableForSubModal
	 *	array of editableForSubModal info
	 * @param string key
	 *	key of $libInfos['selectAttributes']['editableInSubEntity'][$key]
	 * @return string
	 *	html of td
	 */
	private function _referenceKeyForSubModalEditTable($editableForSubModal, $key){		
		$loop_1 = 0;
		foreach($editableForSubModal as $key1 =>$val1){
			if($key1==$key){
				return $loop_1;
			}
			$loop_1++;
		}
		return null;
	}
	/**	 
	 *	compose fields of select for select clause, 
	 * @param array selectAttributes
	 *	selectAttributes is array in extra entity info in extraEntityInfos.php 
	 * @return string
	 *	"select" clause, for example "select a,b,c,d"
	 */
	private function createSelectFields($selectAttributes){
		$fields = $selectAttributes['fields'];
		$selectAttributesStr="dummyColumnNameBpspanuOntherock";
		$selectAttributesStr.=",{$this->CI->db->dbprefix}{$this->libName}.id CIEntityDataId";
		foreach ($fields as $val){
			if(is_array($val)){
				foreach($val as $val2){
					$temp = explode(';;',$val2);
					//if format is specified in extraEntityInfo
					if(isset($selectAttributes['format'][$temp[0]])){ 
						$selectAttributesStr .= ", ".str_replace(FRPLCEMNT4FMT,$this->CI->db->dbprefix.$temp[0], $selectAttributes['format'][$temp[0]]);
					}else{
						$selectAttributesStr .= ", {$this->CI->db->dbprefix}{$temp[0]}";
					}
				}
			}else{				
				$temp = explode(';;',$val);				
				//if format is specified in extraEntityInfo
				if(isset($selectAttributes['format'][$temp[0]])){ 
					$selectAttributesStr .= ", ".str_replace(FRPLCEMNT4FMT,$this->CI->db->dbprefix.$temp[0], $selectAttributes['format'][$temp[0]]);
				}else{
					$selectAttributesStr .= ", {$this->CI->db->dbprefix}{$temp[0]}";
				}
			}			
		}
		return str_replace('dummyColumnNameBpspanuOntherock,','',("select ".$selectAttributesStr));		
	}
	/**	 
	 * get column descriptions, header of table for filter row search result or sub-entity search result
	 * @param string libraryName
	 *	library name of current library(entity)
	 * @return array
	 *	array of column descriptions which will be used as table header.
	 */
	protected function getSelectListColumnDescriptions(){
		//$selectFields = extraEntityInfos::infos[$libraryName]['selectAttributes']['fields']; //bugId 20180808-01
		$selectFields = $this->libExtraInfo['selectAttributes']['fields']; //solved bugId 20180808-01				
		$returnArray = [];
		//loop for each field in fields 
		foreach($selectFields as $item){ 
			$temp = explode(";;",$item);
			//if the description, behind ;; is, is specified then use the specified in extraEntityInfo
			if(isset($temp[1])){ 				
				array_push($returnArray, $temp[1]);
			}else{ //if not specified in extraEntityInfo then use description of column which specified in database design.
				list($entityName, $columnName) = explode(".",$temp[0]);
				$obj = $this->_loadLibrary($entityName);
				array_push($returnArray, 
						(isset($obj->revisedColumnDescriptions[$columnName][0])?$obj->revisedColumnDescriptions[$columnName][0]:"_coLdescError_")
						);
			}			
		}		
		return $returnArray;
	}
	/**	 
	 * loop through array in key 'join' of current library to create "join" clause for searching 
	 * @param array joinInfo
	 *	array of joining from extraEntityInfos.php, specified in key 'join' 
	 * @param string libName
	 *	current library name(entity name)
	 * @return string
	 *	
	 */
	private function createJoin($joinInfo,$libName)
	{
		$joinSqlStr=" from {$this->CI->db->dbprefix}{$libName}";
		foreach($joinInfo as $item){
			$joinSqlStr .= " {$item[0]} join {$this->CI->db->dbprefix}{$item[1]} on ";			
			$joinSqlStr .= $this->getJoinOn($item['on']);			
		}
		return $joinSqlStr;
	}
	/**	 
	 * create "join" clause for searching 
	 * @param array joinInfo
	 *	array of joining from extraEntityInfos.php, specified in each key 'join' 	 
	 * @return string
	 *	all join clause of current library(entity) for performing select
	 */
	private function getJoinOn($joinOnInfo){
		$joinOnSqlStr="";
		$orItemsStrCollect="";
		foreach($joinOnInfo as $orItems){
			$andItemStrCollect="";
			foreach($orItems as $andItems){				
				$andItemStrCollect.="<<<{$this->CI->db->dbprefix}{$andItems[0]}{$andItems[1]}{$this->CI->db->dbprefix}{$andItems[2]}>>>";
			}
			$andItemStr1 = str_replace('>>><<<',' and ',$andItemStrCollect);
			$andItemStr2 = str_replace('<<<','(',$andItemStr1);
			$andItemStr3 = str_replace('>>>',')',$andItemStr2);
			$orItemsStrCollect.="<<<{$andItemStr3}>>>";
		}
		$orItemsStr1 = str_replace('>>><<<',' or ',$orItemsStrCollect);
		$orItemsStr2 = str_replace('<<<','(',$orItemsStr1);
		$orItemsStr3 = str_replace('>>>',')',$orItemsStr2);
		$joinOnSqlStr.=$orItemsStr3;
		return $joinOnSqlStr;
	}
	/**	 
	 *	create "where" clause for searching 
	 * @param array request
	 *	$_REQUEST
	 * @param array libDisplaySearchAttribute
	 *	array in "display" key in 'libraryName'=>searchAttributes in extraEntityInfos.php
	 * @param array libHiddenSearchAttribute
	 *	array in "hidden" key in 'libraryName'=>searchAttributes in extraEntityInfos.php, hidden conditions that will be co-use for searching
	 * @return string
	 *	"where" clause, SQL string part 
	 */
	private function createWhereConditions($request,$libDisplaySearchAttribute, $libHiddenSearchAttribute){
		$sqlCondition="";
		foreach($request as $ordinal => $condition){
			if($condition!=""){
				//$condition = $this->escapeSQL($condition);				
				$searchAttributeKey = explode("_",$ordinal);
				$sqlCondition.=$this->_SQLCondition($searchAttributeKey,$libDisplaySearchAttribute,$condition,$sqlCondition);
			}
		}
		//include hidden fields in "where" clause
		foreach($libHiddenSearchAttribute as $val){ 
			$sqlCondition.= " and {$this->CI->db->dbprefix}{$val}";
		}
		return $sqlCondition;
	}
	/**
	 * use by createWhereConditions to avoid too many nested blocks, and to maintain 20 lines per method
	 * @param array $searchAttributeKey
	 * @param array $libDisplaySearchAttribute
	 * @param string $condition
	 * @param string $sqlCondition
	 * @return string
	 */
	private function _SQLCondition($searchAttributeKey,$libDisplaySearchAttribute,$condition,$sqlCondition){
		
		list($tableName,$columnName) =$this->_getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $searchAttributeKey[0]);	
		// if $searchAttributeKey is array that means condition is from and to (between)
		if(isset($searchAttributeKey[1])){ 
			$sqlCondition.=$this->_getFromAndToCond($tableName,$columnName,$condition,$searchAttributeKey,$sqlCondition);
		}else{			
			$tableName = $tableName==''?$this->libName:$tableName;
			//create object of mainForm class for create where conditions by datatype distinguishing  
			$obj = new mainForms($tableName); 
			
			//if datatype characteristic is string
			if(in_array($obj->libObject->columnDataType($columnName),array('varchar','nvarchar','char','nchar','text','ntext'))){ 
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} like '%{$condition}%'";						
			}elseif(in_array($obj->libObject->columnDataType($columnName),array('int','bigint','decimal','float','tinyint','smallint','money'))){
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} = '{$condition}'";
			}else{
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} = '{$condition}'";
			}
		}
		return $sqlCondition;
	}
	/**
	 * use by _SQLCondition to avoid too many nested blocks, and to maintain 20 lines per method
	 * @param string $tableName
	 * @param string $columnName
	 * @param string $condition
	 * @param array $searchAttributeKey
	 * @param string $sqlCondition
	 * @return string
	 */
	private function _getFromAndToCond($tableName,$columnName,$condition,$searchAttributeKey,$sqlCondition){		
		if($searchAttributeKey[1]=='from'){									
			$tableName = $tableName==''?$this->libName:$tableName;
			//create object of mainForm class for create where conditions by datatype distinguishing  
			$obj = new mainForms($tableName); 
			//if datatype is date or datetime, its format must be converted
			if(in_array($obj->libObject->columnDataType($columnName),array('date','datetime'))){ 
				list($year,$month,$day) = $this->splitAndConvertDate($condition);
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} >= '{$year}/{$month}/{$day}'";							
			}else{
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} >= '{$condition}'";
			}
		}elseif($searchAttributeKey[1]=='to'){
			$tableName = $tableName==''?$this->libName:$tableName;
			//create object of mainForm class for create where conditions by datatype distinguishing
			$obj = new mainForms($tableName); 
			//if datatype is date or datetime, its format must be converted
			if(in_array($obj->libObject->columnDataType($columnName),array('date','datetime'))){ 
				list($year,$month,$day) = $this->splitAndConvertDate($condition);
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} <= '{$year}/{$month}/{$day}'";						
			}else{
				$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} <= '{$condition}'";
			}
		}
		return $sqlCondition;
	}
	/**	 	 
	 * distinguish operation and send data to save in mainForms.insertData or mainForms.editData
	  * @return array 
	 *	array of operation results
	 */ 		
	function saveAddEditData(){		
		if(!($this->libObject->insertUpdateAllowed($this->session['id']))){
			$this->notify('danger',"You're not authorized to insert, update or delete {$this->libExtraInfo['descriptions']}.");
		}elseif($this->formValidate($this->_REQUEST)){
			if($this->_REQUEST['operation']==='1'){ parent::insertData(); }
			if($this->_REQUEST['operation']==='0'){ parent::editData(); }
		}else{
			return $this->response;
		}		
		/*
		 * example use of $this->notify($type, $message);
		- $this->notify('warning','you may not confused with...');
		- $this->notify('danger','birthdate can not left blank.');
		- $this->notify('success','Deletion is completed');	
		*/
		return $this->response;
	}
	/**	 	 
	 *	call parent::deleteData() for delete data 	 
	 * @return array 
	 *	array of operation results
	 */ 
	function deleteData()
	{
		parent::deleteData();
		return $this->response;
	}
	/**	 
	 *	perform loading specified record to put in edit form in addEditModal at front-end	 
	 * @return array 
	 *	array of data and information for referenced field 
	 */ 
	function loadDataToEditInModal(){			
		list($columnsWithOrdered, $allLibExtraInfo)=$this->getColumnOrdered();			
		$request = $this->_REQUEST;
		
		//(bugId 20180806-01) //if format of dispaly is specified in AddEditModal (FRPLCEMNT4FMT is specified)
		$columnLists = $this->libObject->revisedColumnDescriptions;
		$selectColumnInit="";
		foreach(array_keys($columnLists) as $kk){ //kk=columnName
			if(isset($allLibExtraInfo[$this->libName]['addEditModal']['format'][$kk])){
				$selectColumnInit .= "{{".str_replace(FRPLCEMNT4FMT,$kk,$allLibExtraInfo[$this->libName]['addEditModal']['format'][$kk])."}}";
			}else{
				$selectColumnInit .= "{{".$kk."}}";
			}
		}
		$selectColumn = str_replace("}}","",str_replace("{{","",str_replace("}}{{",',',$selectColumnInit)));		
		
		$sql = "select {$selectColumn} from {$this->CI->db->dbprefix}{$this->libName} where id={$request['id']}";		
		$q = $this->CI->db->query($sql);
		$row = $q->row();
		$rowArray = (array) $row;
		
		//perform get data for modal
		$this->extend_loadDataToEditInModal($columnsWithOrdered,$allLibExtraInfo,$rowArray);
				
		return $this->response;
	}
	/**
	 * use by loadDataToEditInModal to avoid too many nested blocks, and to maintain 20 lines per method
	 * @param array $columnsWithOrdered
	 * @param array $allLibExtraInfo
	 * @param array $rowArray
	 */
	private function extend_loadDataToEditInModal($columnsWithOrdered,$allLibExtraInfo,$rowArray){
		$index = 0;
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{			
			array_push($this->response['fields'], $rowArray[$fieldName]);			
			
			//if value is null then by pass
			if($rowArray[$fieldName]===""){ 
				$index++;
				continue;
			}
			
			//if column is foreign key from other table 
			if(isset($colInfos['references'])){ 
				$this->extend_loadDataToEditInModal_getRef($index,$fieldName,$allLibExtraInfo,$rowArray);				
			}
			
			/**
			 * in case of none-references but it have been specified in extraEntityInfos[$libName][addEditModal][reference]. 
			 * For instance, 'empIDNo'=>'devEmployees.IDNoAndFullName'. This must create select2 for this situation.
			 */			
			elseif(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName])){ 
				$this->extend_loadDataToEditInModal_getNoneRef($index,$fieldName,$allLibExtraInfo,$rowArray);				
			}
			$index++;
		}
	}
	/**
	 * use by extend_loadDataToEditInModal to avoid too many nested blocks, and to maintain 20 lines per method
	 * @param type $index
	 * @param type $fieldName
	 * @param type $allLibExtraInfo
	 * @param type $rowArray
	 */
	private function extend_loadDataToEditInModal_getRef(&$index,$fieldName,$allLibExtraInfo,$rowArray){
		if(!(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]))){					
			$this->notify('danger'," {$fieldName} got references in db, but references for {$fieldName} not defined in addEditModal. ");	
		}				
		list($refTableName, $refFieldName) = explode(".",$allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]);		
		$refSql = "select id, {$refFieldName} [name]  from {$this->CI->db->dbprefix}{$refTableName} where id = '{$rowArray[$fieldName]}' ";
		//echo $refSql;
		$refQ = $this->CI->db->query($refSql);
		$refRow = $refQ->row();
		$refRowArray = (array) $refRow;
		if(isset($refRowArray['id'])){ //if record exists 
			array_push($this->response['references'], $index."#++||||++#".$refRowArray['id']."#++||||++#".$refRowArray['name']);
		}
	}
	/**
	 *  use by extend_loadDataToEditInModal to avoid too many nested blocks, and to maintain 20 lines per method
	 * @param type $index
	 * @param type $fieldName
	 * @param type $allLibExtraInfo
	 * @param type $rowArray
	 */
	private function extend_loadDataToEditInModal_getNoneRef(&$index,$fieldName,$allLibExtraInfo,$rowArray){
		list($refTableName, $refFieldName) = explode(".",$allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]);			
		$refSql = "select id, {$refFieldName} [name]  from {$this->CI->db->dbprefix}{$refTableName} where id = '{$rowArray[$fieldName]}' ";
		//echo $refSql;
		$refQ = $this->CI->db->query($refSql);
		$refRow = $refQ->row();
		$refRowArray = (array) $refRow;		
		array_push($this->response['references'], $index."#++||||++#".$refRowArray['id']."#++||||++#".$refRowArray['name']);
	}	
	/**	 
	 * get field value,id of record, of data in main-entity for use in insert SQL of sub-entity	 
	 * @return int	 
	 */ 
	function getIdFieldValueAndSubModalInfo(){		
		//use $request instead of $this->_REQUEST to prevent accidentally modify its contents.
		$request = $this->_REQUEST;			
		unset($request['entityOrdinal']);
		
		$columnListsInfo = $this->libObject->columnListInfo;
		$index=0;
		foreach($columnListsInfo as $key=>$val)
		{
			if($val['ColumnName']=='id')	{
				$valueToReturn = $request[$index];				
			}
			unset($request[$key]);
			$index++;
		}		
		return ['idValue'=>$valueToReturn, 'subModal'=>$this->libExtraInfo['addEditModal']['subModal']];
	}
	/**	 
	 * verify that alterView of subModal is exists or not, if not then return warning message, in the other nand,
	 * remove suppressed field that have been specified in subModalInfo and 
	 * perform call searchResult() to search for submodal for send back to put in datatable of sub-modal(sub-entity)	 
	 * @return string
	 *	HTML of data-table 
	 */
	function searchResultsForSubModel($subModalInfo){
		//precheck
		//alterview haven declared or not
		if(!isset($subModalInfo['subModal'][$this->libName]['alterView'])){			
			return ['results'=>'Alterview in submodal have not been declared.'];
		}
				
		//remove selectAttributeFields['suppressedFields']
		if(isset($subModalInfo['subModal'][$this->libName]['suppressedFields'])){
			
			$suppressFields = $subModalInfo['subModal'][$this->libName]['suppressedFields'];		
			
			$selectAttributeFields = $this->libExtraInfo['selectAttributes']['fields'];
			foreach($selectAttributeFields as $key=>$val)
			{
				$temp1 = explode(";;", $val);
				$temp2 = explode(".",$temp1[0]);
				if(in_array($temp2[1] , $suppressFields)){
					unset($selectAttributeFields[$key]);
				}
			}
			$this->libExtraInfo['selectAttributes']['fields'] = $selectAttributeFields;
		}
		return $this->searchResults($subModalInfo);
	}
	/**
	 * compose SQL string and do update record specified from sub-entity
	 */
	function updateSubEntityRecord(){
		list($request, $tableName, $columnName, $fieldValue) = $this->_getInfoForUpdateSubEntity();
		
		if(!($this->libObject->insertUpdateAllowed($this->session['id']))){
			$this->notify('danger',"You'You're not authorized to insert, update or delete {$this->libExtraInfo['descriptions']}."); 
			return $this->response;
		}elseif($this->formValidateForSubEntity($columnName, $fieldValue)){
			//in case of additional validation, for example see devClassExtInstructors.php. 		
			$this->libObject->infoForAdditionalValidateSubEntity = ['idValue'=>$request[0], 'tableName'=>$tableName, 'columnToUpate'=>$columnName];
			$fieldValue = $this->_getFieldValueForSubModal($request,$columnName,$fieldValue);
						
			$updateSql = "update {$tableName} set {$columnName} = {$fieldValue} where id='{$request[0]}' ";
			//if(CODING_ENVIROMENT=='develop'){ $this->response['converted']['updateSql'] = $updateSql;}
			$updateResult = $this->libObject->doDbTransactions($updateSql);
			$libExtraInfo = $this->libExtraInfo;
			if($updateResult[0]=='ok'){
				$this->notify('success',"Updated {$libExtraInfo['descriptions']} ");
			}else{
				$updateResult[1] = $this->convertDBErrorMessageToUser($updateResult['errorCode'],$updateResult['errorMessage'],extraEntityInfos::infos());
				$this->notify('danger',"Unable to update {$libExtraInfo['descriptions']}, because {$updateResult[1]} ");
			}
			return $this->response;
		}else{
			return $this->response;
		}		
	}
	/**
	 * call from updateSubEntityRecord to avoid too many nested blocks.
	 * @return array 
	 */
	private function _getInfoForUpdateSubEntity(){
		$request = $this->_REQUEST; 
		$tableName = $this->CI->db->dbprefix."".$this->libName; 
		$columnName = $this->getSubEntityColumnName($request[1]);		
		//convert date and time 
		$fieldValue = $request[2];	
		return [$request, $tableName, $columnName, $fieldValue];
	}
	/**
	 * call from updateSubEntityRecord to avoid too many nested blocks.
	 * @param array $request
	 * @param array $columnName
	 * @return string
	 *	string in form of SQL update value
	 */
	private function _getFieldValueForSubModal($request,$columnName,$fieldValue){
		if(in_array($this->_getColumnDataType($this->libObject, $columnName),['date','datetime'])){
			if($request[2]!==""){
				$convertedDate = $this->splitAndConvertDate($request[2]);
				$fieldValue = "'".$convertedDate[0]."/".$convertedDate[1]."/".$convertedDate[2]."'";
			}else{
				$fieldValue="NULL";
			}
		}else{
			$fieldValue = $fieldValue==""?"NULL":"'{$fieldValue}'";
		}
		return $fieldValue;
	}
	/**
	 * get column name for use in update "CLAUSE", requested from sup-entity updating
	 * @param string $refKey
	 * @return string
	 *	column name
	 */
	private function getSubEntityColumnName($refKey){
		$index=0;
		foreach($this->libExtraInfo['selectAttributes']['editableInSubEntity'] as $key=>$val){
			if($index===((int)$refKey)){
				return $this->extend_getSubEntityColumnName($key,$val);
			}
			$index++;
		}
	}
	/**
	 * call from getSubEntityColumnName to avoid too many nested blocks.
	 * @param string $key
	 * @param string $val
	 * @return string
	 */
	private function extend_getSubEntityColumnName($key,$val){
		if($key===$val){
			return $key;
		}else{
			$temp = explode("::", $val);
			foreach(array_keys($this->libExtraInfo['addEditModal']['references']) as $key2){
				if($temp[1]==$key2){
					return $key2;
				}
			}
		}
	}
}