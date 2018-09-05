<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/extraEntityInfos.php');
require_once(APPPATH.'libraries/forms/mainForms.php');
require_once(APPPATH.'libraries/forms/formResponse.php');

/**
 * M class is main controller of entire CI-Entity. It is interface of all connection from front-end.
 */
class M extends CI_Controller {

	/**
	 * The constructor only check that user is logged in or not
	 */
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
	 }
	/**
	 * Display the home page.
	 */
	public function index(){		
		$viewData = self::getViewData();
		$this->load->view('main_view',$viewData);
	}
	/**
	 * Display the page according to $this->uri->segment(3), the entityOrdinal.
	 */
	public function e(){
		$entityOrdinal = $this->uri->segment(3);	
		
		$entityName = extraEntityInfos::getEntityName($entityOrdinal);
		
		$forms = new mainForms($entityName);
		
		$formExtraInfo = $forms->_getLibExtraInfo();
		
		$viewData = $this->_getEntityViewData($entityOrdinal, $entityName, $forms, $formExtraInfo);
		
		$this->load->view('entity_view',$viewData);
	}
	
	private function _getEntityViewData($entityOrdinal, $entityName, $forms, $formExtraInfo){
		
		$viewData = self::getViewData();			

		$viewData['header_JS_CSS'] = (isset($formExtraInfo['header_JS_CSS']))?$formExtraInfo['header_JS_CSS']:extraEntityInfos::default_header_JS_CSS;
		$viewData['footer_JS_CSS'] = (isset($formExtraInfo['footer_JS_CSS']))?$formExtraInfo['footer_JS_CSS']:extraEntityInfos::default_footer_JS_CSS;
		$viewData['entityThDescription']= (isset($formExtraInfo['descriptions']))?$formExtraInfo['descriptions']:"extraEntityInfos[{$entityName}].descriptions not exists";
		
		 $viewData['activeMenuItem'] = $entityOrdinal;
		
		/**
		 * If the entity is not 'customized', normal entity that create under rules of CI-Entity
		 */		
		if(!((isset($formExtraInfo['customized'])) && ($formExtraInfo['customized']===true))){			
			$viewData['filterRow'] = $forms->createFilterRow();	
			$viewData['addEditModal'] = $forms->createAddEditModal();
			$viewData['customizedEntity'] = false;			
		}
		/**
		 * If not 'customized, then load do the method in the customized entity in [entityName].php
		 */
		else{			
			$viewData['filterRow'] = $forms->libObject->createFilterRow();	
			$viewData['addEditModal'] = $forms->libObject->createSearchResultZone();
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
		$entityName = extraEntityInfos::entityNameByURISegment($this->uri->segment(3));
		
		/**
		 * Create forms object for perform search.
		 */
		$forms = new mainForms($entityName);	
		
		/**
		 * get searchOption and keep search key word in cookies.
		 */
		$searchOption = $this->keepSelect2KeywordInCookie();
		
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
		$entityName = extraEntityInfos::entityNameByURISegment($this->uri->segment(3));
		
		/**
		 * Create forms object for perform search.
		 */
		$forms = new mainForms($entityName);	
		
		/**
		 * get searchOption and keep search key word in cookies.
		 */
		$searchOption = $this->keepSelect2KeywordInCookie();
		
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
	private function keepSelect2KeywordInCookie(){
		$cookieName = 'infoForAjaxOptions_'.$this->uri->segment(3);		
		$_request = $this->input->post(null,true);				
		if(isset($_request['q'])){
			$searchOption = $_request['q'];
			$cookie = ['name'=>$cookieName, 'value'=>$_request['q'],'expire'=>'86500'];
			$this->input->set_cookie($cookie);			
		}else{			
			$searchOption = $this->input->cookie($cookieName, TRUE)?$this->input->cookie($cookieName, TRUE):'';
		}
		return $searchOption;
	}
	/**
	 * Receive search criteria from filter row and return the seach result to front-end.
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
		 * Load all extra entity info for creating left menu purpose.
		 */
		$extraEntityInfoDesc = extraEntityInfos::getAllDescriptions();		
		
		/**
		 * Load and create user object for creating left menu, and display user name in entity_view.php.
		 */		
		$this->load->library('users/UsersOfcientity');
		$user = new UsersOfcientity;
		$user->init($this->session['userName'],$extraEntityInfoDesc);
		
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
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	/**
	 * In case of user select record to select, in filter-row's search results, the method perform load saved data and send to fill in add/edit form.
	 */
	public function loadDataToEditInModal(){
		$formResponse = new formResponse($this->input->post(null,true));
		$response['results'] = $formResponse->loadDataToEditInModal();
		echo json_encode($response);
	}
	public function loadDataToSubEntityTable(){
		
		$mainFormRequest = $this->input->post('mainEntityInfo',true);	
		$mainForm = new formResponse($mainFormRequest);
		$mainForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
		$mainFormLibExtraInfo = $mainForm->_getLibExtraInfo();
		
		//if $mainform Contains subForm
		if(isset($mainFormLibExtraInfo['addEditModal']['subEntity'])){
			$subEntityInfo = $mainForm->getIdFieldValueAndSubEntityInfo();
			//$subFormRequest = $_REQUEST['subEntityInfo'];
			$subFormRequest = $this->input->post('subEntityInfo',true);	
			$subForm = new formResponse($subFormRequest);
			$subForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
			$response['subEntityResults'] = $subForm->searchResultsForSubEntity($subEntityInfo);
		}				
		
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}	
	public function insertFromSubEntity(){
		
		$response = [];
		
		$hereRequest = $this->input->post(null,true);
		
		list ($mainEntityIdForSubEntity, $mainRefTo) = $this->_getMainEntityInfo($hereRequest);		
		
		$_REQUESTS = $hereRequest['subEntityInfo'];
		$subForm = new formResponse($_REQUESTS);
		$subForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์	
		
		$subFormLibName = $subForm->libName();		
		
		$linkField = $this->_getLinkField($mainRefTo, $subFormLibName);		
		
		$this->_prepareSubForForInsert($subForm, $linkField, $mainEntityIdForSubEntity);
		
		$response['results'] = $subForm->saveAddEditData();
		//$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);		
	}
	private function _getMainEntityInfo($hereRequest){
		
		$_REQUESTM = $hereRequest['mainEntityInfo'];	
		$mainForm = new formResponse($_REQUESTM);
		$mainForm->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
		//$mainEntityIdForSubEntity คือ ค่าฟิลด์ id ของ mainEntity ที่ POST มา ใน  $_REQUEST['mainEntity'] เช่น หาก mainEntity เป็น devClasses นี่คือฟิลด์ id ของ devClasses
		$mainEntityIdForSubEntity = $mainForm->getIdToInsertForSubEntity();		
		$mainRefTo = $mainForm->libObjectInfo()->columnRefKeyTo;
		
		return [$mainEntityIdForSubEntity,$mainRefTo];
	}
	
	private function _getLinkField($mainRefTo, $subFormLibName){
		foreach($mainRefTo as $column){
			if($column['referenced_to_libName']==$subFormLibName){
				//หาก ชื่อ sub entity เท่ากับ ตัวใดตัวหนึ่งของ reference key นั่นคือ table รองลิ้งค์กับ table หลักด้วยฟิลด์นั้น 
				$linkField = $column['referenced_to_column'];
				break;
			}
		}
		
		if(!(isset($linkField))){ //หากไม่มี link field ต้องหยุดโปรแกรมพร้อมแจ้งเตือน
			$response=[];
			$response['results']['notifications']['danger']=["Error Link field between main-entity and sub-entity is not found , linkField not found in columnRefKeyTo "];
			echo json_encode($response);
			exit;
		}
		
		return $linkField;
	}
	
	private function _prepareSubForForInsert(&$subForm, $linkField, $mainEntityIdForSubEntity){
		//ไปหาว่า $linkFieldนั้น เป็น key ไอดีที่เท่าไหร่
		list($columnsWithOrdered)=$subForm->p_getColumnOrdered();
		//var_dump($columnsWithOrdered);
		$index = 0;
		foreach(array_keys($columnsWithOrdered) as $key){
			if($key=='id'){
				$subForm->_REQUEST[$index] = ''; //หลอกตัว insert ไม่ให้ error Message: Undefined offset: 0, Filename: forms/mainForms.php
				$subForm->_setRequestME($index,''); //หลอกตัว insert ไม่ให้ error Message: Undefined offset: 0, Filename: forms/mainForms.php
			}
			if($linkField==$key){
				$subForm->_REQUEST[$index] = $mainEntityIdForSubEntity; //กำหนดค่าให้ $_REQUEST ลำดับที่ index มีค่าเท่ากลับ ฟิลด์ id ของ mainEntity 				
				$subForm->_setRequestME($index,$mainEntityIdForSubEntity); //กำหนดค่าให้ $_REQUESTM และ $_REQUESTE  ลำดับที่ index มีค่าเท่ากลับ ฟิลด์ id ของ mainEntity 				
				break;
			}
			$index++;
		}
	}
	
	public function updateSubEntityRecord(){
		//var_dump($_REQUEST);
		//$form = new formResponse($_REQUEST);
		$form = new formResponse($this->input->post(null,true));
		$form->_setSession($this->session); //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		$response['results'] = $form->updateSubEntityRecord();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	
	private function responseNotLoggedIn(){
		$response = [];
		$response['results']['notifications']['danger']=["Unable to determine user information, please login again =&gt;<a href='".base_url()."user/loginform'>Login</a>"];
		echo json_encode($response);
	}
}
