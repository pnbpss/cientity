<?php  
/** create by application/controllers/createEntityClassLibrary , since 08:14:08 */ 
//require_once(APPPATH.'libraries/entityRecipes.php');
require_once(APPPATH.'libraries\entity\entity.php');  
class sysTasks extends entity{
	/**
	 * Constructor and also create temporary table for keep entity description.
	 */
	function __construct() {
		parent::__construct();
		$entityRecipes = new entityRecipes();
		$recipes = $entityRecipes->getRecipes();
		$sql ="create table #sysTask_temporary (taskName varchar(max), descriptions varchar(max)); ";			
				
		foreach($recipes as $key=>$val){
			if(isset($val['descriptions'])){
				$val['descriptions'] = str_replace("'","''",$val['descriptions']);
				$sql .= "insert into #sysTask_temporary values('{$key}','{$val['descriptions']}');";
			}
		}
		$sql_main = " IF OBJECT_ID('tempdb..#sysTask_temporary') IS NULL
				begin
				".$sql."
				end
			";
		$this->CI->db->query($sql_main);
	}
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	}
	/**
	 * show entity description by concatenating entity name and descriptions
	 * @param type $sqlSelect
	 * @return type
	 */
	function additionalSelectInFilterRow($sqlSelect){
		$tableName = $this->getTableName();
		//$dbPrefix = $this->_returnDbPrefix();
		//$entityName = str_replace($dbPrefix,"",$tableName);
		$replaceMent = "{$tableName}.taskName "
		. "+ case when isnull((select descriptions from #sysTask_temporary where #sysTask_temporary.taskName={$tableName}.taskName),'')='' then '' else '(' +(select descriptions from #sysTask_temporary where #sysTask_temporary.taskName={$tableName}.taskName)+')' end "
		. "taskName ";
		$sqlSelect4Return = str_replace("{$tableName}.taskName",$replaceMent,$sqlSelect);
		return $sqlSelect4Return;
	}
} 
//'(' +(select descriptions from #sysTask_temporary where #sysTask_temporary.taskName={$tableName}.taskName)+')'