<?php
$recipes = 
[
	'devClassReportSubmits'=>[
		'descriptions' => 'Classes Reports'
		,'addEditModal'=>[
			'hidden'=>['fileOneId','fileTwoId','fileThreeId']
			,'default'=>['fileOneId'=>'sql::null','fileTwoId'=>'sql::null','fileThreeId'=>'sql::null']
			//,'columnOrdering'=>['id','classId','quizId','score']
			,'references'=>[ 
						'classEnrollListId'=>'devClassEnrollistsView.empAndClass']
			,'fieldLabels'=>['classEnrollListId'=>"Student/Class",'title'=>'Report Title','reports'=>'Report Contents']
		]
		,'filtersBar'=>[
			'display'=>[
				'devClassEnrollistsView.empAndClass::;;Student/Class'
				,'devClassReportSubmits.title;;Report Title'
				,'devClassReportSubmits.reports;;Report Contents'
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devClassEnrollistsView.empAndClass;;Student/Class'
				,'devClassReportSubmits.title;;Report Title'
				,'devClassReportSubmits.reports;;Report Contents'
			]
			,'format'=>[]
		]
		,'join'=>[
			['left','devClassEnrollistsView','on'=>[[['devClassReportSubmits.classEnrollListId','=','devClassEnrollistsView.id']]]]			
		]		
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]

	]	
];
