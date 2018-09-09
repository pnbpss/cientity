<?php
$recipes = 
[	
	'devSubjectCourse'=>[
		'descriptions' => 'Course\'s Subjects'
		,'addEditModal'=>[
			'dummy'=>[]
			,'columnOrdering'=>['id','courseId','subjectId']
			,'columnWidth'=>['id'=>12,'subjectId'=>12,'courseId'=>12]
			,'references'=>[ 
				'subjectId'=>'devSubjectsView.codeAndName'
				,'courseId'=>'devCourses.name'
			]
		]
		,'filtersBar'=>[
			'display'=>[
				"devCourses.name::devSubjectCourse.courseId"	
				,"devSubjects.name::devSubjectCourse.subjectId"
			]
			,'hidden'=>[] 			
		]
		,'selectAttributes'=>[
			'fields'=>[
				'devCourses.name'
				,'devSubjects.code'
				,'devSubjects.name'																										
			]
			,'format'=>[
				'devSubjects.name'=>"".FRPLCEMNT4FMT." subjectName"
				,'devCourses.name'=>"".FRPLCEMNT4FMT." courseName"
			]												
		]
		,'join'=>[
			['left','devSubjects','on'=>[[['devSubjectCourse.subjectId','=','devSubjects.id']]]]
			,['left','devCourses','on'=>[[['devSubjectCourse.courseId','=','devCourses.id'] ]]]
		]
	]
];
