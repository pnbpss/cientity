<?php

class UsersOfcientity 
{
	private $CI,$menus;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		
	}
	public function init($userName,$extraEntityInfoDesc)
	{
		$this->menus = $this->getMenus($userName,$extraEntityInfoDesc);
	}
	public function rtMenues()
	{
		return $this->menus;
	}
	private function getMenus($userName,$extraEntityInfoDesc)
	{
		if(strtolower($userName)!='sysadmin')
		{
			
			$sql = "
				select tg.id taskGroupId,tg.groupName taskGroupName,t.taskName,t.id taskId 
				from {$this->CI->db->dbprefix}sysUsers u
				left join {$this->CI->db->dbprefix}sysUserGroups ug on ug.id=u.groupId
				left join {$this->CI->db->dbprefix}gntPrivileges p on ug.id=p.userGroupId 
				left join {$this->CI->db->dbprefix}gntTasks t on p.taskId=t.id 
				left join {$this->CI->db->dbprefix}gntTaskGroups tg on t.taskGroupId=tg.id 
				where 1=1 and t.display=1 and u.userName='{$userName}'
				order by tg.ordering, t.ordering ";
		}else{
			$conditions = "";
			$sql = "
			select distinct tg.id taskGroupId,tg.ordering,tg.groupName taskGroupName,t.taskName,t.id taskId
			from {$this->CI->db->dbprefix}gntTaskGroups tg 
			left join {$this->CI->db->dbprefix}gntTasks t on tg.id=t.taskGroupId 			
			where 1=1  and t.display=1
			order by tg.ordering,t.taskName
			";
		}		
		$q = $this->CI->db->query($sql);
		$tempTaskGroupName = "";
		$menus = [];
		foreach ($q->result() as $row) {
			// วนลูปเพื่อดึงเมนูออกมา
			if($tempTaskGroupName!=$row->taskGroupName)
			{
				$menus[$row->taskGroupName] = [];
			}
			$taskDescription = (isset($extraEntityInfoDesc[$row->taskName]['descriptions']))?$extraEntityInfoDesc[$row->taskName]['descriptions']:"_".$row->taskName;
			array_push($menus[$row->taskGroupName],['taskId'=>$row->taskId,'taskName'=>$row->taskName,'description'=>"&nbsp;".$taskDescription]);
			$tempTaskGroupName=$row->taskGroupName;
		}
		return $menus;
	}

}

