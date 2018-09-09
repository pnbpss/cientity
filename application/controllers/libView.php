<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LibView extends CI_Controller {
	
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
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function testSomeThing()
	{	
		//$this->load->library('form_validation');
		//$libName = 'devClassInstructors';
		//$libName = 'penaltyConfigs';
		//$libName = 'devExpenseTypes';
		//$libName = 'devClassBudgets';
		$libName = 'devEmployees';
		////$libName = 'devLocations';
		//$libName = 'devClassExtInstructors';
		//$libName = 'devExtInstructors';
		//$libName = 'devSubjects';
		//$libName = 'devClasses';
		//$libName = 'devClassEnrollists';
		//$libName = 'devSubjectCourse';
		//$libName = 'devSubDistrictsView';
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$obj = $this->_loadLibrary($libName);
		echo $obj->returnTableName().', and type is '.$obj->ObjectType." and libName is ".$obj->shortName." called from ".$ip." named ".$_SERVER['SERVER_NAME']."<br />"."<br />";
		echo 'columnListInfo'; var_dump($obj->columnListInfo);
		echo 'columnRefKeyFrom'; var_dump($obj->columnRefKeyFrom);		
		echo 'syncedColumnlistInfoWithRefKey'; var_dump($obj->syncedColumnlistInfoWithRefKey);
		echo 'columnRefKeyTo'; var_dump($obj->columnRefKeyTo);		
		echo 'columnDescriptions'; var_dump($obj->columnDescriptions);
		echo 'columnDescriptionsColumnIndexed'; var_dump($obj->columnDescriptionsColumnIndexed);
		echo 'revisedColumnDescriptions'; var_dump($obj->revisedColumnDescriptions);				
		echo 'stdValidationRules'; var_dump($obj->stdValidationRules);		
		echo 'insertSqlString'; echo $obj->insertSqlString();
		
		//$obj->_insert(array());
		
		//echo "<pre>".$libName::dirInfo()."</pre>";
		
		//echo 'dummy is '.$obj->dummy;
		
		//var_dump(debug_backtrace());
		//var_dump(explode('||','||asdf'));
		
		//$this->load->view('welcome_message');
		//$this->load->helper('form_validation');
		
		//$this->reOrderArrayWithAdditionalOrdering();
		
		//echo "===".preg_replace("/\|+/", '|', "abcde|||||||efghi")."===";
	}
	private function reOrderArrayWithAdditionalOrdering()
	{
		$original = [						
			0=>'a'
			,1=>'b'
			,2=>'c'
			,3=>'d'
			,4=>'e'
			,5=>'f'
			,6=>'g'
			,7=>'h'			
		];
		$originalKeep = $original;
		$tempOriginal = [];
		$i=1;
		foreach($original as $val)
		{
			$tempOriginal[$i] = $val;
			$i++;
		}
		$additional = [
			1=>'g'
			,3=>'a'
			,4=>'c'			
		];
		
		//$newArray = array_merge($original, $additional);
		$newArray = [];
		$tempArray = [];
		$arraySize = sizeof($original);
		
		foreach($additional as $key=>$val)
		{
			
			$index=array_search($val, $tempOriginal);
			echo "index=".$index." key=".$key." val=".$val."<br />";
			if($index)			
			{	
				echo "index=".$index." key=".$key." val=".$val."<br />";
				unset($tempOriginal[$index]);
				
			}
			
		}
		reset($tempOriginal);
		$original = [];
		$i=0;
		foreach($tempOriginal as $val)
		{
			$original[$i] = $val;
			$i++;
		}
		
		reset($additional);
		
		for($indexOfAll = 0; $indexOfAll<$arraySize; $indexOfAll++)
		{
			if(isset($additional[$indexOfAll]))
			{
				array_push($newArray,$additional[$indexOfAll]);
			}
			if(isset($tempOriginal[$indexOfAll]))
			{
				array_push($newArray,$tempOriginal[$indexOfAll]);
			}
			
		}
		
		var_dump($originalKeep);
		//var_dump($tempOriginal);
		var_dump($additional);
		var_dump($newArray);
		
	}
	private function _loadLibrary($libName)
	{			
		$this->load->library('custom/'.$libName);
		//echo "<pre>".$libName::fileInfo()."</pre>";
		$obj = new $libName;		
		return $obj;
	}
	public function loadLibrary($libName){
		$this->load->library('custom/'.$libName);		
		$obj = new $libName;		
		return $obj;
	}
	public function viewTestData(){
		$this->load->library('testData');
		$obj = new testData;
		$data = $obj->provider01();
		var_dump($data);
	}
	public function dumpSession()
	{
		$ses = $this->session->userdata(USER_INFO_SESSION_KEY);
		var_dump($ses);
		/*
		if(isset($ses)){
			foreach($ses as $key => $val){
				$this->sess[$key] = $val;
				echo $key."=&gt; ".$val."<br />";
			}
		}else{
			echo "There is no session id logged_in;";
		}*/
	}
}
