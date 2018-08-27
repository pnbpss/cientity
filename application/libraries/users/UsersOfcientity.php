<?php

class UsersOfcientity 
{
	private $CI,$menus;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		
	}
	public function init($employeeCode,$extraEntityInfoDesc)
	{
		$this->menus = $this->getMenus($employeeCode,$extraEntityInfoDesc);
	}
	public function rtMenues()
	{
		return $this->menus;
	}
	private function getMenus($employeeCode,$extraEntityInfoDesc)
	{
		if($employeeCode!='admin')
		{
			$sql = "select top 1 groupuser from {$this->CI->db->dbprefix}gntUsers "
			." where employeeCode = '".$employeeCode."' ";
			$q = $this->CI->db->query($sql);
			$row = $q->row();
			$conditions = "and p.usergroup='{$row->groupuser}'";
			$sql = "
				select tg.id taskGroupId,tg.groupName taskGroupName,t.taskName,t.id taskId from {$this->CI->db->dbprefix}gntTaskGroups tg left join {$this->CI->db->dbprefix}gntTasks t on tg.id=t.groupId left join {$this->CI->db->dbprefix}gntPrivileges p on t.id=p.taskId where 1=1  {$conditions} and t.display=1 order by tg.ordering, t.ordering ";
		}else
		{
			$conditions = "";
			$sql = "
			select distinct tg.id taskGroupId,tg.groupName taskGroupName,t.taskName,t.id taskId
			from
			{$this->CI->db->dbprefix}gntTaskGroups tg left join
			{$this->CI->db->dbprefix}gntTasks t on tg.id=t.groupId left join
			{$this->CI->db->dbprefix}gntPrivileges p on t.id=p.taskId
			where 1=1  and t.display=1
			order by tg.id,t.taskName
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
			array_push($menus[$row->taskGroupName],['taskId'=>$row->taskId,'taskName'=>$row->taskName,'description'=>$taskDescription]);
			$tempTaskGroupName=$row->taskGroupName;
		}
		return $menus;
	}

}

