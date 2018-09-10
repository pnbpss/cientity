<?php
$recipes = 
[
	'devClassEvaluators'=>[
		'descriptions' => 'Class Evaluators'
		,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
			'display'=>[
				"devClassEvaluatorsView.locationCode;;Location Code"
				,'devClassEvaluatorsView.employeeFullName;;Employee Name'
				,'devClassEvaluatorsView.classDescriptions;;Class Descriptions'										
			]
			,'between'=>[]
		]
		,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
			'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
				'devClassEvaluatorsView.employeeFullName;;Employee Name'
				,'devClassEvaluatorsView.positionName;;Position'
				,'devClassEvaluatorsView.classDescriptions;;Course Descriptions'
				,"devClassEvaluatorsView.locationCode;;Class Location" //ต้องระบุ entityName ด้วย
				,'devClassEvaluatorsView.classStartDate;;Start Date'
				,'devClassEvaluatorsView.comments;;Comment'
				]
			,'format'=>[
				'devClassEvaluatorsView.classStartDate'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') classStartDate"				
			]		
			,'editableInSubEntity'=>[
				'comments'=>'comments' //create input text
				
				/**
				 * The key _validationInfo_ values tell backend the field that will be used to get validation rules.
				 * if it field does not specified here, but the input field is use in sub-entity, It will use the validation rule as main-entity use.
				 */
				,'_validationInfo_'=>[
				]
			]
		]
		,'join'=>[['left','devClassEvaluatorsView','on'=>[[['devClassEvaluators.id','=','devClassEvaluatorsView.id']]]]]
		,'addEditModal'=>[
			'dummy'=>[]			
			,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
						'classId'=>'devClasses.descriptions'
						,'employeeId'=>'devEmployeesView.IDNoAndFullName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
						//,'acknowledgedId'=>'sysAcknowledges.descriptions'
						//,'refusedId'=>'sysRefuses.descriptions'
						]
			,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
						'classId'=>'Class Descriptions'
						,'employeeId'=>'PID, First Name and Last Name ']
		]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
];
