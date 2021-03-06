<?php
$recipes = 
[
	'devQuizGroups'=>[
		'descriptions' => 'Quiz Group'
		,'addEditModal'=>[			
			'references'=>[ 
						'closedId'=>'sysClosed.descriptions'
						]
		]							
		,'filtersBar'=>[
			'display'=>[
				'devQuizGroups.name;;Quiz Group Name'
				,'sysClosed.descriptions::;;Select Status'
			]
			,'hidden'=>[]
						]
		,'selectAttributes'=>[
			'fields'=>[
				 'devQuizGroups.name;;Quiz Group Name'
				 ,'sysClosed.descriptions;;Open or Closed'
			]
			,'format'=>[													
				]
		]
		,'join'=>[
			['left','sysClosed','on'=>[[['devQuizGroups.closedId','=','sysClosed.id']]]]
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
