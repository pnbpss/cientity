<?php
$recipes = 
[
	'devQuizAnswerSubjectives'=>[
		'descriptions' => "Subjective Question's Answers"
		,'addEditModal'=>[
			'hidden'=>[]
			,'columnOrdering'=>['id','classEnrollListId','classExamPaperId','answer']
			,'references'=>[ 
						'classExamPaperId'=>'devClassExamPapersForSubjectiveAns.classAndQuestion'
						,'classEnrollListId'=>'devClassEnrollistsView.empAndClass'						
						]
			,'fieldLabels'=>['classExamPaperId'=>"Class Exampaper",'classEnrollListId'=>'Class Enrollment','answer'=>'Answer His/Her Filled']
			,'columnWidth'=>['id'=>12,'classExamPaperId'=>12,'classEnrollListId'=>12,'answer'=>12]
		]							
		,'filtersBar'=>[
			'display'=>[				
				'devClassExamPapersForSubjectiveAns.classAndQuestion::;;Select Questions Of Class'
				,'devClassEnrollistsView.empAndClass::;;Employee in Class'
				,"devQuizAnswerSubjectives.answer::;;answer've Made"
				
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devClassEnrollistsView.employeeFullName;;Employee Name'
				,'devClasses.descriptions;;Class Descriptions'				
				,'devQuizzes.questions;;Question'
				,"devQuizAnswerSubjectives.answer;;answer've Made"
			]
			,'format'=>[]
		]
		,'join'=>[
			['left','devClassExamPapersForSubjectiveAns','on'=>[[['devQuizAnswerSubjectives.classExamPaperId','=','devClassExamPapersForSubjectiveAns.id']]]]			
			,['left','devClassEnrollistsView','on'=>[[['devQuizAnswerSubjectives.classEnrollListId','=','devClassEnrollistsView.id']]]]
			,['left','devClasses','on'=>[[['devClassExamPapersForSubjectiveAns.classId','=','devClasses.id']]]]
			,['left','devQuizzes','on'=>[[['devClassExamPapersForSubjectiveAns.quizId','=','devQuizzes.id']]]]
		]		
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]

	]	
];
