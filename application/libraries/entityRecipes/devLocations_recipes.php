<?php
$recipes = 
[
#region devLocations
'devLocations'=>[
		'descriptions' => 'Locations'							
		,'addEditModal'=>['references'=>['closedId'=>'sysClosed.descriptions','internalId'=>'sysYesNo.yesno']]
		,'filtersBar'=>[
			'display'=>[
				"devLocations.code" 
				,"devLocations.descriptions"
				,'devLocations.numberOfSeat'
				,"sysClosed.descriptions::closedId;;location status"
				]
			,'between'=>['devLocations.numberOfSeat']

		]
		,'selectAttributes'=>[
			'fields'=>[
				'devLocations.code;;Location Code'
				,'devLocations.descriptions;;Descriptions'
				,'sysYesNo.yesno;;Internal'
				,'devLocations.numberOfSeat;;Number Seats'
				,'devLocations.numberOfComputers;;Laptops'
				,'devLocations.projector;;Projectors'
				,'devLocations.microphone;;Mics'
				,'sysClosed.descriptions;;Available?'									
			]
			,'format'=>['sysClosed.descriptions'=>"".FRPLCEMNT4FMT." closedDescription"]
			]
		,'join'=>[['left','sysClosed','on'=>[[['devLocations.closedId','=','sysClosed.id']]]],['left','sysYesNo','on'=>[[['devLocations.internalId','=','sysYesNo.id']]]]]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
#endregion devLocations
];
