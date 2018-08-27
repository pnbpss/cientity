<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of libView_test
 *
 * @author pnbps
 */
require_once APPPATH."libraries\\testData.php";
class Libs_test extends TestCase{
        //put your code here
        /**
        * @dataProvider dp_test_issetAllColumnInArray
        */
        public function test_filesExists($data) {
                                
	$this->assertFileExists(APPPATH.'libraries\\'.$data.'.php');
        }
        /**
        * @dataProvider dp_test_issetAllColumnInArray
        */
        public function test_issetAllColumnInArray($className,$columns)
        {
                require_once APPPATH."libraries\\".$className.".php";
                $obj = new $className;
               
                foreach($columns as  $column)
                {
                        
                        $this->assertEquals('hds_'.$className,
                                $obj->columnDescriptionsColumnIndexed[$column]['tableName']
                                );
                }
        }
       
        public function dp_test_issetAllColumnInArray(){
                $obj = new testData;
                $data = $obj->provider01();
                return $data;          
        }
}
