<?php
$recipes = [
	'devEmployees'=>[
		'descriptions' => 'Employees'
		,'customized'=>false
		,'addEditModal'=>[			
			'format'=>[
				'birthDate'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') birthDate"
				,'workStart'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') workStart"
				,'workEnd'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') workEnd"
				] 
			
		] //end of 'addEditModal' key
		,'filtersBar'=>[ //ฟิลด์ที่จะใช้เป็นเงื่อนไขในการ search ตรง filter row		
			'display'=>[				
				"devEmployees.employeeCode;;Employee' Code" 
				,"devEmployees.firstName;;First Name"
				,"devEmployees.lastName;;Last Name"
				,"devEmployees.workStart;;Start Work"				
				,"devEmployees.officeName;;Office Name"
				]
		] // end of 'filtersBar' key
		,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น
			'fields'=>[ 
				'devEmployees.employeeCode'				
				,'devEmployees.firstName'
				,'devEmployees.lastName'				
				,'devEmployees.birthDate'
				,'devEmployees.workStart'				
				,'devEmployees.IDNo'
				,'devEmployees.positionName'
				,'devEmployees.officeName'
				,'devEmployees.em_status'				
			]

			,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
				'devEmployees.birthDate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) birthDate"
				,'devEmployees.workStart'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) workStart"
				,'devEmployees.workEnd'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) workEnd"
				]			
			,'editableInSubEntity'=>[]
		] // end of 'selectAttributes' key
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	] // end of '..entityName..' key
];
