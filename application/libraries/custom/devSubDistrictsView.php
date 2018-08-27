<?php  
/** create by application/controllers/createEntityClassLibrary , since 05:33:24 */ 
require_once(APPPATH.'libraries\entity\entity.php');  
class devSubDistrictsView extends entity{	 
	public function __construct()  
	{ 
	 parent::__construct(); 
	 $this->columnDescriptions = [ 
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'id','descriptions'=>'id||id'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'subDistrictCode','descriptions'=>'subDistrictCode||subDistrictCode'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'subDistrictThName','descriptions'=>'subDistrictThName||subDistrictThName'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'districtCode','descriptions'=>'districtCode||districtCode'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'districtThName','descriptions'=>'districtThName||districtThName'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'provinceCode','descriptions'=>'provinceCode||provinceCode'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'provinceThName','descriptions'=>'provinceThName||provinceThName'],   
			[ 'tableName'=>'hds_devSubDistrictsView','columnName'=>'provinceThName','descriptions'=>'provinceThName||provinceThName'],   
	 ]; 
	 unset($this->columnDescriptions[7]); 
	 list($this->columnDescriptionsColumnIndexed,$this->revisedColumnDescriptions) = $this->reviseColumnDescriptions($this->columnDescriptions); 
	 } 
	private function getTableName() 
	{ 
		return $this->name; 
	} 
	public function returnTableName() 
	{ 
		return $this->getTableName(); 
	} 
} 
