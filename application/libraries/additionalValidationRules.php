<?php
namespace cientity;
class additionalValidation {	
	const rules = 
	[
		'devExtInstructors'=>[
				'IDNo'=>'exact_length[13]|numeric'
				,'emailAddress'=>'valid_emails'
				,'phoneNumber'=>'min_length[10]'
			]
		,'devClassBudgets'=>[
				'amount'=>'greater_than_equal_to[20]'
			]
		,'devExpenseTypes'=>[
				'accountCode'=>'numeric|min_length[5]'
		]
		,'devSubjects'=>['shopDuration'=>'greater_than_equal_to[0]']
		//,'devClassEnrollists'=>['comments'=>'valid_email|required']
	];
	
	const validationErrorMessage = [
		
		/**/
		/*
		'min_length'=>'{field} ต้องยาวอย่างน้อย {param} ตัวอักษร'
		,'required'=> 'ยังไม่ได้กรอก {field}'
		 ,'is_unique'=> '{field} ที่ระบุมา มีใช้อยู่แล้ว
		,'exact_length'=>'{field} ความยาวต้องเท่ากับ {param} ตัวอักษร'
		,'numeric'=>'{field} ต้องเป็นตัวเลขเท่านั้น'
		,'integer'=>'{field} ต้องเป็นเลขจำนวนเต็ม'
		,'greater_than_equal_to'=>'{field} ต้องมีค่าตั้งแต่ {param} ขึ้นไป'
		,'valid_emails'=>'{field} {param} ที่ระบุมา ผิดรูปแบบ'
		,'valid_email'=>'{field} {param} ที่ระบุมา ผิดรูปแบบ'
		,'decimal'=>'{field} ต้องเป็นทศนิยม'
		*/
	];
	public static function validationErrorMessage(){
		return self::validationErrorMessage;
	}
	public static function getRules($entityName,$columnName=''){		
		if ($columnName!='')
		{			
			return  isset(self::rules[$entityName][$columnName])? self::rules[$entityName][$columnName]:'';
		}else{
			return self::rules[$entityName];
		}
	}
	public static function getAllRules()
	{
		return self::rules;
	}
	public static function formValidationMessage()
	{
		
	}
}