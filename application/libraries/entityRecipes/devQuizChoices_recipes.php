<?php
$recipes = 
[
	'devQuizChoices'=>[
		'descriptions' => 'Quiz Choices'
		,'addEditModal'=>[
			'hidden'=>[]
			//,'columnOrdering'=>['id','groupId','quizTypeId','questions']
			,'references'=>[ 
						'quizId'=>'devQuizzesView.Group_Questions'
						,'ordinalId'=>'devQuizOrdinals.ordinal'
						,'correctId'=>'sysYesNo.yesno'
						]
			,'fieldLabels'=>['quizId'=>"Quiz Group and (Question)",'ordinalId'=>'Ordinal','answerOption'=>'Answer','correctId'=>'Is Correct?']
			,'columnWidth'=>['quizId'=>12]
		]							
		,'filtersBar'=>[
			'display'=>[				
				'devQuizzesView.Group_Questions::;;Select Questions'
				,'devQuizChoices.answerOption;;Answer'
				
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devQuizzesView.Group_Questions;;Question'
				,'devQuizOrdinals.ordinal;;ordinal'				
				,'devQuizChoices.answerOption;;Answer'
				,'sysYesNo.yesno;;Correct choice'
			]
			,'format'=>[]
		]
		,'join'=>[
			['left','devQuizzesView','on'=>[[['devQuizChoices.quizId','=','devQuizzesView.id']]]]
			,['left','devQuizOrdinals','on'=>[[['devQuizChoices.ordinalId','=','devQuizOrdinals.id']]]]
			,['left','sysYesNo','on'=>[[['devQuizChoices.correctId','=','sysYesNo.id']]]]
		]		
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]

	]	
];
