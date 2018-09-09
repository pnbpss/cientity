<?php
$recipes = 
[	
			'devExtInstructors'=>[
				'descriptions' => 'External Instructors'							
				,'addEditModal'=>[
					'dummy'=>[]								
					,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
								'subDistrictId'=>'devSubDistrictsView.fullEnName'											
								]
				]
				,'filtersBar'=>[
					'display'=>[
						'devExtInstructors.firstName'
						,'devExtInstructors.lastName'
						,'devExtInstructors.emailAddress'
						//,'devExtInstructors.IDNo'
					]
					,'hidden'=>[
					]
				]
				,'selectAttributes'=>[
					'fields'=>[
						 'devExtInstructors.firstName'
						,'devExtInstructors.lastName'
						,'devExtInstructors.phoneNumber'
						,'devExtInstructors.emailAddress'
						,'devExtInstructors.address'
						,'devSubDistrictsView.fullEnName;;subDistrict/District/Province' //incase of use derived data from other table, descriptions must be specified
					]
					,'format'=>[]
				]
				,'join'=>[
					['left','devSubDistrictsView','on'=>[[['devExtInstructors.subDistrictId','=','devSubDistrictsView.id']]]]
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
