<?php
/**
* @author panu boonpromsook 2018/07/10, pnbpss@gmail.com
*/
abstract class entities{
	private $ObjectType /* table or view*/
			,$name /*name of entities*/
			,$columnListInfo /* information of column list*/
			,$columnRefKeyFrom /* parent key if column is foreign key */
			,$syncedColumnlistInfoWithRefKey /* syncedColumnListAndRef */
			,$columnRefKeyTo /* parent key if column is foreign key */
			,$insertKeyList 	/* ลิสต์ของคีย์ที่จะใช้ insert เข้าไปใน table */
			,$revisedColumnDescriptions = [] /* คำอธิบายคอลัมน์ ที่จัดใหม่แล้ว */
			,$columnDescriptionsColumnIndexed = [] /* คำอธิบายคอลัมน์ ที่ index ด้วยชื่อคอลัมน์ */
			,$entityInterfaces = [] // interface เพื่อเอาไว้ใช้สำหรับ สร้าง insert string และ  front end (ทำหน้าสำหรับ user interface)
			,$additionalValidateRules = [] /* validate ของแต่ละคอลัมน์ เพิ่มเติม ซึ่งจะไปกำหนด ใน class ชื่อตาราง  */
			,$stdValidationRules = [] /* กฎ ของการ validate ที่ได้จาก constraint, column_width, not null, data type  ในฐานข้อมูล */
			;
	 
	
	abstract public function _select($columns, $conditions); // $columns is array, $conditions is array
	abstract public function _update($data,$id); //$data is array,$id is int
	abstract public function _insert($data); //$data is array
	abstract public function _delete($id); //$id is int
	abstract public function getColumnlist($entityName); //$entityName is string
	abstract public function getColumnDescription($entityName); //get column description from db 
	abstract public function getColumnRefKeyFrom($entityName); //get which field column reference from $entityName is string 
	abstract public function getColumnRefKeyTo($entityName); //get which field column reference To $entityName is string 
	abstract public function setName($name); //set name of entity, and $name is string
	abstract public function resultToArray($q); //convert result of query to array
	abstract public function getDbObjectType($entityName); //get object type , view or table
	abstract public function makeEntityInterface(); //make interface for insert keylist, and for front end
	abstract public function syncColumnListAndRef($columnListInfo, $columnRefKeyFrom); // seek columnlist and add reference key info 
	abstract public function makeStdValidationRules(); // make standard validation rules for CodeIgniter form_validation helper
}