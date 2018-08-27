<?php

/**
 * Part of ci-phpunit-test
 *
 * @author     panu boonpromsook
 * @license    MIT License
 * @copyright  2018 Panu Boonpromsook
 * @link       
 * @group controller
 */
class Ajax01_test extends TestCase {
    public function setUp() {
        $this->obj = new ajax01;
    }

    /**
     * @dataProvider dataProviderForReturnStr
     */
    public function test_retStr($number, $str, $expected) {

        $actual = $this->obj->retStr($number, $str);
        $this->assertEquals($expected, $actual);
    }
    
    public function test_getNumber() {

        $actual = $this->obj->getNumber();
        $expected = 500;
        $this->assertEquals($expected, $actual, 'error get number', 0.001);
    }

    /**
     * @dataProvider dataProviderForMultiPlyAndDividen
     */
    public function test_multiplyByTwo($number, $expected) {
        //$obj = new ajax01;
        $actual = $this->obj->multiplyByTwo($number);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProviderForMultiPlyAndDividen
    */
    public function test_dividedByTwo($expected, $number) {
        //$obj = new ajax01;        
        $actual = $this->obj->dividedByTwo($number);
        $this->assertEquals($expected, $actual, '', 0.5);
    }

    public function dataProviderForMultiPlyAndDividen() {
        return
                [
                    [2, 4],
                    [3, 6],
                    [4, 8],
                    [5, 10],
                    [0, 0],
                    [null, null]                     
                    
        ];
    }

    public function dataProviderForReturnStr() {
        return [
            [-5, 'asdfasd', 'error'],
            [2, 'ชนานนท์ สบายใจ', 'ชน'],
            [9, 'นริศรา เพียรดี', 'นริศรา เพ'],
            [10, 'ณัฐวุฒิ แก้ววิมล', 'ณัฐวุฒิ แก'],
            [20, 'Onanong Wongchan', 'Onanong Wongchan'],
            [1, 'มนภรณ์ จันทร์บุญ', 'ม'],
            [11, 'Panu Boonpromsook', 'Panu Boonpr'],
            [19, 'ทศพล เจริญพานิช', 'ทศพล เจริญพานิช'],
            [16, 'นคร อ่อนแสง', 'นคร อ่อนแสง'],
            [7, 'suwit kamwilan', 'suwit k'],
            [7, 'เพ็ญลักษณ์ ฟักบุญเลิศ', 'เพ็ญลัก'],
            [3, 'อัศนี สุขศรี', 'อัศ'],
            [5, 'พิสมยา ชูสิทธิ์', 'พิสมย'],
            [2, 'เมธาวี รินราช', 'เม'],
            [22, 'กนกภรณ์ ยิ้มเจริญ', 'กนกภรณ์ ยิ้มเจริญ'],
            [20, 'เอกรินทร์ ไกรวงษ์', 'เอกรินทร์ ไกรวงษ์'],
            [14, 'พงศกร ฃุมแต', 'พงศกร ฃุมแต'],
            [7, 'อธิพงศ์ คงศิริ', 'อธิพงศ์'],
            [14, 'พรเทพ เกตุสมบูรณ์', 'พรเทพ เกตุสมบู'],
            [10, 'ภัคจิรา อันดารา', 'ภัคจิรา อั'],
            [12, 'สมภพ อุปนันท์', 'สมภพ อุปนันท'],
            [11, 'ธัญวรัตน์ หึกขุนทด', 'ธัญวรัตน์ ห'],
            [13, 'ภัทรพงศ์ สุรัติศักดิ์', 'ภัทรพงศ์ สุรั'],
            [10, 'ฤทัยรัตน์  พรหมทอง', 'ฤทัยรัตน์ '],
            [5, 'วสันต์ ศิลพงษ์', 'วสันต'],
            [11, 'Ton Suttisuk', 'Ton Suttisu'],
            [7, 'ศุภวัน เพ็ชรรัตน์', 'ศุภวัน '],
            [17, 'จารุปกรณ์ แสนภักดี', 'จารุปกรณ์ แสนภักด'],
            [null, null, 'error']
            
        ];
    }
    /**
     * @dataProvider dataProviderForSplitNameWithSpace
    */
    public function test_splitNameWithSpace($expected, $fullName)
    {
        $actual = $this->obj->splitNameWithSpace($fullName);
        $this->assertEquals($expected, $actual);
    }
    public function dataProviderForSplitNameWithSpace()
    {
        return [
            [['panu','boonpromsook'],'panu boonpromsook'],
            [['panu','f','boonpromsook'],'panu f boonpromsook'],
            [['มานพ','ณ','สงขลา'],'มานพ ณ สงขลา'],
            [['john','f','kenedy','a'],'john f kenedy a '],
            [['panu'],'panu'],
            [['a','b','c','d','e','f'],'a   b c    d e f             '],
            [['a','b','c','i','d','e','f'],'    a     b    c   i    d e f    '],
            [['a','b','c','i','d','e','f'],'a     b    c   i    d e f    '],
            [[''],'']
        ];
    }


}
