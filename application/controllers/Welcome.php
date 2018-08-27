<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		//$this->load->view('welcome_message');
		$this->load->view('main_view');
	}
	public function testSomeThing()
	{	
		
		$libName = 'devClassInstructors';
		//$libName = 'penaltyConfigs';
		//$libName = 'devExpenseTypes';
		//$libName = 'devClassBudgets';
		//$libName = 'devEmployees';
		$obj = $this->loadLibrary($libName);
		echo $obj->returnTableName().', and type is '.$obj->type;
		//var_dump($obj->columnListInfo);
		var_dump($obj->columnRefKeyTo);		
		var_dump($obj->entityInterfaces['forInsert']);		
		//var_dump($obj->columnDescriptions);
		var_dump($obj->columnDescriptionsColumnIndexed);
		//var_dump($obj->revisedColumnDescriptions);		
		//var_dump($obj->entityInterfaces['forFrontEnd']);
		
		//echo $obj->insertSqlString();
				
		//var_dump(debug_backtrace());
		//var_dump(explode('||','||asdf'));
		
		//$this->load->view('welcome_message');
	}
	private function loadLibrary($libName)
	{
		$this->load->library($libName);
		$obj = new $libName;		
		return $obj;
	}
}
