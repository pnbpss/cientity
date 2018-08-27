<?php

/**
 * Part of ci-phpunit-test 
 * test ว่า revisedColumnDescriptions มีคีย์ครบหรือไม่
 * @author     panu boonpromsook
 * @license    MIT License
 * @copyright  2018 Panu Boonpromsook
 * @link       
 * @group controller
 */
require_once APPPATH."libraries\\testData.php";
class entityHasKey_Test extends TestCase
{
        public function setUp() 
        {
                //$this->CI->load->library('devClasses');
                
        }
        /**
     * @dataProvider dataProviderFor_test_reviseColumnDescriptions
     */
        public function test_reviseColumnDescriptionsHasKey($className,$data)
        {
                //$className='devClasses';
                require_once APPPATH."libraries\\".$className.".php";
                $this->obj = new $className;
                foreach($data as  $excpected1)
                {
                        $this->assertArrayHasKey($excpected1, $this->obj->revisedColumnDescriptions);                        
                }                
        }
        public function dataProviderFor_test_reviseColumnDescriptions()
        {
                $obj = new testData;
                $data = $obj->provider01();
                return $data;       
        }
        
}