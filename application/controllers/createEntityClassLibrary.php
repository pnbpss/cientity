<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CreateEntityClassLibrary extends CI_Controller {
	private $filePath;
	public function __construct(){
		parent::__construct();
		$this->filePath = APPPATH.'libraries/custom/';		
	}

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
		set_time_limit (0);
		$sql = "
		SELECT
		distinct 
		--top 5
		replace(c.TABLE_NAME,'{$this->db->dbprefix}','') libraryName
		,t.TABLE_TYPE
		FROM INFORMATION_SCHEMA.COLUMNS c
		left join INFORMATION_SCHEMA.TABLES t on c.TABLE_NAME=t.TABLE_NAME
		WHERE c.TABLE_NAME like '{$this->db->dbprefix}%' AND c.TABLE_SCHEMA='dbo'
		--and c.TABLE_NAME='hds_devClassExtInstructorsView'
		and c.TABLE_NAME not in ('hds_sysConfigs','hds_sysConfigTypes')
		and c.TABLE_NAME not in (select '{$this->db->dbprefix}'+sc.val from hds_sysConfigs sc inner join hds_sysConfigTypes sct on sc.configTypeId=sct.id where sct.id=1)
		";
		//echo $sql; exit;
		$q = $this->db->query($sql);		
		$triedToCreateFile=0; $createdFile = 0;
		foreach($q->result() as $row)
		{
			$libraryName = $row->libraryName;
			echo "<br />สร้างไฟล์  ".$this->filePath.$libraryName.".php ";
			$winCmd = "echo ^<?php > ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo /** create by application/controllers/".basename(__FILE__, '.php') ." , since ".date("H:i:s")." */ >> ".$this->filePath.$libraryName.".php"; exec($winCmd);
			$winCmd = "echo require_once(APPPATH.'libraries\\entity\\entity.php'); >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo class ".$libraryName." extends entity{	>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			if($row->TABLE_TYPE=='VIEW') 
			{
				$this->createCmdForView($libraryName);
			}
			$winCmd = "echo 	private function getTableName()>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 	{>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 		return \$this-^>name;>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 	}>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 	public function returnTableName()>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 	{>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 		return \$this-^>getTableName();>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo 	}>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo }>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			//$winCmd = ""; exec($winCmd);
			$triedToCreateFile++;
			if(file_exists($this->filePath.$libraryName.".php"))
			{
				$this->db->query("insert into hds_sysConfigs (configTypeId, val) values (1,'{$libraryName}') ");
				$createdFile++;
				echo "แล้ว";
				
			}else{
				echo "ไม่สำเร็จ";
			}
		}
		echo "<br />พยายามสร้าง {$triedToCreateFile} ไฟล์ สร้างสำเร็จ {$createdFile} ไฟล์... ";
		if($triedToCreateFile==0) 
		{
			echo "<br />หากการสร้างไฟล์ไม่ครบตามที่ต้องการ อาจจะมีการสร้างไฟล์ไว้แล้วก่อนหน้านี้ ทั้งนี้เพื่อป้องกัน ไม่ให้เขียนทับไฟล์ที่มีการแก้ไขไปแล้ว ";
		}
		echo "<br />หากต้องการสร้างใหม่ ให้ลบ record ใน table hds_sysConfigs ที่ configTypeId=1 ออก ด้วยคำสั่ง ... delete from [{$this->db->dbprefix}sysConfigs] where configTypeId=1";
		
	}
	private function createCmdForView($libraryName){
		$sql = "
		SELECT	
		c.COLUMN_NAME
		FROM INFORMATION_SCHEMA.COLUMNS c
		left join INFORMATION_SCHEMA.TABLES t on c.TABLE_NAME=t.TABLE_NAME
		WHERE c.TABLE_NAME like 'hds_%' AND c.TABLE_SCHEMA='dbo'
		and c.TABLE_NAME = '{$this->db->dbprefix}{$libraryName}'
		";
		$q = $this->db->query($sql);
		$winCmd = "echo 	public function __construct() >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	{>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 parent::__construct();>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 \$this-^>columnDescriptions = [>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		//$winCmd = "echo 			'dummy'=^>['tableName'=^>'{$this->db->dbprefix}{$libraryName}','descriptions'=^>'dummy'] >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$i=0;
		foreach($q->result() as $row)
		{
			$winCmd = "echo 			[ 'tableName'=^>'{$this->db->dbprefix}{$libraryName}','columnName'=^>'{$row->COLUMN_NAME}','descriptions'=^>'{$row->COLUMN_NAME}^|^|{$row->COLUMN_NAME}'],  >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$i++;
			//$winCmd = "echo 			,'{$row->COLUMN_NAME}'=^>['tableName'=^>'{$this->db->dbprefix}{$libraryName}','descriptions'=^>'{$row->COLUMN_NAME}^|^|{$row->COLUMN_NAME}'] >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		}
		$winCmd = "echo 			[ 'tableName'=^>'{$this->db->dbprefix}{$libraryName}','columnName'=^>'{$row->COLUMN_NAME}','descriptions'=^>'{$row->COLUMN_NAME}^|^|{$row->COLUMN_NAME}'],  >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		
		$winCmd = "echo 	 ];>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 unset(\$this-^>columnDescriptions[{$i}]);>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 list(\$this-^>columnDescriptionsColumnIndexed,\$this-^>revisedColumnDescriptions) = \$this-^>reviseColumnDescriptions(\$this-^>columnDescriptions);>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 }>> ".$this->filePath.$libraryName.".php "; exec($winCmd);		
		
	}
}

