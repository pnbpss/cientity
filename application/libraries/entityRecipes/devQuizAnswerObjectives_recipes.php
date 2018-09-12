<?php
$recipes = 
[
	'devQuizAnswerObjectives'=>[
		'descriptions' => "Objective Question's Answers"
		,'addEditModal'=>[
			'hidden'=>[]
			,'columnOrdering'=>['id','classEnrollListId','classExamPaperId','quizChoiceId']
			,'references'=>[ 
						'classExamPaperId'=>'devClassExamPapersForObjectiveAns.classAndQuestion'
						,'classEnrollListId'=>'devClassEnrollistsView.empAndClass'
						,'quizChoiceId'=>'devQuizChoicesForObjectiveAnswer.choice'
						]
			,'fieldLabels'=>['classExamPaperId'=>"Class Exampaper",'classEnrollListId'=>'Class Enrollment','quizChoiceId'=>'Choice his/her made']
			,'columnWidth'=>['id'=>12,'classExamPaperId'=>12,'classEnrollListId'=>12,'quizChoiceId'=>12]
		]							
		,'filtersBar'=>[
			'display'=>[				
				'devClassExamPapersForObjectiveAns.classAndQuestion::;;Select Questions Of Class'
				,'devClassEnrollistsView.empAndClass::;;Employee in Class'
				,"devQuizChoicesForObjectiveAnswer.choice::;;Choices've Made"
				
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devClassEnrollistsView.employeeFullName;;Employee Name'
				,'devClasses.descriptions;;Class Descriptions'				
				,'devQuizzes.questions;;Question'
				,'devQuizChoices.answerOption;;Choice his/her have made'
				,'devQuizOrdinals.ordinal;;Choice Ordinal'
			]
			,'format'=>[]
		]
		,'join'=>[
			['left','devClassExamPapersForObjectiveAns','on'=>[[['devQuizAnswerObjectives.classExamPaperId','=','devClassExamPapersForObjectiveAns.id']]]]
			,['left','devClassExamPapers','on'=>[[['devQuizAnswerObjectives.classExamPaperId','=','devClassExamPapers.id']]]]
			,['left','devClassEnrollistsView','on'=>[[['devQuizAnswerObjectives.classEnrollListId','=','devClassEnrollistsView.id']]]]
			,['left','devClasses','on'=>[[['devClassExamPapers.classId','=','devClasses.id']]]]
			,['left','devQuizzes','on'=>[[['devClassExamPapers.quizId','=','devQuizzes.id']]]]
			,['left','devQuizChoices','on'=>[[['devQuizAnswerObjectives.quizChoiceId','=','devQuizChoices.id']]]]
			,['left','devQuizOrdinals','on'=>[[['devQuizChoices.ordinalId','=','devQuizOrdinals.id']]]]			
			,['left','devQuizChoicesForObjectiveAnswer','on'=>[[['devQuizAnswerObjectives.quizChoiceId','=','devQuizChoicesForObjectiveAnswer.id']]]]
		]		
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]

	]	
];
