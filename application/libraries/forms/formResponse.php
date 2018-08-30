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
	 * <p><pre>	 
	 *	compose sql string for search and send back to front end. there are two search case, first, search from filter row, second search from sub-entity
	 * </pre><p>	 
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
		//$allLibSearchAttribute = array_merge($libDisplaySearchAttribute, $libHiddenSearchAttribute); //เอาเงื่อนไขที่ให้เลือกใน filter row มารวมกับ เงื่อนไขที่ hidden			
		
		$request = $this->_REQUEST;
		unset($request['entityOrdinal']); //เอา ordinal ออก เพราะไม่ได้ใช้ในการสร้าง sql
		$response = (Object) null;
		$parameters = (Object) null;
		
		//create select clause
		$sqlSelect = $this->createSelectFields($libInfos['selectAttributes']);
		if(CODING_ENVIROMENT=='develop') $response->sql['select']  = $sqlSelect; //เอาไว้ดูเฉยๆใน ajax response
		$parameters->sql['select'] = $sqlSelect;
		
		//create from clause and join clause
		$libInfos['join'] = (isset($libInfos['join']))?$libInfos['join']:[];
		$sqlJoin = $this->createJoin($libInfos['join'],$this->libName);
		if(CODING_ENVIROMENT=='develop') $response->sql['join']  = $sqlJoin; //เอาไว้ดูเฉยๆใน ajax response
		$parameters->sql['join'] = $sqlJoin;
		
		//create where clause		
		//ถ้าเป็น search ที่มาจาก subModal
		if(isset($subModalInfo['subModal'][$this->libName])){
			//return ['idValue'=>$valueToReturn, 'subModal'=>$this->libExtraInfo['addEditModal']['subModal']];
			$idValue = $this->escapeSQL($subModalInfo['idValue']);
			$temp = explode(".", $subModalInfo['subModal'][$this->libName]['alterView']);
			$fieldName=$temp[1];
			$sqlCondition = " where 1=1 and {$this->CI->db->dbprefix}{$this->libName}.{$fieldName}='{$idValue}' ";			
			
		}
		else //ถ้าเป็น search ที่มาจาก filterRow ในหน้าหลัก
		{
			$sqlCondition = " where 1=1 ".$this->createWhereConditions($request,$libDisplaySearchAttribute, $libHiddenSearchAttribute);
		}		
				
		if(method_exists($this->libName,'additionalWhereInFilterRow')){
			$sqlCondition.=$this->libObject->additionalWhereInFilterRow();
		}
		
		if(CODING_ENVIROMENT=='develop') $response->sql['condition'] = $sqlCondition; //เอาไว้ดูเฉยๆใน ajax response
		$parameters->sql['condition'] = $sqlCondition;
		
		//สร้างหัวคอลัมน์
		$headerArray = $this->getSelectListColumnDescriptions($this->libName);
		
		$response->results = $this->_getResultFromSQL($parameters->sql,$headerArray,$subModalInfo);
		
		return $response;
	}
	/**	 
	 * <p><pre>	 
	 *	execute SQL string which composed in this->searchResults and return search result in HTML format to front-end.
	 * </pre><p>	 
	 * @param array sqlObj 
	 *	consists of SQL string select, join and condition
	 * @param array headerArray
	 *	array of header informations which constructed in this->getSelectListColumnDescriptions
	 * @param array subModalInfo
	 *	incase of search from subModal(sub-entity) this variable will be contains an information of subModal(sub-entity)
	 * @return string 
	 *	HTML string of table, which will be use as dataTable
	 */
	private function _getResultFromSQL($sqlObj,$headerArray,$subModalInfo=[])
	{
		$sqlStr = $sqlObj['select'].$sqlObj['join'].$sqlObj['condition'];		
		$q = $this->CI->db->query($sqlStr);
		$tableRow="";
		foreach($q->result() as $row){
			$rowArray = (array)$row;
			$tableData="";
			foreach($rowArray as $key=>$val)	{
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
		if($tableRow==""){ //ไม่พบข้อมูล		
			$table = "<table  class=\"table table-striped custom-table datatable\">";
			$table.="<tbody><tr><td><div class=\"alert alert-warning\" role=\"alert\">search result not found.</div></td></tr></tbody";
			$table.="</table>";
			return $table;
		}
		//ดึงหัวคอลัมน์ออกมา
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
			//var_dump($this->_REQUEST);
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
	 * <p><pre>	 
	 *	distinguish datatype between date and datetime and time and return format for datepicker
	 * </pre><p>	 
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
	 * <p><pre>	 
	 *	compose td of each search result 
	 * </pre><p>	 
	 * @param string key
	 *	display field name key 
	 * @return string
	 *	html of td
	 */
	private function _getTdTableData($key, $val,$subModalInfo){		
		//(isset($subModalInfo['subModal']))
		$libInfos = $this->libExtraInfo;		
		if((isset($libInfos['selectAttributes']['editableInSubEntity'][$key])) && (isset($subModalInfo['subModal']))){ //if editable data in td, and called from display in submodal
			$cientityKeyReference = $this->_referenceKeyForSubModalEditTable($libInfos['selectAttributes']['editableInSubEntity'], $key);			
			if($key==$libInfos['selectAttributes']['editableInSubEntity'][$key]){ //if it is input text
				//dont forget date or datetime input				
				$dataType = $this->_getColumnDataTypeDirectlyForSubEntityRowInput($key, $libInfos);
				if(in_array($dataType,['date','datetime','time'])){
					$datetimeInputInfo = $this->convertdtFunction($dataType);					
				}else{					
					$datetimeInputInfo = ['',''];
				}
				return "<input  type='text' class='form-control input-sm cientitySubModalEditTd {$datetimeInputInfo[0]}' {$datetimeInputInfo[1]}  cientityKeyReference='{$cientityKeyReference}' value='{$val}' cientityRollbackValue=\"{$val}\" />";
			}else{				
				//return  $this->getSelectInputForSubEntity($key, $val);
				return $this->_inputSelectForSubEntity($key,$val,$cientityKeyReference);
			}
		}else{
			return $val;
		}
	}
	/**	 
	 * <p><pre>	 
	 *	compose select input for sub-entity, get value from text
	 * </pre><p>	 
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
		//var_dump($linkInfo);			
		
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
	 * <p><pre>	 
	 *	looking for ordinal number of element in $libInfos['selectAttributes']['editableInSubEntity'][$key]
	 * </pre><p>	 
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
	 * <p><pre>	 
	 *	compose fields of select for select clause, 
	 * </pre><p>	 
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
					//$val2=$temp[0];
					if(isset($selectAttributes['format'][$temp[0]])){ //หากมี format ของฟิลด์นี้					
						$selectAttributesStr .= ", ".str_replace("__#@!!@#__",$this->CI->db->dbprefix.$temp[0], $selectAttributes['format'][$temp[0]]);
					}else{
						$selectAttributesStr .= ", {$this->CI->db->dbprefix}{$temp[0]}";
					}
				}
			}else{				
				$temp = explode(';;',$val);				
				if(isset($selectAttributes['format'][$temp[0]])){ //หากมี format ของฟิลด์นี้				
					$selectAttributesStr .= ", ".str_replace("__#@!!@#__",$this->CI->db->dbprefix.$temp[0], $selectAttributes['format'][$temp[0]]);
				}else{
					$selectAttributesStr .= ", {$this->CI->db->dbprefix}{$temp[0]}";
				}
			}			
		}
		return str_replace('dummyColumnNameBpspanuOntherock,','',("select ".$selectAttributesStr));		
	}
	/**	
	* @function getSelectListColumnDescriptions($entityName)
	* @desc คื่นค่าคำอธิบายในหัวคอลัมน์ เพื่อเอาไปแสดงใน datatable ในรูปของ อาเรย์
	* @parameters $libraryName ชื่อ entity ของ page นั้น
	* @return array ของคำอธิบาย array('ชื่อคอลัมน์ใน select list','คำอธิบาย') ตัวอย่าง array('code'=>'รหัสสถานที่')
	*/
	/**	 
	 * <p><pre>	 
	 *	get column descriptions, header of table for filter row search result or sub-entity search result
	 * </pre><p>	 
	 * @param string libraryName
	 *	library name of current library(entity)
	 * @return array
	 *	array of column descriptions which will be used as table header.
	 */
	protected function getSelectListColumnDescriptions($libraryName) 
	{
		//$selectFields = extraEntityInfos::infos[$libraryName]['selectAttributes']['fields']; //bugId 20180808-01
		$selectFields = $this->libExtraInfo['selectAttributes']['fields']; //แก้ bugId 20180808-01
				
		$returnArray = [];
		foreach($selectFields as $key=>$item) //load entity เพื่อจะได้เอา descriptions ของแต่ละ field ออกมา
		{
			if(is_array($item)) //ถ้ามีฟิลด์ซ้อนกันใน 1 array item 
			{
				foreach($item as $key2 => $item2)
				{
					$temp = explode(";;",$item2);
					if(isset($temp[1])) //ถ้ากำหนด descriptions มาเอง(ไม่เอา description ของ column ในฐานข้อมูล
					{
						array_push($returnArray, $temp[1]);
					}
					else //ไปเอา descriptions ของ column ในฐานข้อมูล
					{
						list($entityName, $columnName) = explode(".",$temp[0]);
						$obj = $this->_loadLibrary($entityName);
						array_push($returnArray, $obj->revisedColumnDescriptions[$columnName][0]);
					}
				}				
			}
			else
			{
				$temp = explode(";;",$item);
				if(isset($temp[1])) //ถ้ากำหนด descriptions มาเอง(ไม่เอา description ของ column ในฐานข้อมูล
				{
					array_push($returnArray, $temp[1]);
				}
				else //ไปเอา descriptions ของ column ในฐานข้อมูล
				{
					list($entityName, $columnName) = explode(".",$temp[0]);
					$obj = $this->_loadLibrary($entityName);
					array_push($returnArray, 
							(isset($obj->revisedColumnDescriptions[$columnName][0])?$obj->revisedColumnDescriptions[$columnName][0]:"_coLdescError_")
							);
				}
			}
		}
		//var_dump($returnArray);exit;
		return $returnArray;
	}
	/**	 
	 * <p><pre>	 
	 *	loop through array in key 'join' of current library to create "join" clause for searching 
	 * </pre><p>	 
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
	 * <p><pre>	 
	 *	create "join" clause for searching 
	 * </pre><p>	 
	 * @param array joinInfo
	 *	array of joining from extraEntityInfos.php, specified in each key 'join' 	 
	 * @return string
	 *	all join clause of current library(entity) for performing select
	 */
	private function getJoinOn($joinOnInfo){
		$joinOnSqlStr="";
		$orItemsStr="";
		foreach($joinOnInfo as $key => $orItems){
			$andItemStr="";
			foreach($orItems as $key2 => $andItems){				
				$andItemStr.="<<<{$this->CI->db->dbprefix}{$andItems[0]}{$andItems[1]}{$this->CI->db->dbprefix}{$andItems[2]}>>>";
			}
			$andItemStr = str_replace('>>><<<',' and ',$andItemStr);
			$andItemStr = str_replace('<<<','(',$andItemStr);
			$andItemStr = str_replace('>>>',')',$andItemStr);
			$orItemsStr.="<<<{$andItemStr}>>>";
		}
		$orItemsStr = str_replace('>>><<<',' or ',$orItemsStr);
		$orItemsStr = str_replace('<<<','(',$orItemsStr);
		$orItemsStr = str_replace('>>>',')',$orItemsStr);
		$joinOnSqlStr.=$orItemsStr;
		return $joinOnSqlStr;
	}
	/**	 
	 * <p><pre>	 
	 *	create "where" clause for searching 
	 * </pre><p>	 
	 * @param array request
	 *	$_REQUEST
	 * @param array libDisplaySearchAttribute
	 *	array in "display" key in 'libraryName'=>searchAttributes in extraEntityInfos.php
	 * @param array libHiddenSearchAttribute
	 *	array in "hidden" key in 'libraryName'=>searchAttributes in extraEntityInfos.php, hidden conditions that will be co-use for searching
	 * @return string
	 *	"where" clause, sql string part 
	 */
	private function createWhereConditions($request,$libDisplaySearchAttribute, $libHiddenSearchAttribute){
		$sqlCondition="";
		foreach($request as $ordinal => $condition){
			if($condition!=""){
				//$condition = $this->escapeSQL($condition);				
				$searchAttributeKey = explode("_",$ordinal);
				if(isset($searchAttributeKey[1])){ //หาก ordinal ถูก ส่งมาพร้อมกับเครื่องหมาย _ นั่นหมายถึง between
					if($searchAttributeKey[1]=='from'){
						list($tableName,$columnName) =$this->_getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $searchAttributeKey[0]);						
						$tableName = $tableName==''?$this->libName:$tableName;
						$obj = new mainForms($tableName); //สร้าง object ของ tableName ขึ้นมาใหม่เพื่อเช็ค dataType
						if(in_array($obj->libObject->columnDataType($columnName),array('date','datetime'))){ //ถ้า datatype เป็น datetime ต้องแปลง format ก่อน						
							list($year,$month,$day) = $this->splitAndConvertDate($condition);
							$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} >= '{$year}/{$month}/{$day}'";							
						}else{
							$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} >= '{$condition}'";
						}
					}elseif($searchAttributeKey[1]=='to'){
						list($tableName,$columnName) =$this->_getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $searchAttributeKey[0]);		
						$tableName = $tableName==''?$this->libName:$tableName;
						$obj = new mainForms($tableName); //สร้าง object ของ tableName ขึ้นมาใหม่เพื่อเช็ค dataType
						if(in_array($obj->libObject->columnDataType($columnName),array('date','datetime'))){ //ถ้า datatype เป็น datetime ต้องแปลง format ก่อน						
							list($year,$month,$day) = $this->splitAndConvertDate($condition);
							$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} <= '{$year}/{$month}/{$day}'";						
						}else{
							$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} <= '{$condition}'";
						}
					}
				}else{
					list($tableName,$columnName) =$this->_getTableAndColumnNameInSearchAttributes($libDisplaySearchAttribute, $searchAttributeKey[0]);
					$tableName = $tableName==''?$this->libName:$tableName;
					$obj = new mainForms($tableName); //สร้าง object ของ tableName ขึ้นมาใหม่เพื่อเช็ค dataType		
					
					if(in_array($obj->libObject->columnDataType($columnName),array('varchar','nvarchar','char'))){ //ถ้า datatype เป็น char, varchar, nvarchar จะเกิดจาก การเอาฟิลด์ varchar มาต่อกันใน view
						$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} like '%{$condition}%'";						
					}elseif(in_array($obj->libObject->columnDataType($columnName),array('int','bigint'))){
						$sqlCondition.=" and {$this->CI->db->dbprefix}{$tableName}.{$columnName} = '{$condition}'";
					}
				}
			}
		}
		
		foreach($libHiddenSearchAttribute as $key=>$val){ //ฟิลด์ที่ซ่อน แต่ต้องเอาเงื่อนไขไปประกอบร่วมในการ search 
			$sqlCondition.= " and {$this->CI->db->dbprefix}{$val}";
		}
		return $sqlCondition;
	}
	/**	 
	 * <p><pre>	 
	 *	distinguish operation and send data to save in mainForms.insertData or mainForms.editData
	 * </pre><p>	 	 
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
		//$this->notify('warning','คุณอาจจจะสับสนระหว่าง ห้องเรียน กับวิชาเรียนได้');
		//$this->notify('danger','ยังไม่ได้กรอกข้อมูลวัน เดือน ปี เกิด ');
		//$this->notify('success','บันทึกข้อมูลสำเร็จ');	
		
		return $this->response;
	}
	/**	 
	 * <p><pre>	 
	 *	call parent::deleteData() for delete data 
	 * </pre><p>	 	 
	 * @return array 
	 *	array of operation results
	 */ 
	function deleteData()
	{
		parent::deleteData();
		return $this->response;
	}
	/**	 
	 * <p><pre>	 
	 *	perform loading specified record to put in edit form in addEditModal at front-end
	 * </pre><p>	 	 
	 * @return array 
	 *	array of data and information for referenced field 
	 */ 
	function loadDataToEditInModal(){
		$response = mainForms::stdResponseFormat();		
		list($columnsWithOrdered, $allLibExtraInfo, $columns)=$this->getColumnOrdered();			
		$request = $this->_REQUEST;
		
		//(bugId 20180806-01) หากระบุ format ในการแสดงผลในหน้า addEditModal มา
		$columnLists = $this->libObject->revisedColumnDescriptions;
		$selectColumn="";
		foreach($columnLists as $kk=>$vv){ //kk=columnName
			if(isset($allLibExtraInfo[$this->libName]['addEditModal']['format'][$kk])){
				$selectColumn .= "{{".str_replace("__#@!!@#__",$kk,$allLibExtraInfo[$this->libName]['addEditModal']['format'][$kk])."}}";
			}else{
				$selectColumn .= "{{".$kk."}}";
			}
		}
		$selectColumn = str_replace("}}","",str_replace("{{","",str_replace("}}{{",',',$selectColumn)));		
		
		$sql = "select {$selectColumn} from {$this->CI->db->dbprefix}{$this->libName} where id={$request['id']}";
		
		$q = $this->CI->db->query($sql);
		$row = $q->row();
		$rowArray = (array) $row;
				
		$index = 0;
		foreach($columnsWithOrdered as $fieldName=>$colInfos)	{
			//array_push($this->response['converted'], $rowArray[$fieldName]);
			array_push($this->response['fields'], $rowArray[$fieldName]);
			if(isset($colInfos['references'])){ //ถ้าใน syncedColumnlistInfoWithRefKey มีฟิลด์ response ของ column นั้น แสดงว่า มีการอ้างไปถึงฟิลด์อื่น
				//$references[$index] = ;
				if(!(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]))){					
					$this->notify('danger'," {$fieldName} got references in db, but references for {$fieldName} not defined in addEditModal. ");	
				}				
				list($refTableName, $refFieldName) = explode(".",$allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]);
				if($rowArray[$fieldName]===""){ //ถ้า ตารางหลัก ฟิลด์นี้มีค่าเป็น null ถึงแม้จะ reference ก็ให้ข้ามไปเลย 						
					$index++;
					continue;
				}
				$refSql = "select id, {$refFieldName} [name]  from {$this->CI->db->dbprefix}{$refTableName} where id = '{$rowArray[$fieldName]}' ";
				//echo $refSql;
				$refQ = $this->CI->db->query($refSql);
				$refRow = $refQ->row();
				$refRowArray = (array) $refRow;
				if(isset($refRowArray['id'])){ //if record exists 
					array_push($this->response['references'], $index."#++||||++#".$refRowArray['id']."#++||||++#".$refRowArray['name']);
				}
			}
			elseif(isset($allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName])){ //ถึงแม้จะไม่ได้ระบุว่า มี reference ใน syncedColumnlistInfoWithRefKey แต่ระบุ reference มาใน extraEntityInfos[$libName][addEditModal][reference] เช่น 'empIDNo'=>'devEmployees.IDNoAndFullName' ก็ให้สร้าง select2 ด้วย 
				list($refTableName, $refFieldName) = explode(".",$allLibExtraInfo[$this->libName]['addEditModal']['references'][$fieldName]);
				if($rowArray[$fieldName]===""){ //ถ้า ตารางหลัก ฟิลด์นี้มีค่าเป็น null ถึงแม้จะ reference ก็ให้ข้ามไปเลย 						
					$index++;
					continue;
				}
				$refSql = "select id, {$refFieldName} [name]  from {$this->CI->db->dbprefix}{$refTableName} where id = '{$rowArray[$fieldName]}' ";
				//echo $refSql;
				$refQ = $this->CI->db->query($refSql);
				$refRow = $refQ->row();
				$refRowArray = (array) $refRow;
				//$references[$index] = $refRowArray;
				//var_dump($refRowArray); exit;
				array_push($this->response['references'], $index."#++||||++#".$refRowArray['id']."#++||||++#".$refRowArray['name']);
			}
			$index++;
		}		
		return $this->response;
	}
	
	/*
		ดึงสมาชิกใน _REQUEST ที่ส่งมาว่า ตัวไหนคือ id ที่ใช้อ้างถึง ใช้สำหรับ query ให้ submodal
	*/
	/**	 
	 * <p><pre>	 
	 *	get field value,id of record, of data in main-entity for use in insert sql of sub-entity
	 * </pre><p>	 	 
	 * @return int
	 *	
	 */ 
	function getIdFieldValueAndSubModalInfo(){
		//ดึงดูว่า id คือตัวไหน
		$request = $this->_REQUEST;
		
		//$subEntityOrdinal = $request['entityOrdinal']; //subentity ที่ส่งมาคืออะไร เอามาเก็บไว้ก่อน		
		unset($request['entityOrdinal']);
		
		$columnListsInfo = $this->libObject->columnListInfo;
		$index=0;
		foreach($columnListsInfo as $key=>$val)
		{
			if($val['ColumnName']=='id')
			{
				$valueToReturn = $request[$index];				
			}
			unset($request[$key]);
			$index++;
		}		
		return ['idValue'=>$valueToReturn, 'subModal'=>$this->libExtraInfo['addEditModal']['subModal']];
	}
	/**	 
	 * <p><pre>	 
	 *	verify that alterView of subModal is exists or not, if not then return warning message, in the other nand,
	 * remove suppressed field that have been specified in subModalInfo and 
	 * perform call searchResult() to search for submodal for send back to put in datatable of sub-modal(sub-entity)	 
	 * </pre><p>	 	 
	 * @return string
	 *	html of datatable 
	 */
	function searchResultsForSubModel($subModalInfo){
		//เช็คเบื้องต้นก่อน 
		//มี alterView หรือยัง		
		if(!isset($subModalInfo['subModal'][$this->libName]['alterView'])){
			return ['results'=>'ยังไม่ได้กำหนด alter view ใน submodal '];
		}
		
		//เอา suppress field ออก selectAttributeFields		
		if(isset($subModalInfo['subModal'][$this->libName]['suppressedFields'])){
			
			$suppressFields = $subModalInfo['subModal'][$this->libName]['suppressedFields'];
			//var_dump($suppressFields);
			
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
	 * compose sql string and do update record specified from sub-entity
	 */
	function updateSubEntityRecord(){
		$request = $this->_REQUEST;
		$tableName = $this->CI->db->dbprefix."".$this->libName;
		$columnName = $this->getSubEntityColumnName($request[1]);		
		//convert date and time 
		$fieldValue = $request[2];
		
				
		if(!($this->libObject->insertUpdateAllowed($this->session['id']))){
			$this->notify('danger',"You'You're not authorized to insert, update or delete {$this->libExtraInfo['descriptions']}.");
		}elseif($this->formValidateForSubEntity($columnName, $fieldValue)){
			//in case of additional validation, for example see devClassExtInstructors.php. 
			
			$this->libObject->infoForAdditionalValidateSubEntity = ['idValue'=>$request[0], 'tableName'=>$tableName, 'columnToUpate'=>$columnName];
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
			$updateSql = "update {$tableName} set {$columnName} = {$fieldValue} where id='{$request[0]}' ";
			if(CODING_ENVIROMENT=='develop') $this->response['converted']['updateSql'] = $updateSql;
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
	 * get column name for use in update "CLAUSE", requested from sup-entity updating
	 * @param string $refKey
	 * @return string
	 *	column name
	 */
	private function getSubEntityColumnName($refKey){
		$index=0;
		foreach($this->libExtraInfo['selectAttributes']['editableInSubEntity'] as $key=>$val){
			if($index===((int)$refKey)){
				if($key===$val){
					return $key;
				}else{
					$temp = explode("::", $val);
					foreach($this->libExtraInfo['addEditModal']['references'] as $key2=>$val2){
						if($temp[1]==$key2){
							return $key2;
						}
					}
				}
			}
			$index++;
		}
	}
}