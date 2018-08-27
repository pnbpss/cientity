<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of allColumnDescHasPipe_Test
 *
 * @author pnbps
 */
require_once APPPATH."libraries\\testData.php";
class entity_Test  extends TestCase 
{       
        public function setUp()
        {
                $this->appPath = APPPATH;
        }
        /**
        * @dataProvider dataProviderFor_test_reviseColumnDescriptions
        */
        public function test_reviseColumnDescriptionsHasPipe($className,$data)
        {
                
                //require_once "d:\workspace\ww2\hrds\application\libraries\\".$className.".php";
                require_once $this->appPath."libraries\\".$className.".php";
                $this->obj = new $className;
                foreach($data as  $columnName)
                {                        
                        $this->assertContains('||', $this->obj->columnDescriptionsColumnIndexed[$columnName]['descriptions']);                        
                }
        }
        
        /**
        * @dataProvider dataProviderFor_test_reviseColumnDescriptions
        */
        public function test_reviseColumnDescriptionsNotNull($className,$data)
        {
                
                //require_once "d:\workspace\ww2\hrds\application\libraries\\".$className.".php";
                require_once $this->appPath."libraries\\".$className.".php";
                $this->obj = new $className;
                foreach($data as  $columnName)
                {
                        $this->assertNotNull($this->obj->columnDescriptionsColumnIndexed[$columnName]['descriptions']);                        
                }
        }
        /**
        * @dataProvider dataProviderFor_test_reviseColumnDescriptions
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
        public function dataProviderFor_test_reviseColumnDescriptions()
        {                
                $obj = new testData;
                $data = $obj->provider01();
                return $data;           
                        
        }
}
