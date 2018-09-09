<?php
$recipes = 
[
	'devSubjects'=>[	
		'descriptions' => 'Subjects'
		,'addEditModal'=>[
			'columnWidth'=>['id'=>2,'code'=>3,'name'=>7,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
			,'hidden'=>['createdDate','createdBy']
			,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_getUserSessionValue::userName"]
			,'references'=>[ 
				'closedId'=>'sysClosed.descriptions'
				,'preSubjectId'=>'devSubjectsView.codeAndName'
				,'preCourseId'=>'devCourses.name'
			]
			,'fieldLabels'=>[
				'code'=>"Subject Code"
				,'name'=>'Subject Name'
			]
		]
		,'filtersBar'=>[
			'display'=>[
				"devSubjects.name"
				,'devSubjects.classDuration'
				,'devSubjects.shopDuration'
				,"sysClosed.descriptions::devSubjects.closedId;;select status" 													
			]
			,'hidden'=>[] 
			,'between'=>['devSubjects.classDuration','devSubjects.shopDuration']
		]
		,'selectAttributes'=>[
			'fields'=>[
				'devSubjects.code'
				,'devSubjects.name'
				,'devSubjects.classDuration'
				,'devSubjects.shopDuration'
				,'sysClosed.descriptions;;status'
			]
			,'format'=>[]												
		]
		,'join'=>[['left','sysClosed','on'=>[[['devSubjects.closedId','=','sysClosed.id']]]]]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>['assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css']
		,'footer_JS_CSS'=>['assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js']
	]	
];
