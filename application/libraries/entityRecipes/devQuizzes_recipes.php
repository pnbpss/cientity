<?php
$recipes = 
[
	'devQuizzes'=>[
		'descriptions' => 'Quizzes'
		,'addEditModal'=>[
			'hidden'=>[]
			,'columnOrdering'=>['id','groupId','quizTypeId','questions']
			,'references'=>[ 
						'groupId'=>'devQuizGroups.name'
						,'quizTypeId'=>'devQuizTypes.type'
						]
			,'fieldLabels'=>['groupId'=>"Quiz Group",'quizTypeId'=>'Quiz Type','questions'=>'Question']
		]							
		,'filtersBar'=>[
			'display'=>[
				'devQuizGroups.name::;;Quiz Group'
				,'devQuizTypes.type::;;Quiz Type'
				,'devQuizzes.questions;;Questions'
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devQuizGroups.name;;Quiz Group'
				,'devQuizTypes.type;;Quiz Type'
				,'devQuizzes.questions;;Questions'
			]
			,'format'=>[											
				]
		]
		,'join'=>[
			['left','devQuizGroups','on'=>[[['devQuizzes.groupId','=','devQuizGroups.id']]]]
			,['left','devQuizTypes','on'=>[[['devQuizzes.quizTypeId','=','devQuizTypes.id']]]]
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
