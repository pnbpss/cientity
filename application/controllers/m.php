<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/forms/mainForms.php');
require_once(APPPATH.'libraries/forms/formResponse.php');

/**
 * M class is main controller of entire CI-Entity. It is interface of all connection from front-end.
 */
class M extends CI_Controller {
	
	/**
	 * The constructor only check that user is logged in or not
	*/
	private $entityRecipes;
	public function __construct(){
		parent::__construct();
		$this->session = $this->session->userdata(USER_INFO_SESSION_KEY);
		if(!(isset($this->session))){
			if(in_array($this->uri->segment(2), array('m','e',''))){
				redirect(base_url().'user/loginform');
				exit;
			}else{
				$this->responseNotLoggedIn();
				exit;
			}
		}
		$this->entityRecipes = new entityRecipes();
	 }
	/**
	 * Display the home page.
	 */
	public function index(){		
		$viewData = self::getViewData();
		$this->load->view('cientityIntro_view',$viewData);
	}
	
	/**
	 * Display the home page dashboard 
	 * *****this function must be removed in Github version.**
	 */
	public function dashboard(){		
		$viewData = self::getViewData();
		$this->load->model('dashboard');
		$viewData['openingClasses']=$this->dashboard->openingClasses();
		$viewData['employeeEnrolled']=$this->dashboard->employeeEnrolled();
		$viewData['classesExpense']=$this->dashboard->classesExpense();
		$viewData['quizzes']=$this->dashboard->quizzes();
		$viewData['listOfOpeningClasses']=$this->dashboard->listOfOpeningClasses();
		$viewData['viewAllClassesLink']=$this->dashboard->viewAllClassesLink();
		$this->load->view('dashboard_view',$viewData);
	}
	
	/**
	 * Display the page according to $this->uri->segment(3), the entityOrdinal.
	 */
	public function e(){
		/**
		 * Get the ordinal of entity. The ordinal is sequence number of entity in entityRecipes->getRecipes()
		 */
		$entityOrdinal = $this->uri->segment(3);	
		
		/**
		 * Get entity name from 
		 */
		$entityName = $this->entityRecipes->getEntityName($entityOrdinal);
		
		/**
		 * Create new object forms by using entity name
		 */
		$forms = new mainForms($entityName);
		
		/**
		 * Fetch form Extra information of entity 
		 */
		$formExtraInfo = $forms->_getCurrentEntityRecipes();
		
		/**
		 * Get viewData that will be sent to 'entity_view'
		 */
		$viewData = $this->_getEntityViewData($entityOrdinal, $entityName, $forms, $formExtraInfo);
		
		/**
		 * Load view by using viewData
		 */		
		$this->load->view('entity_view',$viewData);
	}
	
	private function _getEntityViewData($entityOrdinal, $entityName, $mainForms, $formExtraInfo){
		
		$viewData = self::getViewData();
				
		$viewData['header_JS_CSS'] = (isset($formExtraInfo['header_JS_CSS']))?$formExtraInfo['header_JS_CSS']:$mainForms->entityRecipes_default_header_JS_CSS();
		$viewData['footer_JS_CSS'] = (isset($formExtraInfo['footer_JS_CSS']))?$formExtraInfo['footer_JS_CSS']:$mainForms->entityRecipes_default_footer_JS_CSS();
		$viewData['entityThDescription'] = (isset($formExtraInfo['descriptions']))?$formExtraInfo['descriptions']:"entityRecipes[{$entityName}].descriptions not exists";
		$viewData['entityMoreDetailDesc'] = (isset($formExtraInfo['moreDetails']))?"<small><i>(".$formExtraInfo['moreDetails'].")</i></small>":"";
		
		 $viewData['activeMenuItem'] = $entityOrdinal;
		
		/**
		 * If the entity is not 'customized', normal entity that create under rules of CI-Entity
		 */		
		if(!((isset($formExtraInfo['customized'])) && ($formExtraInfo['customized']===true))){			
			$viewData['filterRow'] = $mainForms->createFilterRow();	
			$viewData['addEditModal'] = $mainForms->createAddEditModal();
			$viewData['customizedEntity'] = false;			
		}
		/**
		 * If not 'customized, then load do the method in the customized entity in [entityName].php
		 */
		else{			
			$viewData['filterRow'] = $mainForms->libObject->createFilterRow();	
			$viewData['addEditModal'] = $mainForms->libObject->createSearchResultZone();
			$viewData['customizedEntity'] = true;
		}			
		return $viewData;
	}
	
	/**
	 * Perform received search keyword from select2 in filter row and response back the query result
	 */
	public function infoForAjaxOptions(){
		/**
		 * Get entity name by ordinal number of entity(uri->segment(3)).
		 */		
		$entityName = $this->entityRecipes->entityNameByURISegment($this->uri->segment(3));
		
		/**
		 * Create forms object for perform search.
		 */
		$forms = new mainForms($entityName);	
		
		/**
		 * get searchOption and keep search key word in cookies.
		 */
		$searchOption = $this->_getSelect2SearchOption();
		
		/**
		 * Return search result back to select2.
		 */
		$response = $forms->infoForAjaxOptions($this->uri->segment(3), $searchOption);
		echo json_encode($response);
	}
	
	/**
	 * Perform received search keyword from select2 in add-edit modal and response back the query result. It also response to 
	 * select2 in sub-entity.
	 */
	public function infoForAjaxAddEditModalOptions(){
		
		/**
		 * Get entity name by ordinal number of entity(uri->segment(3)).
		 */		
		$entityName = $this->entityRecipes->entityNameByURISegment($this->uri->segment(3));
		
		/**
		 * Create forms object for perform search.
		 */
		$forms = new mainForms($entityName);	
		
		/**
		 * get searchOption and keep search key word in cookies.
		 */
		$searchOption = $this->_getSelect2SearchOption();
		
		/**
		 * Return search result back to select2.
		 */
		$response = $forms->infoForAjaxAddEditModalOptions($this->uri->segment(3), $searchOption);
		echo json_encode($response);
	}
	
	/**
	 * Perform keeping search keyword of select2 in cookies. 
	 * @return string search option 
	 */
	private function _getSelect2SearchOption(){
		/**
		 * Define cookie name for distinguish select2 component
		 */
		$cookieName = 'infoForAjaxOptions_'.$this->uri->segment(3);
		
		/**
		 * Get the $_REQUEST via CI input helper
		 */
		$_request = $this->input->post(null,true);
		
		/**
		 * If $_REQUEST['q'] was submitted (user typed a keyword), then use that keyword as search condition. This also keep 
		 * the keyword in to $_COOKIES.
		 * If user only browses (not typed yet), then check that last keyword kept in $_COOKIES. 
		 */
		if(isset($_request['q'])){
			$searchOption = $_request['q'];			
			$this->input->set_cookie(['name'=>$cookieName, 'value'=>$_request['q'],'expire'=>'86500']);			
		}else{			
			$searchOption = $this->input->cookie($cookieName, TRUE)?$this->input->cookie($cookieName, TRUE):'';
		}
		
		return $searchOption;
	}
	/**
	 * Receive search criteria from filter row and return the search result to front-end.
	 */
	public function getRowListByConditionsInFilterRow()	{
		
		/**
		 * Create form for manipulate search.
		 */
		$formResponse = new formResponse($this->input->post(null,true));
		
		/**
		 * Put session values on formResponse for future use ,such as logging, or permission check.
		 */
		$formResponse->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		/**
		 * Return search result to back-end
		 */
		$response['searchResults'] = $formResponse->searchResults();
		//$response['_request'] = $_REQUEST; //just for view response in inspection
		echo json_encode($response);		
	}
	
	/**
	 * Get HTML for send to entity_view.php
	 * @return array 
	 */
	private function getViewData(){
		/**
		 * Load all  entity recipes info for creating left menu purpose.
		 */
		$allEntityRecipes = $this->entityRecipes->getRecipes();		
		
		/**
		 * Load and create user object for creating left menu, and display user name in entity_view.php.
		 */		
		$this->load->library('users/UsersOfcientity');
		$user = new UsersOfcientity;
		$user->init($this->session['userName'],$allEntityRecipes);
		
		/**
		 * Create left menu according to grant to view entity of user.
		 */
		$userMenus = $user->rtMenues();		
		$viewData['menus'] = $userMenus;
		$viewData['userInfo'] = $this->session;		
		return $viewData;
	}
	
	/**
	 *  Perform save new record or edit record from main-entity interface. When the operation is finished, it return result of 
	 * operation back to front-end.
	 */
	public function saveAddEditData(){
		
		/**
		 * Create new formResponse Object by passing the $_POST to its constructor. 
		 * The method __setSession performs put session values on formResponse for future use ,such as logging, or permission check.
		 */		
		$formResponse = new formResponse($this->input->post(null,true));
		$formResponse->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		/**
		 * Perform save the data, and return the saving result to back-end.
		 */
		$response['results'] = $formResponse->saveAddEditData();
		//$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	/**
	 *  Perform delete record from main-entity interface. When the operation is finished, it return result of 
	 * operation back to front-end.
	 */
	public function deleteData(){
		
		/**
		 * Create new formResponse Object by passing the $_POST to its constructor. 
		 * The method __setSession performs put session values on formResponse for future use ,such as logging, or permission check.
		 */
		$formResponse = new formResponse($this->input->post(null,true));
		$formResponse->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		/**
		 * Perform delete data, and return the deleting result to back-end.
		 */
		$response['results'] = $formResponse->deleteData();
		//$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	/**
	 * In case of user select record to edit, in filter-row's search results, the method perform load saved data and send to fill in add/edit form.
	 */
	public function loadDataToEditInModal(){
		/**
		 * Create new form Object and store in $formResponse.
		 */
		$formResponse = new formResponse($this->input->post(null,true));
		
		/**
		 * Perform load data and send back to frond-end
		 */
		$response['results'] = $formResponse->loadDataToEditInModal();
		echo json_encode($response);
	}
	
	/**
	 * Perform load data from sub-entity of main-entity and send back to display in data-table in sub-entity panel.
	 * This method will be called when user click view/edit on each row of search result or will be called when user click
	 * the navigate bar (Navbar) of each sub-entity.
	 */
	public function loadDataToSubEntityTable(){
		
		/**
		 * Create mainFormRequest Object, including send a session info to store in its session property for future use
		 */		
		$mainForm = new formResponse($this->input->post('mainEntityInfo',true));
		$mainForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
		/**
		 * Fetch entity recipes of main-entity
		 */
		$mainFormLibExtraInfo = $mainForm->_getCurrentEntityRecipes();
		
		/**
		 * if subEntity of $mainform is declared:
		 * - Create subForm object, and send session info to use in logging or permission check
		 * - perform fetch subForm data
		 */
		if(isset($mainFormLibExtraInfo['addEditModal']['subEntity'])){
			$subEntityInfo = $mainForm->getIdFieldValueAndSubEntityInfo();			
			$subForm = new formResponse($this->input->post('subEntityInfo',true));
			$subForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
			$response['subEntityResults'] = $subForm->searchResultsForSubEntity($subEntityInfo);
		}else{
			$response['subEntityResults'] ='';
		}		
		
		#$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	/**
	 * This method performs insert new record to sub-entity table. It occurred when user submit request to add from sub-entity
	 */
	public function insertFromSubEntity(){
		
		/**
		 * $response is variable to store insertion result
		 */
		$response = [];
		
		/**
		 * Store cleaned $_REQUEST to $request
		 */
		$request = $this->input->post(null,true);
		
		/**
		 * Get informations that represented how main-entity related to sub-entity
		 */
		list ($mainEntityIdForSubEntity, $mainRefTo) = $this->_getMainEntityInfo($request);		
		
		/**		 
		 * - Create subForm object, and send session info to use in logging or permission check
		 * - perform fetch subForm data
		 */
		$subEntityRequestInfo = $request['subEntityInfo'];
		$subForm = new formResponse($subEntityRequestInfo);
		$subForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์	
		
		/**
		 * Get the field name of sub-entity that must be used to link to main-entity
		 */
		$subEntityName = $subForm->libName();		
		$linkField = $this->_getLinkField($mainRefTo, $subEntityName);		
		
		/**
		 * Preparing sub-entity, such as ordering of column
		 */
		$this->_prepareSubFormForInsert($subForm, $linkField, $mainEntityIdForSubEntity);
		
		/**
		 * Perform insert record
		 */
		$response['results'] = $subForm->saveAddEditData();
		//$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);		
	}
	
	/**
	* Get informations that represented how main-entity related to sub-entity
	*/
	private function _getMainEntityInfo($request){
		
		$mainEntityRequestInfo = $request['mainEntityInfo'];
		
		/**
		 * Create mainForm object, and send session info to use in logging or permission check
		 */		
		$mainForm = new formResponse($mainEntityRequestInfo);
		$mainForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
			
		/*		  
		 * $mainEntityIdForSubEntity stores the key of $mainEntityRequestInfo that represent the id of main-entity.
		 * For instance, the follwing is submited $mainEntityRequestInfo 
		 *	array (size=9)
		 *		0 => string '1' (length=1) =>this key is id of main entity
		 *		1 => string '11' (length=2)
		 *		2 => string '26/08/2018' (length=10)
		 *		3 => string '3' (length=1)
		 *		4 => string '3' (length=1)
		 *		5 => string 'Class for new staff, started work on Jul 2018' (length=45)
		 *		6 => string '13' (length=2)
		 *		'entityOrdinal' => string '0' (length=1)
		 *		'operation' => string '0' (length=1)
		 * 
		 *  then the $mainEntityIdForSubEntity value is 0
		 *  The description how $mainRefTo means, please the description of property columnRefKeyTo in entity.php in APPPATH/libraries/entity. 
		 */
		$mainEntityIdForSubEntity = $mainForm->getIdToInsertForSubEntity();		
		$mainRefTo = $mainForm->libObjectInfo()->columnRefKeyTo;
		
		return [$mainEntityIdForSubEntity,$mainRefTo];
	}
	
	/**
	 * $linkField variable is foreign key field name. CI-Entity use it for link between two table, main-entity and sub-entity.
	 * @param array $mainRefTo
	 * @param string $subFormLibName
	 * @return string
	 */
	private function _getLinkField($mainRefTo, $subFormLibName){
		
		/**
		 * Loop through $mainRefTo until the value of 'referenced_to_libName' key is equal to $subFormLibName
		 */
		foreach($mainRefTo as $column){
			if($column['referenced_to_libName']==$subFormLibName){
				//หาก ชื่อ sub entity เท่ากับ ตัวใดตัวหนึ่งของ reference key นั่นคือ table รองลิ้งค์กับ table หลักด้วยฟิลด์นั้น 
				$linkField = $column['referenced_to_column'];
				break;
			}
		}
		
		/**
		 * If $linkField never been declared ($column['referenced_to_libName']==$subFormLibName not occurred in  above loop)
		 * , then halt the operation including send back the warning message to back-end.
		 */
		if(!(isset($linkField))){ //หากไม่มี link field ต้องหยุดโปรแกรมพร้อมแจ้งเตือน
			$response=[];
			$response['results']['notifications']['danger']=["Error Link field between main-entity and sub-entity is not found , linkField not found in columnRefKeyTo "];
			echo json_encode($response);
			exit;
		}
		
		return $linkField;
	}
	
	/**	
	 * Prepare sub-entity, such as ordering of column	
	 * @param Object $subForm
	 * @param string $linkField
	 * @param int $mainEntityIdForSubEntity
	 */
	private function _prepareSubFormForInsert(&$subForm, $linkField, $mainEntityIdForSubEntity){
		
		/**		
		 * get order of column of sub-form(sub-entity)
		 */
		list($columnsWithOrdered)=$subForm->p_getColumnOrdered();				
		
		/**
		 * The sub-entity interface for add new record allow suppressing some filed, not create input tag.
		 * The suppression of some field may cause sequence of field to construct SQL insert disordered.
		 * 
		 * This part of method adjusts the ordering of  $_REQUEST key and value to suit insert SQL composing of sub-entity. 
		 */
		$index = 0;
		foreach(array_keys($columnsWithOrdered) as $key){
			if($key=='id'){
				$subForm->_request[$index] = ''; //หลอกตัว insert ไม่ให้ error Message: Undefined offset: 0, Filename: forms/mainForms.php
				$subForm->_setRequestME($index,''); //หลอกตัว insert ไม่ให้ error Message: Undefined offset: 0, Filename: forms/mainForms.php
			}
			if($linkField==$key){
				$subForm->_request[$index] = $mainEntityIdForSubEntity; //กำหนดค่าให้ $_REQUEST ลำดับที่ index มีค่าเท่ากลับ ฟิลด์ id ของ mainEntity 				
				$subForm->_setRequestME($index,$mainEntityIdForSubEntity); //กำหนดค่าให้ $_REQUESTM และ $_REQUESTE  ลำดับที่ index มีค่าเท่ากลับ ฟิลด์ id ของ mainEntity 				
				break;
			}
			$index++;
		}
	}
	
	/**
	 * Perform update record that submitted from sub-entity edit input.
	 */
	public function updateSubEntityRecord(){
		
		/**		 
		 * - Create form object, and send session info to use in logging or permission check
		 * - perform fetch form data
		 */
		$form = new formResponse($this->input->post(null,true));
		$form->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		/**
		 * Perform update
		 */
		$response['results'] = $form->updateSubEntityRecord();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	/**
	 * Response in case of session is dead, or user is not logged in.
	 */
	private function responseNotLoggedIn(){
		$response = [];
		$response['results']['notifications']['danger']=["Unable to determine user information, please login again =&gt;<a href='".base_url()."user/loginform'>Login</a>"];
		echo json_encode($response);
	}
}
