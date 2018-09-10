<?php
/**
* Class member's expenses are expense of class member who joined the class of each member. The expenses can be redeem later, or widthdraw from company.
*/
$recipes = 
[
'devClassEvaluations'=>[
		'descriptions' => "Class Evaluations"
		//,'moreDetails'=> "Class member's expenses are expense of class member who joined the class of each member. The expenses can be redeem later, or widthdraw from company."
		,'addEditModal'=>[
			'dummy'=>[] //Nothing special for this key, just lazy to add comma if I deleted the second key.
			//,'hidden'=>['testTime'],'default'=>['testTime'=>'sql::NULL']
			,'columnWidth'=>['classEvaluatorId'=>12,'classEnrollistId'=>12]
			,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
						'classEnrollistId'=>'devClassEnrollistsView.empAndClass'
						,'classEvaluatorId'=>'devClassEvaluatorsView.empAndClass' 						
						,'attendanceId'=>'devClassAttendances.attendance'
						,'resultId'=>'devEvalResults.results'
						]
			,'hidden'=>['evalDate']
			,'default'=>['evalDate'=>'sql::getdate()']
			,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
						'classEnrollistId'=>'Student: Employee code/name and Class Descriptions'
						,'classEvaluatorId'=>'Evaluator: Employee Name and Class Descriptions']
						,'attendanceId'=>'How Attendance?'
						,'resultId'=>'Evaluation Result'
		]
		,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
			'display'=>[
					"devClasses.descriptions::;;Class Descriptions"
					,'devEmployeesView.FLName::;;Employee Name(Student)'
					,'devEmployeesView2.FLName::;;Employee Name(Evaluator)'
					//,'devClassEnrollists.testTime;;test time'
					]
			,'between'=>[]
		]
		,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
			'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
				'devClassEnrollistsView.employeeCode;;Employee Code'
				,'devClassEnrollistsView.employeeFullName;;Employee Name'
				,'devClassEnrollistsView.classDescriptions;;Class Descriptions'
				,"devClassEnrollistsView.locationCode;;Class Location" 				
				,'devClassEnrollistsView.refused;;Refused'
				,'devClassEnrollistsView.acknowledged;;Acknowledged'
				,'devClassAttendances.attendance'
				,'devEvalResults.results'
				,'devClassEvaluatorsView.empAndClass;;Evaluator'
				]
			,'format'=>[]						
		]
		,'join'=>[
			['left','devClassEnrollistsView','on'=>[[['devClassEvaluations.classEnrollistId','=','devClassEnrollistsView.id']]]]
			,['left','devClassAttendances','on'=>[[['devClassEvaluations.attendanceId','=','devClassAttendances.id']]]]
			,['left','devEvalResults','on'=>[[['devClassEvaluations.resultId','=','devEvalResults.id']]]]
			,['left','devClassEvaluatorsView','on'=>[[['devClassEvaluations.classEvaluatorId','=','devClassEvaluatorsView.id']]]]
		]
		
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
	#endregion devClassEnrollists
];