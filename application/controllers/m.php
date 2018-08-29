<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/extraEntityInfos.php');
require_once(APPPATH.'libraries/forms/mainForms.php');
require_once(APPPATH.'libraries/forms/formResponse.php');

class M extends CI_Controller {
	//data-toggle=\"modal\" data-target=\"#cientityPageLoaderModal\"
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 public function __construct()
	 {
		parent::__construct();
		$this->session = $this->session->userdata('cientity_logged_in');
		if(!(isset($this->session))) 
		{
			if(in_array($this->uri->segment(2), array('m','e',''))){
				redirect(base_url().'user/loginform');
				exit;
			}else{
				$this->responseNotLoggedIn();
				exit;
			}
		}
	 }
	public function index()	{
		$viewData = self::getViewData();
		$this->load->view('main_view',$viewData);
	}
	public function e(){
		$taskId = $this->uri->segment(3);		
		$entityName = extraEntityInfos::getEntityName($taskId);
				
		
		if($entityName=='404'){
			
		}else{	
			$viewData = self::getViewData();
			$viewData['activeMenuItem'] = $taskId;
			
			$viewData['header_JS_CSS'] = (isset(extraEntityInfos::infos[$entityName]['header_JS_CSS']))?extraEntityInfos::infos[$entityName]['header_JS_CSS']:extraEntityInfos::default_header_JS_CSS;
			$viewData['footer_JS_CSS'] = (isset(extraEntityInfos::infos[$entityName]['footer_JS_CSS']))?extraEntityInfos::infos[$entityName]['footer_JS_CSS']:extraEntityInfos::default_footer_JS_CSS;
			$viewData['entityThDescription']= (isset(extraEntityInfos::infos[$entityName]['descriptions']))?extraEntityInfos::infos[$entityName]['descriptions']:"extraEntityInfos[{$entityName}].descriptions not exists";
			
			$forms = new mainForms($entityName);
			$viewData['filterRow'] = $forms->createFilterRow();
			
			$viewData['addEditModal'] = $forms->createAddEditModal();
			
			$this->load->view('entity_view',$viewData);
		}
	}
	public function infoForAjaxOptions(){
		$entityInfo = extraEntityInfos::infos;		
		$property = explode("_", $this->uri->segment(3));
		$property[0] = (int) $property[0];
		$i = 0;
		foreach($entityInfo as $key=>$val){
			if ($property[0]==$i){
				$entityName=$key;
				break;
			}
			$i++;
		}
		$forms = new mainForms($entityName);
		
		$cookieName = 'infoForAjaxOptions_'.$this->uri->segment(3);
		$_request = $this->input->post(null,true);
		if(isset($_request['q'])){
			$searchOption = $_request['q'];
			setcookie($cookieName,$_request['q'] ,time()+3600);
		}else{
			$searchOption = isset($_COOKIE[$cookieName])?$_COOKIE[$cookieName]:'';
		}
		
		$response = $forms->infoForAjaxOptions($this->uri->segment(3), $searchOption);
		echo json_encode($response);
	}
	public function infoForAjaxAddEditModalOptions()
	{
		$entityInfo = extraEntityInfos::infos;		
		$property = explode("_", $this->uri->segment(3));
		$property[0] = (int) $property[0];
		$i = 0;
		foreach(array_keys($entityInfo) as $key){
			if ($property[0]==$i){
				$entityName=$key;
				break;
			}
			$i++;
		}
		$forms = new mainForms($entityName);
		
		$cookieName = 'infoForAjaxOptions_'.$this->uri->segment(3);
		
		$_request = $this->input->post(null,true);
		
		if(isset($_request['q'])){
			$searchOption = $_request['q'];
			setcookie($cookieName,$_request['q'] ,time()+3600);
		}else{
			$searchOption = isset($_COOKIE[$cookieName])?$_COOKIE[$cookieName]:'';
		}
		
		$response = $forms->infoForAjaxAddEditModalOptions($this->uri->segment(3), $searchOption);
		echo json_encode($response);
	}
	public function getRowListByConditionsInFilterRow()
	{
		$formResponse = new formResponse($this->input->post(null,true));
		$response['searchResults'] = $formResponse->searchResults();
		//$response['_request'] = $_REQUEST; //เอาไว้ดูเฉยๆ 
		echo json_encode($response);		
	}
	private function getViewData()
	{
		//$this->load->library('extraEntityInfos');
		$extraEntityInfoDesc = extraEntityInfos::getAllDescriptions();		
		$this->load->library('users/UsersOfcientity');
		$user = new UsersOfcientity;
		$user->init($this->session['userName'],$extraEntityInfoDesc);
		$userMenus = $user->rtMenues();		
		$viewData['menus'] = $userMenus;
		$viewData['userInfo'] = $this->session;		
		return $viewData;
	}	
	public function saveAddEditData(){
		$formResponse = new formResponse($this->input->post(null,true));
		$formResponse->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		$response['results'] = $formResponse->saveAddEditData();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	public function deleteData(){
		$formResponse = new formResponse($this->input->post(null,true));
		$formResponse->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์
		
		$response['results'] = $formResponse->deleteData();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}	
	public function loadDataToEditInModal(){
		$formResponse = new formResponse($this->input->post(null,true));
		$response['results'] = $formResponse->loadDataToEditInModal();
		echo json_encode($response);
	}
	public function loadDataToSubModalTable(){
		//$mainFormRequest = $_REQUEST['mainEntityInfo'];		
		$mainFormRequest = $this->input->post('mainEntityInfo',true);	
		$mainForm = new formResponse($mainFormRequest);
		$mainForm->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
		$mainFormLibExtraInfo = $mainForm->_getLibExtraInfo();
		
		//if $mainform Contains subForm
		if(isset($mainFormLibExtraInfo['addEditModal']['subModal'])){
			$subModalInfo = $mainForm->getIdFieldValueAndSubModalInfo();
			//$subFormRequest = $_REQUEST['subEntityInfo'];
			$subFormRequest = $this->input->post('subEntityInfo',true);	
			$subForm = new formResponse($subFormRequest);
			$subForm->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
			$response['subModelResults'] = $subForm->searchResultsForSubModel($subModalInfo);
		}				
		
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
	private function responseNotLoggedIn(){
		$response = [];
		$response['results']['notifications']['danger']=["ไม่สามารถระบุผู้ใช้งานได้, กรุณาเข้าสู่ระบบอีกครั้งที่นี่ =&gt;<a href='".base_url()."user/loginform'>เข้าสู่ระบบ</a>"];
		echo json_encode($response);
	}
	public function insertFromSubEntity(){
		$hereRequest = $this->input->post(null,true);
		$_REQUESTM = $hereRequest['mainEntityInfo'];	
		$mainForm = new formResponse($_REQUESTM);
		$mainForm->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์		
		
		//$mainEntityIdForSubEntity คือ ค่าฟิลด์ id ของ mainEntity ที่ POST มา ใน  $_REQUEST['mainEntity'] เช่น หาก mainEntity เป็น devClasses นี่คือฟิลด์ id ของ devClasses
		$mainEntityIdForSubEntity = $mainForm->getIdToInsertForSubEntity();
		
		$mainRefTo = $mainForm->libObjectInfo()->columnRefKeyTo;
		
		$_REQUESTS = $hereRequest['subEntityInfo'];
		$subForm = new formResponse($_REQUESTS);
		$subForm->session = $this->session; //ส่งค่า session เพื่อเอาไว้ใช้ในกรณีต่างๆ เช่น บันทึก logs หรือเช็คสิทธิ์	
		$subFormLibName = $subForm->libName();		
		foreach($mainRefTo as $column){
			if($column['referenced_to_libName']==$subFormLibName){
				//หาก ชื่อ sub entity เท่ากับ ตัวใดตัวหนึ่งของ reference key นั่นคือ table รองลิ้งค์กับ table หลักด้วยฟิลด์นั้น 
				$linkField = $column['referenced_to_column'];break;
			}
		}

		$response = [];
		if(!(isset($linkField))){ //หากไม่มี link field ต้องหยุดโปรแกรมพร้อมแจ้งเตือน
			$response['results']['notifications']['danger']=["ไม่พบ link field ระหว่าง mainForm กับ subForm , linkField not found in columnRefKeyTo "];
			echo json_encode($response);exit;
		}
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
		
		$response['results'] = $subForm->saveAddEditData();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);		
	}
	public function updateSubEntityRecord(){
		//var_dump($_REQUEST);
		//$form = new formResponse($_REQUEST);
		$form = new formResponse($this->input->post(null,true));
		$response['results'] = $form->updateSubEntityRecord();
		$response['_request'] = $this->input->post(null,true); //เอาไว้ดูเฉยๆ 
		echo json_encode($response);
	}
}
