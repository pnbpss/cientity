<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_controller{
	public function __construct()
	{
		parent::__construct();
	}	
	public function loginform()
	{
		$session = $this->session->userdata('cientity_logged_in');
		if(isset($session))
		{
			redirect(base_url());
		}else{
			$viewData['loginErrorMessage']="";
			if($this->uri->segment(3)=='code01'){
				$viewData['loginErrorMessage']="<div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>ผิดพลาด!</strong>ไม่สามารถเข้าสู่ระบบได้</div>";
			}
			$this->load->view('login_view',$viewData);
		}
	}
	public function login()
	{
		$Username = trim($this->input->post('Username',true));
		$Password = strtoupper(MD5(trim($this->input->post('Password',false))));
		$validateUserResult = $this->validateUser($Username,$Password);
		if($validateUserResult['status']!='F')
		{
			redirect(base_url().'');
		}
		else
		{
			redirect(base_url().'user/loginform/code01');
		}
	}	
	private function validateUser($U, $P){
		$arr = array('status'=>'F','msg'=>'','html'=>'');
		$Username = trim($U);		
		$Password = $P;
		
		if($Username == ''){
			$arr['status'] = 'F';
			$arr['msg']	   = 'error:';			
			return $arr;
		}
		if($Password == ''){
			$arr['status'] = 'F';
			$arr['msg']	   = 'error:';			
			return $arr;
		}		
		$sql = "
			select a.*,c.groupstatus,isnull(d.em_status,'') as em_status,d.employeeCode
			from {$this->db->dbprefix}sysUsers a
			left join {$this->db->dbprefix}sysUserGroups c on a.groupuser=c.groupuser
			left join {$this->db->dbprefix}devEmployees d on a.userName=d.employeeCode
			where lower(a.userName) = lower('{$Username}') and a.PW='{$Password}'
		";
		$query = $this->db->query($sql);				
		$row = $query->result();		
		if(isset($row[0]->userName)){			
			$arr['status'] = 'S';
			$sess_array = array();
			$rowArray = (array) $row[0];
			foreach($rowArray as $key => $val){ $sess_array[$key] = $val; }
			$this->session->sess_expiration = '1';
			$this->session->set_userdata('cientity_logged_in', $sess_array);					
		}else{
			$arr['status'] = 'F';
			$arr['msg'] = 'ล้มเหลว ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';					
		}		
		return $arr;
	}
	public function logout(){
		$this->session->set_userdata('cientity_logged_in',null);
		redirect(base_url().'user/loginform/');
	}
}