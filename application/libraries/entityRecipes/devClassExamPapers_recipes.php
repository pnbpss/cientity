<?php
$recipes = 
[
	'devClassExamPapers'=>[
		'descriptions' => 'Class Exampapers'
		,'addEditModal'=>[
			'hidden'=>[]
			,'columnOrdering'=>['id','classId','quizId','score']
			,'references'=>[ 
						'quizId'=>'devQuizzes.questions'
						,'classId'=>'devClassesViewForExamPaper.descriptions'
						]
			,'fieldLabels'=>['quizId'=>"Question",'classId'=>'Class','score'=>'Full Mark']
		]
		,'filtersBar'=>[
			'display'=>[
				'devQuizzes.questions::;;Question'
				,'devClassesViewForExamPaper.descriptions::;;Class Descriptions'				
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devQuizzes.questions;;Questions'
				,'devClassesViewForExamPaper.descriptions;;Class Descriptons'				
			]
			,'format'=>[											
				]
		]
		,'join'=>[
			['left','devQuizzes','on'=>[[['devClassExamPapers.quizId','=','devQuizzes.id']]]]
			,['left','devClassesViewForExamPaper','on'=>[[['devClassExamPapers.classId','=','devClassesViewForExamPaper.id']]]]
		]
		
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]

	]	
];
