<?php

/*
 *
 *  
 * 
 */

/**
 * Description of AdditionalValidattionRules_Test
 *
 * @author pnbps
 */
require_once APPPATH."libraries\\additionalValidationRules.php";

class AdditionalValidattionRules_Test extends TestCase{
        //put your code here
        
       /**
        *  test every key of validation rules of each libray that the key,eg IDNo , is exists in db
        * @dataProvider dp_test_keyIsMatchWithDB
        */
        public function test_keyIsMatchWithDB($className, $columns) {                
                require_once APPPATH."libraries\\".$className.".php";
                $obj = new $className;
               
                foreach($columns as $column)
                {
                        
                        $this->assertTrue(isset($obj->columnDescriptionsColumnIndexed[$column]));
                }
        }
        public function dp_test_keyIsMatchWithDB() {
               $rules = AdditionalValidation::getAllRules();
               $returnRules = [];
               foreach($rules as $key=>$val){
                       $items = [];
                       foreach($val as $k=>$v){
                               array_push($items,$k);
                       }
                       array_push($returnRules,array($key,$items));
               }
               return $returnRules;
        }
}
