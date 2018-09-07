<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CreateEntityClassLibrary extends CI_Controller {
	private $filePath;
	public function __construct(){
		parent::__construct();
		$this->filePath = APPPATH.'libraries/custom/';		
	}
	public function index(){	
		set_time_limit (0);
		$sql = "
		SELECT
		distinct 		
		replace(c.TABLE_NAME,'{$this->db->dbprefix}','') libraryName
		,t.TABLE_TYPE
		FROM INFORMATION_SCHEMA.COLUMNS c
		left join INFORMATION_SCHEMA.TABLES t on c.TABLE_NAME=t.TABLE_NAME
		WHERE c.TABLE_NAME like '{$this->db->dbprefix}%' AND c.TABLE_SCHEMA='dbo'		
		and c.TABLE_NAME not in (
			--users and task for authenticate 
			'{$this->db->dbprefix}sysConfigs'
			,'{$this->db->dbprefix}sysConfigTypes'
			--,'{$this->db->dbprefix}sysTasks'
			--,'{$this->db->dbprefix}sysTaskGroups'
			--,'{$this->db->dbprefix}sysUserTaskPrivileges'
			--,'{$this->db->dbprefix}sysUserGroups'
			--,'{$this->db->dbprefix}sysUsers'
		)
		and c.TABLE_NAME not in (
			--created file
			select '{$this->db->dbprefix}'+sc.val from {$this->db->dbprefix}sysConfigs sc inner join {$this->db->dbprefix}sysConfigTypes sct on sc.configTypeId=sct.id where sct.id=1
		)
		";		
		$q = $this->db->query($sql);		
		$triedToCreateFile=0; $createdFile = 0; $insertIntoTaskListSql = "";
		foreach($q->result() as $row){
			$libraryName = $row->libraryName;
			echo "<br />".$this->filePath.$libraryName.".php ";
			$winCmd = "echo ^<?php > ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo /** create by application/controllers/".basename(__FILE__, '.php') ." , since ".date("H:i:s")." */ >> ".$this->filePath.$libraryName.".php"; exec($winCmd);
			$winCmd = "echo require_once(APPPATH.'libraries\\entity\\entity.php'); >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$winCmd = "echo class ".$libraryName." extends entity{	>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			if($row->TABLE_TYPE==='VIEW'){
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
			$triedToCreateFile++;
			if(file_exists($this->filePath.$libraryName.".php")){
				$this->db->query("insert into hds_sysConfigs (configTypeId, val) values (1,'{$libraryName}') ");
				$createdFile++;
				echo " =&gt; created. ";
				//insert to sysTasks in group unknow for switch to correct task group later
				if($row->TABLE_TYPE!=='VIEW'){
					//check that is exists or not
					$insertIntoTaskListSql .= " ".PHP_EOL
					." if(select count(*) from {$this->db->dbprefix}sysTasks where taskName='{$libraryName}') = 0 ".PHP_EOL
					." begin "
					." insert into {$this->db->dbprefix}sysTasks (taskGroupId, taskName,ordering,display) values(@taskGroupId ,'{$libraryName}',99,1); "
					." end ".PHP_EOL
					;					
				}
			}else{
				echo " =&gt; create this file not successed.";
			}
		}
		$allTranSql = " ".PHP_EOL
		." declare @taskGroupId int;".PHP_EOL
		." set @taskGroupId = (select id from {$this->db->dbprefix}sysTaskGroups where groupName='_notDefinedTaskGroup_');".PHP_EOL
		." if isnull(@taskGroupId,-1) = -1 begin insert into {$this->db->dbprefix}sysTaskGroups (groupName,ordering) values('_notDefinedTaskGroup_',99); select @taskGroupId = SCOPE_IDENTITY();  end ".PHP_EOL
		."  ".PHP_EOL
		.$insertIntoTaskListSql;
		$this->db->query($allTranSql);
		
		echo "<br />Tried to create {$triedToCreateFile} file(s),  completed {$createdFile} file(s) ";
		if($triedToCreateFile==0){
			echo "<br />
			In case of files are not create as expected, that might be files are already exist, preventing created file from being overwritten.
			";
		}
		echo "<br />
		if you want to re-create all file, just delete records in hds_sysConfigs which configTypeId is 1 by using \" delete from [{$this->db->dbprefix}sysConfigs] where configTypeId=1 \" sql.
		";
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
		
		$i=0;
		foreach($q->result() as $row){
			$winCmd = "echo 			[ 'tableName'=^>'{$this->db->dbprefix}{$libraryName}','columnName'=^>'{$row->COLUMN_NAME}','descriptions'=^>'{$row->COLUMN_NAME}^|^|{$row->COLUMN_NAME}'],  >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
			$i++;			
		}
		$winCmd = "echo 			[ 'tableName'=^>'{$this->db->dbprefix}{$libraryName}','columnName'=^>'{$row->COLUMN_NAME}','descriptions'=^>'{$row->COLUMN_NAME}^|^|{$row->COLUMN_NAME}'],  >> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		
		$winCmd = "echo 	 ];>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 unset(\$this-^>columnDescriptions[{$i}]);>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 list(\$this-^>columnDescriptionsColumnIndexed,\$this-^>revisedColumnDescriptions) = \$this-^>reviseColumnDescriptions(\$this-^>columnDescriptions);>> ".$this->filePath.$libraryName.".php "; exec($winCmd);
		$winCmd = "echo 	 }>> ".$this->filePath.$libraryName.".php "; exec($winCmd);		
	}
}

