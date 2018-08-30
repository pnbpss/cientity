<?php
require_once(APPPATH.'libraries/entity/entities.php');
require_once(APPPATH."libraries/additionalValidationRules.php");

/*
	+--------------------------------------------------------------------+
	 | CI_Entity version 1.0                                                |
	 +--------------------------------------------------------------------+
	 | Copyright CI_Entity LLC (c) 2004-2017                                |
	 +--------------------------------------------------------------------+
	 | This file is a part of CI_Entity.                                    |
	 |                                                                    |
	 | CI_Entity is free software; you can copy, modify, and distribute it  |
	 | under the terms of the GNU Affero General Public License           |
	 | Version 3, 19 November 2007 and the CI_Entity Licensing Exception.   |
	 |                                                                    |
	 | CI_Entity is distributed in the hope that it will be useful, but     |
	 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
	 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
	 | See the GNU Affero General Public License for more details.        |
	 |                                                                    |
	 | You should have received a copy of the GNU Affero General Public   |
	 | License and the CI_Entity Licensing Exception along                  |
	 | with this program; if not, contact CI_Entity LLC                     |
	 | at pnbpss[AT]gmail[DOT]com. If you have questions about the        |
	 | GNU Affero General Public License or the licensing of CI_Entity,     |
	 | see the CI_Entity license FAQ at http://www.cientity.com/licensing        |
	 +--------------------------------------------------------------------+
	 */
	/**
	 *
	 * @package forms
	 * @copyright CI_Entity LLC (c) 2018
	 * @author Panu Boonpromsook <pnbpss@gmail.com>
	 */

	 /*
	limitations:
	1. does not support manay-to-many relations, you have to normalized all entity relations to one-to-one or one-to-many
	2. 
	*/

class entity extends entities{
	/** 
	* CI store &get_instance for referencing to CodeIgniter resources.
	*/	
	private $CI,$sessionData=[]; 	
	
	public $infoForAdditionalValidate = []; //for use in additional entity validation for example see devClassExtInstructors.php
	public $infoForAdditionalValidateSubEntity = []; //for use in additional entity validation for example see devClassExtInstructors.php
	public $_REQUESTE = [];
	
	public function __construct(){		
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->setName($this->CI->db->dbprefix(get_class($this))); 
		
		//type Of Object object (view or table)
		$this->ObjectType = $this->getDbObjectType($this->name); 
		
		//list ของ คอลัมน์ใน table นั้นๆ  มาเก็บใน columnListInfo
		$this->columnListInfo = $this->getColumnlist($this->name); 
		
		// ดึง list ของ คำอธิบาย จาก description ใน table นั้นๆ 
		$this->columnDescriptions = $this->getColumnDescription($this->name); 
		
		// ดึง parent of column(foreign key of) 
		$this->columnRefKeyFrom = $this->getColumnRefKeyFrom($this->name); 
		
		// ดึง children of column(foreign key of) 
		$this->columnRefKeyTo = $this->getColumnRefKeyTo($this->name); 
		
		// เอา list ของคอลัมน์ (getColumnlist) มา เชื่อมกับ list ของ reference (getColumnRefKey)
		$this->syncedColumnlistInfoWithRefKey = $this->syncColumnListAndRef($this->columnListInfo, $this->columnRefKeyFrom); 
		
		//ทำ entity เพื่อเอาไว้ใช้สำหรับ สร้าง insert string และ  front end (ทำหน้าสำหรับ user interface)
		$this->entityInterfaces = $this->makeEntityInterface(); 

		//จัดรูปแบบ array column description ให้อ่านง่ายขึ้น
		list($this->columnDescriptionsColumnIndexed,$this->revisedColumnDescriptions) = $this->reviseColumnDescriptions($this->columnDescriptions); 
				
		//หากเป็น view ไม่ต้องสร้าง validation rules (U=table)
		if($this->ObjectType=='U'){
			/* เอา definition ของคอลัมน์ เช่น ความกว้าง ชนิดข้อมูล มาสร้าง validation rules, stdValidationRules จะถูกรวมกับ AdditionalValidation ที่กำหนดไว้ในไฟล์ additionalValidationRules.php ด้วยแล้ว ด้วย method mergeWithAdditionalRules()
			*/
			$this->stdValidationRules = $this->makeStdValidationRules(); 
		}elseif($this->ObjectType=='V'){
			$this->stdValidationRules = [];
		}	
		
	}
	public function _saveSessionData($sessionData){ 	
		$this->sessionData = $sessionData;
	}
	public function _retSessionData(){
		return $this->sessionData;
	}	
	public function _returnDbPrefix(){
		return $this->CI->db->dbprefix;
	}
	/**
	* @return object type of current entity 
	* 
	* Each entity have type of it, there two type of entity, View or Table
	*/
	public function getDbObjectType($entityName){
		$sql = "SELECT sobjects.name,''+replace(sobjects.[type],' ','')+'' [type] FROM sysobjects sobjects where sobjects.name='{$entityName}'";
		$q = $this->CI->db->query($sql);
		$row = $q->row();
		return $row->type;
	}
	/**
	* 
	* <p>
	* <pre>example of array format of each column is as following
	* array (size=2)
	*	0 => 
	*	array (size=12)
	*	  'ColumnName' => string 'id' (length=2)
	*	  'Datatype' => string 'int' (length=3)
	*	  'MaxLength' => int 4
	*	  'precision' => int 10
	*	  'scale' => int 0
	*	  'is_nullable' => int 0
	*	  'is_identity' => int 1
	*	  'default_object_id' => int 0
	*	  'PrimaryKey' => int 1
	*	  'is_unique' => int 1
	*	  'is_unique_constraint' => int 0
	*	  'index_name' => string 'PK_hds_devClasses' (length=17)
	*	1 => 
	*	array (size=12)
	*	  'ColumnName' => string 'scId' (length=4)
	*	  'Datatype' => string 'int' (length=3)
	*	  'MaxLength' => int 4
	*	  'precision' => int 10
	*	  'scale' => int 0
	*	  'is_nullable' => int 0
	*	  'is_identity' => int 0
	*	  'default_object_id' => int 0
	*	  'PrimaryKey' => int 0
	*	  'is_unique' => null
	*	  'is_unique_constraint' => null
	*	  'index_name' => null
	* </pre></p>
	* @param string entityName 
	*	name of entity
	* @return array
	*	the informations of each column of specified entity in array format.
	*/
	public function getColumnlist($entityName){ //$entityName is string	
		$fullEntityName = $this->CI->db->database.'.dbo.'.$entityName;
		$sql = "
			SELECT c.name 'ColumnName',t.Name 'Datatype',c.max_length 'MaxLength',c.precision ,c.scale ,c.is_nullable,c.is_identity,c.default_object_id,ISNULL(i.is_primary_key, 0) 'PrimaryKey',i.is_unique,i.is_unique_constraint,i.name index_name
			FROM    
				sys.columns c
			INNER JOIN 
				sys.types t ON c.user_type_id = t.user_type_id
			LEFT OUTER JOIN 
				sys.index_columns ic ON ic.object_id = c.object_id AND ic.column_id = c.column_id
			LEFT OUTER JOIN 
				sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
			WHERE
				c.object_id = OBJECT_ID('{$fullEntityName}')
		";
		return  $this->resultToArray($this->CI->db->query($sql));
	}
	/**	
	* <p><pre>
	* construct description of each entity's column
	* example of array format of each column is as following
	* array (size=8)
	*	0 => 
	*	array (size=4)
	*	  'tableName' => string 'hds_devClasses' (length=14)
	*	  'columnName' => string 'id' (length=2)
	*	  'descriptions' => string 'ไอดี||id' (length=16)
	*	  'a' => int 1
	*	1 => 
	*	array (size=4)
	*	  'tableName' => string 'hds_devClasses' (length=14)
	*	  'columnName' => string 'scId' (length=4)
	*	  'descriptions' => string 'วิชาในหลักสูตร||scId' (length=48)
	*	  'a' => int 1
	* </pre>
	* * 'a' is just a dummy, no matter to using.
	* </p>
	* @param string entityName
	*	name of specified entity 
	* @return array
	*	description which specified in database.table.column.description
	*/
	public function getColumnDescription($entityName){
		$fullEntityName = $this->CI->db->database.'.dbo.'.$entityName;
		$sql = "
		select 
			st.name [tableName],
			sc.name [columnName],
			cast(sep.value as varchar(max)) [descriptions],
			--sep.value [descriptions2],
			1 as [a]
		from sys.tables st
		inner join sys.columns sc on st.object_id = sc.object_id
		left outer join sys.extended_properties sep on st.object_id = sep.major_id and sc.column_id = sep.minor_id and sep.name = 'MS_Description'
		where sc.object_id = object_id('{$fullEntityName}')
		";
		
		return $this->resultToArray($this->CI->db->query($sql));
	}
	/**	
	* <p>
	* construct list of table.column which this entity is use as foreign key
	* <pre>for example
	* array (size=2)
	*	0 => 
	*	array (size=6)
	*	  'FK_NAME' => string 'FK_hds_devClasses_hds_devSubjectCourse' (length=38)
	*	  'schema_name' => string 'dbo' (length=3)
	*	  'table' => string 'hds_devClasses' (length=14)
	*	  'column' => string 'scId' (length=4)
	*	  'referenced_table' => string 'hds_devSubjectCourse' (length=20)
	*	  'referenced_column' => string 'id' (length=2)
	*	1 => 
	*	array (size=6)
	*	  'FK_NAME' => string 'FK_hds_devClasses_hds_devLocations' (length=34)
	*	  'schema_name' => string 'dbo' (length=3)
	*	  'table' => string 'hds_devClasses' (length=14)
	*	  'column' => string 'locationId' (length=10)
	*	  'referenced_table' => string 'hds_devLocations' (length=16)
	*	  'referenced_column' => string 'id' (length=2)
	*	</pre></p>
	* @param string entityName
	*	name of entity
	* @return array 
	*	table.column that the entity is referenced from.
	*/
	public function getColumnRefKeyFrom($entityName){ //$entityName is string 	
		$fullEntityName = $this->CI->db->database.'.dbo.'.$entityName;
		$sql = "SELECT  obj.name AS FK_NAME,sch.name AS [schema_name],tab1.name AS [table],col1.name AS [column],tab2.name AS [referenced_table],col2.name AS [referenced_column]
			FROM sys.foreign_key_columns fkc
			INNER JOIN sys.objects obj
				ON obj.object_id = fkc.constraint_object_id
			INNER JOIN sys.tables tab1
				ON tab1.object_id = fkc.parent_object_id
			INNER JOIN sys.schemas sch
				ON tab1.schema_id = sch.schema_id
			INNER JOIN sys.columns col1
				ON col1.column_id = parent_column_id AND col1.object_id = tab1.object_id
			INNER JOIN sys.tables tab2
				ON tab2.object_id = fkc.referenced_object_id
			INNER JOIN sys.columns col2
				ON col2.column_id = referenced_column_id AND col2.object_id = tab2.object_id
			where col1.object_id = object_id('{$fullEntityName}')
		";
		
		return $this->resultToArray($this->CI->db->query($sql));		
	}
	/**	
	*
	* <p><pre>for example
	* array (size=2)
	*	0 => 
	*	array (size=7)
	*	  'FK_NAME' => string 'FK_hds_devClassInstructors_hds_devClasses' (length=41)
	*	  'schema_name' => string 'dbo' (length=3)
	*	  'tableName' => string 'hds_devClasses' (length=14)
	*	  'referenced_from_column' => string 'id' (length=2)
	*	  'referenced_to_table' => string 'hds_devClassInstructors' (length=23) <i> table hds_devClasses.id is foreign key of column hds_devClassInstructors.classId</i>
	*	  'referenced_to_libName' => string 'devClassInstructors' (length=19)
	*	  'referenced_to_column' => string 'classId' (length=7)
	*	1 => 
	*	array (size=7)
	*	  'FK_NAME' => string 'FK_hds_devClassExtInstructors_hds_devClasses' (length=44)
	*	  'schema_name' => string 'dbo' (length=3)
	*	  'tableName' => string 'hds_devClasses' (length=14)
	*	  'referenced_from_column' => string 'id' (length=2)
	*	  'referenced_to_table' => string 'hds_devClassExtInstructors' (length=26)
	*	  'referenced_to_libName' => string 'devClassExtInstructors' (length=22)
	*	  'referenced_to_column' => string 'classId' (length=7)
	* </pre></p>
	* @param string entityName
	*      name of specified entity
	* @return array 
	*       list of column and that the entity is referenced to, on the other hand we should say the entity have foreign for 
	*/
	public function getColumnRefKeyTo($entityName){ //$entityName is string 	
		$fullEntityName = $this->CI->db->database.'.dbo.'.$entityName;
		$sql = "			
			SELECT  obj.name AS FK_NAME,
				sch.name AS [schema_name],
				tab2.name AS [tableName],
				col2.name AS [referenced_from_column],
				tab1.name AS [referenced_to_table],
				replace(tab1.name,'{$this->CI->db->dbprefix}','') [referenced_to_libName],
				col1.name AS [referenced_to_column]  
			FROM sys.foreign_key_columns fkc 
			INNER JOIN sys.objects obj ON obj.object_id = fkc.constraint_object_id
			INNER JOIN sys.tables tab1 ON tab1.object_id = fkc.parent_object_id
			INNER JOIN sys.schemas sch ON tab1.schema_id = sch.schema_id
			INNER JOIN sys.columns col1 ON col1.column_id = parent_column_id AND col1.object_id = tab1.object_id
			INNER JOIN sys.tables tab2 ON tab2.object_id = fkc.referenced_object_id
			INNER JOIN sys.columns col2 ON col2.column_id = referenced_column_id AND col2.object_id = tab2.object_id
			where 1=1			
			and tab2.object_id=OBJECT_ID('{$fullEntityName}')			
		";
		
		return $this->resultToArray($this->CI->db->query($sql));		
	}
	/**         
	* <p><pre>
	*Convert query result from SQL to array of result for easy to iterate in using
	* for example 
	* $q = {
		{row1.id=1}{row1.name='John'}
		{row2.id=2}{row1.name='Jack'}
		}
	* after use this method $q will be transformed to 
	* [
	*	0=>[id=>1,'name'=>'John']
	*	,1=>[id=>2,'name'=>'Jack']
	* ]
	* </pre></p>
	* @param object q
	*	query result from db 
	* @return array 
	*	array of query result 
	*/
	public function resultToArray($q){
		$arrayResult = [];
		$i=0;
		foreach($q->result() as $row)
		{
			$rowArray = (array) $row;
			foreach($rowArray as $key => $val)
			{
				$arrayResult[$i][$key] = $val;
			}
			$i++;
		}		
		return $arrayResult;		
	}	
	/** 	
	* <p><pre>	
	* iterate $this->columnRefKeyFrom, as external loop, and iterate columnListInfo as internal loop. if both key is match then add referenced key. 
	* <i>(เอา array ของ reference key มา iterate เพื่อดูว่า ใน columnListInfo มี column ไหนบ้างที่มี reference key หากมีจะเพิ่ม reference เข้าไปใน array นั้น )</i>
	* for example
	* array (size=9)
	*  'ColumnName' => string 'locationId' (length=10)
	*  'Datatype' => string 'int' (length=3)
	*  'MaxLength' => int 4
	*  'precision' => int 10
	*  'scale' => int 0
	*  'is_nullable' => int 1
	*  'is_identity' => int 0
	*  'default_object_id' => int 0
	*  'PrimaryKey' => int 0
	*  <b>'references' => array('referencedTable'=>'hds_devLocations','referencedColumn'=>'id') <i>key 'references' is added after use this method </i></b>
	* </pre></p>
	* @param array columnListInfo
	*	array of information of this entity columns 
	* @param array columnRefKeyFrom
	*	array which tell us that this entity is referenced from 
	* @return array 
	*	array of merged of $this->columnListInfo and $this->columnRefKeyFrom
	*/
	public function syncColumnListAndRef($columnListInfo, $columnRefKeyFrom){
		
		$syncedColumnlistInfoWithRefKey = $columnListInfo;
		foreach($columnRefKeyFrom as $rKey => $rVal)
		{
			foreach($columnListInfo as $cKey => $cVal)
			{				
				if($rVal['column']==$cVal['ColumnName'])
				{					
					$syncedColumnlistInfoWithRefKey[$cKey]['references'] = 
					[
						'referencedTable'=>$columnRefKeyFrom[$rKey]['referenced_table']
						,'referencedColumn'=>$columnRefKeyFrom[$rKey]['referenced_column']
					];
				}
			}
			reset($columnListInfo);	// เอาตัวชี้ของ array ไปตั้งต้นที่ 0 ใหม่
		}		
		return $syncedColumnlistInfoWithRefKey;
	}
                /**
                 * set name of entity by get name of class
                 * @param string name
                 *      full table name 
                 */
	public function setName($name){ //set name of entity, and $name is string
		$this->name = $name;
		$this->shortName = get_class($this);
	}
	/**	
	* <p><pre>
	* create array by looping through columnListInfo for select columns which have default, not identity and put column description in array
	* for examle 
	*	array (size=2)
	*	  0 => 
	*		array (size=1)
	*		  'scId' => 
	*			array (size=3)
	*			  'dataType' => string 'int' (length=3)
	*			  'width' => int 4
	*			  'isNullable' => int 0 
	*	  1 => 
	*		array (size=1)
	*		  'startDate' => 
	*			array (size=3)
	*			  'dataType' => string 'date' (length=4)
	*			  'width' => int 3
	*			  'isNullable' => int 0
	* </pre></p>
	* @return array 
	*	array of column for create insert sql, or compose user interface for insert
	*/
	public function makeEntityInterface(){ 
		$entityInterfaces['forInsert'] = [];
		$entityInterfaces['forFrontEnd'] = [];		
		foreach($this->columnListInfo as $val){
			if (!(($val['is_identity']==1) || ($val['default_object_id']!=0)))
			{
				array_push($entityInterfaces['forInsert'],$val['ColumnName']);
				array_push($entityInterfaces['forFrontEnd'],
						[
							$val['ColumnName']=>
								[
									'dataType'=>$val['Datatype']
									,'width'=>$val['MaxLength']
									,'isNullable'=>$val['is_nullable']
								]
						]
					);
			}
			
		}
		return $entityInterfaces;
	}
	/**	
	* in some situation, using simplified column description array is better. this funciton return simplified column description as following:
	* <p><pre>
	* array (size=2)
	*	'id' => 
	*	array (size=2)
	*	  0 => string 'ไอดี' (length=12)
	*	  1 => string 'id' (length=2)
	*	'scId' => 
	*	array (size=2)
	*	  0 => string 'วิชาในหลักสูตร' (length=42)
	*	  1 => string 
	* </pre><p>
	* @param array cds
	*	$this->columListInfo
	* @return array
	*	simplified column description 
	*/
	public function reviseColumnDescriptions($cds){
		$newCds = [];
		$revisedColumnDescriptions = [];
		foreach($cds as $val)
		{
			$newCds[$val['columnName']] = ['tableName' => $val['tableName'] , 'descriptions'=> $val ['descriptions']];
			if($val['descriptions']!='')
			{
				$descriptions = preg_replace('/\s+/', '', $val['descriptions']);
				$descShort =  explode('||', $descriptions);
				$desc = $descShort[0]!=''?$descShort[0]:'?'.$val['columnName'];
				$short = isset($descShort[1])?$descShort[1]:'?'.$val['columnName'];
			}else
			{
				list($desc,$short) = ['?'.$val['columnName'],'?'.$val['columnName']];
			}
			$revisedColumnDescriptions[$val['columnName']] = [$desc,$short];			
		}		
		return [$newCds, $revisedColumnDescriptions];
	}
	/**	
	* <p><pre>
	* @example "insert into HDS.dbo.hds_devClasses(scId,startDate,locationId,statusId,createdBy,createdDate,descriptions) "
	* </pre><p>
	* @return string
	*	insert sql string of entity
	*/
	public function insertSqlString(){
		$insertKeys = $this->entityInterfaces['forInsert'];
		$sql = 'insert into '.$this->CI->db->database.'.dbo.'.$this->name;
		$keyList = '';
		foreach($insertKeys as $val)
		{
			$keyList.='{{'.$val.'}}';
		}
		return $sql.str_replace('{{','(',str_replace('}}',')',str_replace('}}{{',',',$keyList)));
	}
	/**	
	* <p><pre>
	* gater informations from columnListInfo and use property of each column to create valiation rules
	* for example
	* array (size=1)
	*   'devClasses' => array (size=3)
	*	  0 => 
	*		array (size=3)
	*		  'field' => string 'scId' (length=4)
	*		  'label' => string 'วิชาในหลักสูตร' (length=42)
	*		  'rules' => string 'required|integer' (length=16)
	*	  1 => 
	*		array (size=3)
	*		  'field' => string 'startDate' (length=9)
	*		  'label' => string 'วดป.เริ่มเรียน' (length=40)
	*		  'rules' => string 'required' (length=8)
	*	  2 => 
	*		array (size=3)
	*		  'field' => string 'locationId' (length=10)
	*		  'label' => string 'สถานที่' (length=21)
	*		  'rules' => string 'integer' (length=7)
	* <i>this funciton also merge the additionalValidationRules in APPPATH.'libraries\additionalValidationRules.php' </i>		  
	* </pre><p>
        * @return array 
        *        validtion rules which used in formvalidation of codeigniter 
	*/
	public function makeStdValidationRules(){
		$cds = $this->columnListInfo;						 
		$stdValidationRules[get_class($this)] = [];
		$refInfo = $this->columnRefKeyFrom;
		foreach($cds as $val)
		{
			//ถ้าเป็นฟิลด์ auto increment ไม่ต้องสร้าง rules
			if($val['is_identity']==1) 
			{
				continue; 
			}
			
			/*
			//ถ้าเป็นฟิลด์ ที่มี default ไม่ต้องสร้าง rules
			if($val['default_object_id']!=0) 
			{
				continue; 
			}
			*/
			
			/*
			//ถ้าเป็นฟิลดที่ reference มาจาก table อื่น ไม่ต้องสร้าง rule			
			$isRef = false;
			foreach($refInfo as $a=>$b){
				if ($b['column']==$val['ColumnName'])
				{
					$isRef = true;
				}
			}
			
			if ($isRef) 
			{
				continue;
			}
			*/			
			reset($refInfo);			
			$rules = $this->getColumnStdValidationRules($val);
			//ถ้าไม่มี rules เกิดขึ้น ไม่ต้องสร้าง rule
			if($rules=='')
			{
				continue;
			}
			
			$colInfos = [
				'field'=>$val['ColumnName']
				,'label'=> $this->revisedColumnDescriptions[$val['ColumnName']][0]  //$val ['descriptions']
				,'rules'=>$rules
				];
			
			array_push($stdValidationRules[get_class($this)], $colInfos);			
		}
		return $stdValidationRules;
	}
	/**
	* <p><pre>
	* return validation rule of each field. For instance, called from makeStdValidationRules. The merging with additionalValidationRules done in this method
	* </pre><p>
	* @param array columnInfo
	*	information of each column, such as, data_type, maxLength, etc.
	* @return string 
	*	validation rule of each field
	*/
	private function getColumnStdValidationRules($columnInfo){
		$rules = "";
		if ($columnInfo['is_nullable']==0)
		{
			$rules.="{{required}}";
		}
		
		if ($columnInfo['Datatype']=='int')
		{
			$rules.="{{integer}}";
		}elseif($columnInfo['Datatype']=='varchar'){
			$rules.="{{max_length[".$columnInfo['MaxLength']."]}}"; 
		}elseif($columnInfo['Datatype']=='char'){
			$rules.="{{exact_length[".$columnInfo['MaxLength']."]}}"; 
		}elseif($columnInfo['Datatype']=='decimal')
		{			
			$rules.="{{decimal}}";
		}
		/*
		//(bugId 28010804-01) กรณี validate unique ไปใช้ วิธี insert เข้า database ไปเลย แล้วค่อยเอา error message ของ database มาใช้ดีกว่า
		
		//ยกเลิกการ validate set rule unique แต่ให้ insert ไปเลย แล้วค่อยเอา 2601 หรือ 2627 มาใช้แทน(เหนื่อยละ) จากนั้นค่อย map เอาจาก key ที่ sql server ตอบกลับมาว่า ซ้ำกันใน field ไหน
		if($columnInfo['is_unique']==1)
		{	
			//$clInfo = $this->columnListInfo;
			
			//if column shares index_name with other field, which means  it use multiple column for unique, the the validation have to use call_back function
			if($this->shareIndexNameWithOther($columnInfo['ColumnName'], $columnInfo['index_name'])){
				//$rules.= "{{multiple_unique_callback_".get_class($this)."_".$columnInfo['index_name']."()}}";
			}else{
				//$rules.= '{{is_unique['.get_class($this).".".$columnInfo['ColumnName']."]}}";
			}
		}		
		*/
		$re_rules = str_replace('}}','',str_replace('{{','', str_replace('}}{{','|',$rules)));
		
		$this->mergeWithAdditionalRules($re_rules,$columnInfo);
		
		return $re_rules;
	}
	/**
	* @deprecated 
	*
	* <p><pre>
	* </pre><p>
	*/
	private function shareIndexNameWithOther($columnName, $index_name){	
		$entityColumnInfos = $this->columnListInfo;
		//var_dump($entityColumnInfos);exit;
		foreach($entityColumnInfos as $columnInfos)
		{
                                                if($columnInfos['ColumnName']==$columnName){ continue;}
                                                if($columnInfos['index_name']==$index_name){ return true;}
		}
		return false;
	}
	/**
	* <p><pre>merge rule of each field which merged with additional validationRules
	* 
	* fetch additional validation rules from additionalValidationRules.php and merge it with standard rules created with stdValidationRules
	* </pre><p>
	* @param string &re_rules
	 *	validation rule which constructed from standard column info
	 * @param array columnInfo
	 *	 information of column 
	*/
	private function mergeWithAdditionalRules(&$re_rules,$columnInfo){
		$entityName = get_class($this);		
		$re_rules .= '|'.AdditionalValidation::getRules($entityName,$columnInfo['ColumnName']);
		$re_rules = trim(str_replace('||','|',$re_rules),'|');
	}
	/**
	* <p><pre>
	* </pre><p>
	* @param string columnName
	*	name of specified column
	* @return string 
	*	type of specified column
	*/
	public function columnDataType($columName){
		$cli = $this->columnListInfo;
		foreach( $cli as $val)
		{
			if($val['ColumnName']==$columName)
			{
				return $val['Datatype'];
			}
		}
		return null;
	}
	public function insertUpdateAllowed($userId){		
		$entityName = get_class($this);
		$sql = "SELECT p.[id],[userGroupId],[taskId],[allowSave],u.userName FROM [hds_gntPrivileges] p left join hds_gntTasks t on p.taskId=t.id left join hds_sysUserGroups ug on p.userGroupId=ug.id left join hds_sysUsers u on ug.id=u.groupId where u.id='{$userId}' and t.taskName='{$entityName}' and allowSave='Y' ";
		$q = $this->CI->db->query($sql);
		$row = $q->row();
		if(isset($row->userName)){
			return true;
		}else{
			if($userId===12){ //sysadmin
				return true;
			}else{
				return false;
			}
		}
		return false;
	}
	/**	
	*
	* <p><pre>
	* do the transaction processing which specified in $sql and return result of processing. 
	* there two type of transaction result, ok and error. if error accured it also return error message
	* </pre><p>
	* @param string sql
	*	SQL string which required to run.
	* @return array
	*	result of performed transaction
	*/
	public function doDbTransactions($sql){
		$transactionSql = "
		declare @errorMessage as varchar(max);
		create table #thisTemporaryStatus (id varchar(max), msg varchar(max) collate THAI_CI_AS); 
		begin tran t1
		begin try
				
		".$sql."
		
		commit tran t1;
		end try
		begin catch		
		-- save error from transaction into table		
		rollback tran t1;
		insert into #thisTemporaryStatus select convert(varchar(max),ERROR_NUMBER()) as id,  ERROR_MESSAGE() as msg;
		goto endScript;
		end catch

		goto endScript;
		Skipper: -- Don't do nuttin!
		rollback tran t1;		
		-- save error from manually check into table
		insert into #thisTemporaryStatus select 'error' as id,  @errorMessage as msg;					
		endScript:	
		";
		
		$this->CI->db->query($transactionSql);
		
		$resultsql = "select * from #thisTemporaryStatus;";
		$q = $this->CI->db->query($resultsql); $i=0;
		foreach($q->result() as $row){ $i++; }
		if($i==0){			
			return array('ok',"^_^");
		}else{
			return ['error',"{$row->msg}(errorCode:{$row->id})",'errorCode'=>$row->id, 'errorMessage'=>$row->msg];
		}
	}
	/**
	*
	* <p><pre>
	* </pre><p>
	* @return string 
	*	file name of this file
	*/
	public static function fileInfo(){
		return __FILE__;
	}
	/**
	* <p><pre>
	* </pre><p>
	* @return string 
	*	directory informations of this file
	*/
	public static function dirInfo(){
		return __DIR__;
	}
}