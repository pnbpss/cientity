<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax01 extends CI_Controller {
        public function __construct() {
                parent::__construct();
        }
        /**
         * Index Page for this controller.
         *
         * Maps to the following URL
         * 		http://example.com/index.php/welcome
         *	- or -
         * 		http://example.com/index.php/welcome/index
         *	- or -
         * Since this controller is set as the default controller in
         * config/routes.php, it's displayed at http://example.com/
         *
         * So any other public methods not prefixed with an underscore will
         * map to /index.php/welcome/<method_name>
         * @see https://codeigniter.com/user_guide/general/urls.html
         */
        public function index()
        {
                
        }
        public function retStr($leftR,$str)
        {
                //$this->load->library('calendar');
                
                //$this->load->view('welcome_message');

                if(($leftR<0) || (is_null($leftR)))
                {
                    return 'error';
                }
                //$str = 'Panu Boonpromsook';

                $left = ($leftR > mb_strlen($str)) ? mb_strlen($str) : $leftR;

                $rtStr = '';
                for($i=0;$i<$left;$i++)
                {
                    //$rtStr.=$str[$i];
                    $rtStr.=mb_substr($str,$i,1,'UTF-8');
                }
                return $rtStr;
        }
        public function getNumber()
        {
            return 500.0001;
        }
        public function multiplyByTwo($number)
        {
            return $number*2;
        }
        public function dividedByTwo($number)
        {
            return $number/2;
        }
        public function splitNameWithSpace($fullName)
        {
            if ($fullName=='')
            {
                    return array('');
            }
            $dummyStr = '&==&==&';
            $_modifiedFullName = preg_replace('/\s+/', $dummyStr, $fullName);
            $arrayOfName = explode($dummyStr, $_modifiedFullName);
            
            if(((isset($arrayOfName[sizeof($arrayOfName)-1])))  && ($arrayOfName[sizeof($arrayOfName)-1]==''))
            {
                unset($arrayOfName[sizeof($arrayOfName)-1]);
            }
            if(((isset($arrayOfName[0])))  && ($arrayOfName[0]==''))
            {
                unset($arrayOfName[0]);
                foreach($arrayOfName as $key=>$val)
                {
                        $tempArray[$key-1]  = $val;
                }
                $arrayOfName = $tempArray;
            }
            return $arrayOfName;
        }
}
