<?php
$recipes = 
[
	#region devClassInstructors
	'devClassInstructors'=>[
		'descriptions' => 'Class\'s Internal Instructors'							
		,'addEditModal'=>[
			'references'=>[ 
				'classId'=>'devClasses.descriptions'
				,'employeeId'=>'devEmployeesView.IDNoAndFullName' 
				]
			,'fieldLabels'=>[ 
				'classId'=>'Class Descriptions'							
				,'employeeId'=>'PID and Staff Full Name'
				]
		]
		,'filtersBar'=>[ 
			'display'=>[
				"devClassInstructorsView.locationCode;;Class Location Code" 
				,'devClassInstructorsView.employeeFullName;;Staff Full Name'
				,'devClassInstructorsView.classDescriptions;;Class Descriptions'
			]
			,'between'=>[]
		]
		,'selectAttributes'=>[ 
			'fields'=>[ 
				'devClassInstructorsView.employeeFullName;;Staff Full Name'
				,'devClassInstructorsView.positionName;;Position Name'													
				,'devClassInstructorsView.classDescriptions;;Class Descriptions'
				,"devClassInstructorsView.locationCode;;Class Location Code" //ต้องระบุ entityName ด้วย
				,'devClassInstructorsView.classStartDate;;Class Start Date'
				,'devClassInstructorsView.percentLoad;;Percent Load'
				,'devClassInstructorsView.comments;;Comment or Descriptions'
				]
			,'format'=>[]
			,'editableInSubEntity'=>[ //see description in 'devClassEnrolllists'
				'percentLoad'=>'percentLoad'
				,'comments'=>'comments'
				]
		]
		,'join'=>[['left','devClassInstructorsView','on'=>[[['devClassInstructors.id','=','devClassInstructorsView.id']]]]]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
];
