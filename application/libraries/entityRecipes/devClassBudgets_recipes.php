<?php
$recipes = 
[
#region devClassBudgets
	'devClassBudgets'=>[
		'descriptions' => 'Class Expenses'							
		,'addEditModal'=>[
			'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
				'classId'=>'devClasses.descriptions'											
				,'expenseId'=>'devExpenseTypes.name'											
			]
			,'fieldLabels'=>[ 
				'classId'=>'Class Descriptions'
				,'expenseId'=>'Expense Type'
			]
		]
		,'filtersBar'=>[
			'display'=>[
				'devExpenseTypes.name::devClassBudgets.expenseId'
				,'devClasses.descriptions::devClassBudgets.classId;;Class Descriptions'													
			]
		,'hidden'=>[]
		]
		,'selectAttributes'=>[ 
			'fields'=>[ 
				 'devClasses.descriptions;;Class Descriptions'														 
				,'devLocations.code;;Location Code'				
				,'devClasses.startDate;;Class Start Date'														
				,'devExpenseTypes.name;;Name of Expense'
				,'devClassBudgets.amount;;Amount'
				,'devClassBudgets.comments;;Expense Descriptions'
			]
			,'format'=>[ 
				'devClasses.startDate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) startDate"
				,'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
				,'devExpenseTypes.name'=>"".FRPLCEMNT4FMT." expenseTypeName"
				]
			,'editableInSubEntity'=>[ 				
				'expenseTypeName'=>'l::expenseId' 
				,'amount'=>'amount'
				,'comments'=>'comments' //edit by input text															
				,'_validationInfo_'=>[ 						
						'comments'=>'comments' //edit by input text
					]
			]
		]
		,'join'=>[
				['left','devClasses','on'=>[[['devClassBudgets.classId','=','devClasses.id']]]]
				,['left','devExpenseTypes','on'=>[[['devClassBudgets.expenseId','=','devExpenseTypes.id']]]]
				,['left','devLocations','on'=>[[['devClasses.locationId','=','devLocations.id']]]]									
		]
	]
#endregion		
];
