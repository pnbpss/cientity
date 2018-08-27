<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LibrariesController extends CI_Controller {

	public function index()
	{
		
	}
	public function getLibraryInfo($libName)
	{	
		
		//$libName = 'devClasses';
		//$libName = 'devSubjects';
		//$libName = 'devExpenseTypes';
		//$libName = 'devClassBudgets';
		//$libName = 'devEmployees';
		$obj = $this->loadLibrary($libName);
					
		//echo $obj->returnTableName().', and type is '.$obj->type;
		//var_dump($obj->columnListInfo);
		//var_dump($obj->columnRefKey);		
		//var_dump($obj->entityInterfaces['forInsert']);		
		//var_dump($obj->columnDescriptions);
		//var_dump($obj->revisedColumnDescriptions);		
		//var_dump($obj->entityInterfaces['forFrontEnd']);		
		//echo $obj->insertSqlString();		
		
		//var_dump(debug_backtrace());
		//var_dump(explode('||','||asdf'));
		
		return $obj;
		
		//$this->load->view('welcome_message');
	}
	private function loadLibrary($libName)
	{
		$this->load->library($libName);
		$obj = new $libName;		
		return $obj;
	}
}
