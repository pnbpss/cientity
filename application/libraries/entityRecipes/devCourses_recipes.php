<?php
$recipes = 
[
#region devCourses
'devCourses'=>[
		'descriptions' => 'Courses'							
		,'addEditModal'=>[
			'hidden'=>['createdDate','createdBy']
			,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_getUserSessionValue::userName"] 
			,'references'=>[ 
					'closedId'=>'sysClosed.descriptions'					
					]
		]
		,'filtersBar'=>[
			'display'=>[
					"devCourses.code" 
					,"devCourses.name" 																									
					,"sysClosed.descriptions::closedId;;status of course"
					]
			,'between'=>[]

		]
		,'selectAttributes'=>[ 
			'fields'=>[
				'devCourses.code'
				,'devCourses.name'
				,'devCourses.objectives'
				,'sysClosed.descriptions;;status'												
			]
			,'format'=>[]												
		]
		,'join'=>[['left','sysClosed','on'=>[[['devCourses.closedId','=','sysClosed.id']]]]]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
#endregion devCourses
];
