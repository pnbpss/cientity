<?php
$recipes = 
[
	#region devClassExtInstructors
	'devClassExtInstructors'=>[
		'descriptions' => 'Class\'s External Instructors'
		,'addEditModal'=>[
			'references'=>[ 
				'classId'=>'devClasses.descriptions'
				,'extInstructorId'=>'devExtInstructors.firstName' 
			]
			,'fieldLabels'=>[ 
				'classId'=>'Class Descriptions'							
				,'extInstructorId'=>'External Instructor\'s name'
				]
			]
			,'filtersBar'=>[ 
				'display'=>[
					"devClassExtInstructorsView.locationCode;;Class Location Code" 													
					,'devClassExtInstructorsView.subjectName;;Subject '
					,'devClassExtInstructorsView.fullName;;Full Name'
					,'devClassExtInstructorsView.classDescriptions;;Class Descriptions'							
					]
				,'between'=>[]
			]
			,'selectAttributes'=>[ 
				'fields'=>[ 
					'devClassExtInstructorsView.fullName;;Full Name'
					,'devClassExtInstructorsView.percentLoad;;Percent of Load'
					,'devClassExtInstructorsView.classDescriptions;;Class Descriptions'
					,"devClassExtInstructorsView.locationCode;;Class Location Code" //ต้องระบุ entityName ด้วย
					,"devClassExtInstructorsView.phoneNumber;;Cellphone No." 
					,"devClassExtInstructorsView.emailAddress;;e-mail" 
					]
				,'format'=>[]
				,'editableInSubEntity'=>[ //see description in 'devClassEnrolllists'
					'percentLoad'=>'percentLoad'
					,'comments'=>'comments'
				]
			]
		,'join'=>[ 
			['left','devClassExtInstructorsView','on'=>[[['devClassExtInstructors.id','=','devClassExtInstructorsView.id']]]]
		]
	]
	#endregion devClassExtInstructors
];
