<?php
class TestData {
	private $CI; /** เอาไว้โหลด get_instance เพื่อให้สามารถอ้างถึง method ต่างๆใน codeigniter ได้ */			
	public function __construct(){		
		$this->CI =& get_instance();
		$this->CI->load->database();
	}
	public function provider01()
	{
		//$dbPrefix = $this->CI->db->prefix;
		$dbPrefix = 'hds_';
		$groupOfSubApp='';
		$groupOfSubApp='dev';
		$sql = "
			SELECT 
			''+replace(TABLE_NAME,'{$dbPrefix}','')+'' entity
			,''+COLUMN_NAME+'' attribute
			,c.ORDINAL_POSITION colPos
			FROM INFORMATION_SCHEMA.COLUMNS c
			WHERE TABLE_NAME like '{$dbPrefix}{$groupOfSubApp}%' 
			
			and TABLE_SCHEMA='dbo'
			and TABLE_NAME not in ('{$dbPrefix}sysConfigs','{$dbPrefix}sysConfigTypes')
			order by TABLE_NAME,c.ORDINAL_POSITION

		";
		$q = $this->CI->db->query($sql);
		$returnData = [];
		$tempEntityName = '';
		$i=0;
		$currentIdx = $i;
		foreach($q->result() as $row){
			if($row->entity!=$tempEntityName){				
				//array_push($returnData, array($row->entity => array()));				
				$returnData[$i] = array(0=>$row->entity,1=>array());
				$currentIdx = $i;
				$i++;
				$tempEntityName = $row->entity;				
			}
			array_push($returnData[$currentIdx][1] ,$row->attribute);
			$tempEntityName = $row->entity;
		}
		return $returnData;
	}
	
}