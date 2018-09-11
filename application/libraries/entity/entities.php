<?php
namespace cientity;
/**
 * @author panu boonpromsook 2018/07/10, pnbpss@gmail.com
 */
abstract class entities{
	abstract public function getColumnlist($entityName); //$entityName is string
	abstract public function getColumnDescription($entityName); //get column description from db 
	abstract public function getColumnRefKeyFrom($entityName); //get which field column reference from $entityName is string 
	abstract public function getColumnRefKeyTo($entityName); //get which field column reference To $entityName is string 
	abstract public function setName($name); //set name of entity, and $name is string
	abstract public function resultToArray($q); //convert result of query to array
	abstract public function getDbObjectType($entityName); //get object type , view or table	
	abstract public function syncColumnListAndRef($columnListInfo, $columnRefKeyFrom); // seek columnlist and add reference key info 
	abstract public function makeStdValidationRules(); // make standard validation rules for CodeIgniter form_validation helper
	abstract public function _saveSessionData($sessionData); // save session for use in prviledges checking
	abstract public function doDbTransactions($sql);  //perform db transaction 
	abstract public function _retSessionData(); //return session data
	abstract public function _returnDbPrefix(); //return CodeIgniter table prefix 
}