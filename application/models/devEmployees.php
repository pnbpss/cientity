<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DevEmployees extends CI_Model {
	function __construct(){	
		parent::__construct();
		$this->load->library(get_class($this));
		$obj = new get_class($this);
	}
	
}
