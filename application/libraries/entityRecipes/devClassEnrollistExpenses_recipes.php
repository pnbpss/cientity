<?php
/**
* Class member's expenses are expense of class member who joined the class of each member. The expenses can be redeem later, or widthdraw from company.
*/
$recipes = 
[
'devClassEnrollistExpenses'=>[
		'descriptions' => "Class Member's Expenses."
		//,'moreDetails'=> "Class member's expenses are expense of class member who joined the class of each member. The expenses can be redeem later, or widthdraw from company."
		,'addEditModal'=>[
			'dummy'=>[] //Nothing special for this key, just lazy to add comma if I deleted the second key.
			//,'hidden'=>['testTime'],'default'=>['testTime'=>'sql::NULL']
			,'columnWidth'=>['classEnrollId'=>12]
			,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
						'classEnrollId'=>'devClassEnrollistsView.empAndClass'
						,'expenseId'=>'devExpenseTypes.name' 						
						
						]
			,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
						'classEnrollId'=>'Employee code/name and Class Descriptions'
						,'expenseId'=>'Expense Name']
		]
		,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
			'display'=>[
					"devClasses.descriptions::;;Class Descriptions"
					,'devEmployeesView.FLName::;;Employee Name'					
					//,'devClassEnrollists.testTime;;test time'
					]
			,'between'=>[]
		]
		,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
			'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
				'devClassEnrollistExpensesView.employeeCode;;Employee Code'
				,'devClassEnrollistExpensesView.employeeFullName;;Employee Name'
				,'devClassEnrollistExpensesView.classDescriptions;;Class Descriptions'
				,"devClassEnrollistExpensesView.locationCode;;Class Location" 
				,'devClassEnrollistExpensesView.amount;;Amount'
				,'devClassEnrollistExpensesView.refused;;Refused'
				,'devClassEnrollistExpensesView.acknowledged;;Acknowledged'				
				]
			,'format'=>[]						
		]
		,'join'=>[ //การ join กัน ของ table ต่างๆ ใน 'selectAttributes'=>'field'
					['left','devClassEnrollistExpensesView','on'=>[[['devClassEnrollistExpenses.id','=','devClassEnrollistExpensesView.id']]]]
				]
		,'template'=>'projects.html'
		,'header_JS_CSS'=>[
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		]
		,'footer_JS_CSS'=>[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		]
	]
	#endregion devClassEnrollists
];