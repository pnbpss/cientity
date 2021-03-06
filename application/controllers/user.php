<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_controller{
	public function __construct()
	{
		parent::__construct();
	}	
	public function loginform()
	{
		$session = $this->session->userdata(USER_INFO_SESSION_KEY);
		if(isset($session))
		{
			redirect(base_url());
		}else{
			$viewData['loginErrorMessage']="";
			if($this->uri->segment(3)=='code01'){
				$viewData['loginErrorMessage']="<div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>Error!</strong>, Unable to logged in, try again.</div>";
			}
			$remoteip = $this->input->ip_address();
			$sql = "insert into {$this->db->dbprefix}sysUserLoginLogs (userName,loggedInAt,LoginResult,ipAddress) values ('_loginPage_',getdate(),'_in_','{$remoteip}');";
			$this->db->query($sql);
			$this->load->view('login_view',$viewData);
		}
	}
	public function login()
	{
		$Username = trim($this->input->post('Username',true));
		$Password = strtoupper(MD5(trim($this->input->post('Password',false))));
		$validateUserResult = $this->validateUser($Username,$Password);
		$remoteip = $this->input->ip_address();
		if($validateUserResult['status']!='F'){
			$sql = "insert into {$this->db->dbprefix}sysUserLoginLogs (userName,loggedInAt,LoginResult,ipAddress) values ('{$Username}',getdate(),'pass','{$remoteip}');";
			$this->db->query($sql);
			redirect(base_url().'');
		}else{
			$sql = "insert into {$this->db->dbprefix}sysUserLoginLogs (userName,loggedInAt,LoginResult,ipAddress) values ('{$Username}',getdate(),'fail','{$remoteip}');";
			$this->db->query($sql);
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
			select a.id,d.employeeCode,d.titleName,a.userName,c.id userGroupId,d.id employeeId
			,case when isnull(d.firstName,'')='' then a.userName else d.firstName end Fname
			,d.lastName,d.nick,d.gender,d.workStart,d.workEnd,d.IDNo,d.positionName,d.officeName,d.em_status,d.email,d.mobile
			,isnull(d.em_status,'') as em_status
			from {$this->db->dbprefix}sysUsers a
			left join {$this->db->dbprefix}sysUserGroups c on a.groupId=c.id and c.[statusId]='1'
			left join {$this->db->dbprefix}devEmployees d on a.userName=d.employeeCode
			where lower(a.userName) = lower('{$Username}') and a.PW='{$Password}'
		";
		//var_dump($sql);
		$query = $this->db->query($sql);				
		$row = $query->result();	
		//var_dump($row[0]);		
		if(isset($row[0]->userName)){			
			//var_dump($row[0]);
			$arr['status'] = 'S';
			$sess_array = array();
			$rowArray = (array) $row[0];
			foreach($rowArray as $key => $val){ $sess_array[$key] = $val; }
			$this->session->sess_expiration = '1';
			$this->session->set_userdata(USER_INFO_SESSION_KEY, $sess_array);					
		}else{
			$arr['status'] = 'F';
			$arr['msg'] = 'login failed, username or password incorrect';					
		}		
		//var_dump($arr);exit;
		return $arr;
	}
	public function logout(){
		$this->session->set_userdata(USER_INFO_SESSION_KEY,null);
		redirect(base_url().'user/loginform/');
	}
}