<?php
/**
 * Class entityRecipes 
 * @author Panu Boonpromsook <pnbpss@gmail.com>
 * 
 * entityRecipes declares and supplies an information each entity for class formResponses and mainForm.
 * Information in this file will be varied on table in database, or table design. 
 * 
 * For none Thai reader, you can ignore Thai comment in this file. It provided for easier Thai reader to understand the code. The contexts are same as English. 
 */
class entityRecipes {
	
	private $default_header_JS_CSS;
	private $default_footer_JS_CSS;
	private $recipes = [];
	/**
	 * default header of Javascript and CSS file linked
	 */
	function __construct() {		
	
		$this->default_header_JS_CSS=[		
			'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
		];
		/**
		 * default footer  Javascript and CSS file linked
		 */
		$this->default_footer_JS_CSS=[
			'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
		];
		/**
		 * conts info is array of entity properties, these are information that will be use to 
		 * - construct SQL for search in filter row
		 * - select data from table
		 * - etc.
		 * devClasses and devClassEnrollists will be use as examples, so I will put a comment to described each Item one by one.
		 */
		$this->recipes = [
			'dummy'=>[]
			#endregion devClasses
			#region devClassEnrollists
			,'devClassEnrollists'=>[
				'descriptions' => 'Class Enrollments'
				,'addEditModal'=>[
					'dummy'=>[]
					,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId'	,'testTime'
						]
					,'hidden'=>['testTime'],'default'=>['testTime'=>'sql::NULL']
					,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
								'classId'=>'devClasses.descriptions'
								,'employeeId'=>'devEmployeesView.IDNoAndFullName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
								,'acknowledgedId'=>'sysAcknowledges.descriptions'
								,'refusedId'=>'sysRefuses.descriptions'
								]
					,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
								'classId'=>'Class Descriptions'
								,'employeeId'=>'PID, First Name and Last Name ']
				]
				,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
								'display'=>[
										"devClassEnrollistsView.locationCode;;Location Code"
										,'devClassEnrollistsView.employeeFullName;;Employee Name'
										,'devClassEnrollistsView.classDescriptions;;Class Descriptions'
										//,'devClassEnrollists.testTime;;test time'
										]
								,'between'=>[]
				]
				,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
						'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
							'devClassEnrollistsView.employeeFullName;;Employee Name'
							,'devClassEnrollistsView.positionName;;Position'
							,'devClassEnrollistsView.classDescriptions;;Course Descriptions'
							,"devClassEnrollistsView.locationCode;;Class Location" //ต้องระบุ entityName ด้วย
							,'devClassEnrollistsView.classStartDate;;Start Date'
							,'devClassEnrollistsView.refused;;Refused'
							,'devClassEnrollistsView.acknowledged;;Acknowledged'
							,'devClassEnrollistsView.comments;;Comment'
							]
						,'format'=>[
							'devClassEnrollistsView.testDate'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') testDate"
							,'devClassEnrollistsView.testDateTime'=>"CONVERT(varchar(10),".FRPLCEMNT4FMT.",103)+STUFF(RIGHT(' ' + CONVERT(VarChar(7),cast(".FRPLCEMNT4FMT." as time), 0), 7), 6, 0, ' ') testDateTime"
							,'devClassEnrollistsView.testTime'=>"STUFF(RIGHT(' ' + CONVERT(VarChar(7),".FRPLCEMNT4FMT.", 0), 7), 6, 0, ' ') testTime"
						]

						/**
						 * ### complete explanation about 'editableInSubEntity' key ###
						 * The key 'editableInSubEntity' values are informations that tell CI-Entity,in formResponse, to create input tag in
						 * data-table of sub-entity.
						 * Critical:yes, if this entity is a sub-entity to other entity.
						 * Each key, except '_validationInfo_', of 'editableInSubEntity' is a representation of the way how to create input tag.
						 *
						 *  For example 'refused', exactly is not column of devClassEnrollists, is column in devClassEnrollistsView (use as alterView 
						 * in main-entity). The 'refused' must be linked to other table to create select2, but it does not have information for create.
						 * You must tell CI-Entity which field in devClassEnrollists is mapped to it. The element 'refused'=>'l::refusedId' means
						 * devClassEnrollistsView.refused is mapped to devClassEnrollists.refusedId. The alphabet 'l' in front of '::' mean link-to.
						 * 
						 * if the key 'editableInSubEntity' not declared, the sub-entity will display data only.
						 */
						,'editableInSubEntity'=>[
							'refused'=>'l::refusedId' //for create select2 and link to sysRefuses, l:refusedId means link and use field refusedId in addEditModal=>references
							,'acknowledged'=>'l::acknowledgedId' // same logic as key 'refused'
							,'comments'=>'comments' //create input text 
							,'testTime'=>'testTime' //This won't effect if 'addEditModal' specified 'hidden'

							/**
							 * The key _validationInfo_ values tell backend the field that will be used to get validation rules.
							 */
							,'_validationInfo_'=>[
								'refused'=>'refusedId' //use validation rules of devClassEnrollists.refusedId
								,'acknowledged'=>'acknowledgedId' //use validation rules of devClassEnrollists.acknowledgedId
							]
						]
				]
				,'join'=>[ //การ join กัน ของ table ต่างๆ ใน 'selectAttributes'=>'field'
							['left','devClassEnrollistsView','on'=>[[['devClassEnrollists.id','=','devClassEnrollistsView.id']]]]
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
			#region devExtInstructors
			,'devExtInstructors'=>[
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
													,'format'=>[
														]
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
			#endregion devExtInstructors
			#region devExpenseTypes
			,'devExpenseTypes'=>[
								'descriptions' => 'Types of Expense'
								,'addEditModal'=>[
									'dummy'=>[]
									//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
									//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
									,'hidden'=>['createdDate','createdBy']
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
												'closedId'=>'sysClosed.descriptions'
												]
								]							
								,'filtersBar'=>[
													'display'=>[
														'devExpenseTypes.name'
														,'devExpenseTypes.accountCode'
														,'sysClosed.descriptions::devExpenseTypes.closedId;;Select Status'
													]
													,'hidden'=>[]
												]
								,'selectAttributes'=>[
													'fields'=>[
															 'devExpenseTypes.name'
															 ,'devExpenseTypes.accountCode;;Account Code' //ถ้ามี ;; ติดมา แสดงว่าเอา description หลัง ;;
															 ,'sysClosed.descriptions;;Open or Closed'
														]
													,'format'=>[													
														]
												]
								,'join'=>[
									['left','sysClosed','on'=>[[['devExpenseTypes.closedId','=','sysClosed.id']]]]
								]
								,'template'=>'projects.html'
								,'header_JS_CSS'=>[
									'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
								]
								,'footer_JS_CSS'=>[
									'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
								]

							]
							#endregion devExpenseTypes
			#region devClassBudgets
			,'devClassBudgets'=>[
								'descriptions' => 'Class Expenses'							
								,'addEditModal'=>[
									'dummy'=>[]
									//,'columnOrdering'=>['id','classId','expenseId','amount','comments']
									//,'hidden'=>['createdDate','createdBy']
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
												'classId'=>'devClasses.descriptions'											
												,'expenseId'=>'devExpenseTypes.name'											
												]
									,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
												'classId'=>'Class Descriptions'
												,'expenseId'=>'Expense Type']
								]
								,'filtersBar'=>[
													'display'=>[
														'devExpenseTypes.name::devClassBudgets.expenseId'
														,'devClasses.descriptions::devClassBudgets.classId;;Class Descriptions'													
													]
												,'hidden'=>[]
												]
								,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น
													'fields'=>[ //มีฟิลด์อะไรบ้าง
															 'devClasses.descriptions;;Class Descriptions'														 
															,'devLocations.code;;Location Code'
															//,'devLocations.descriptions;;สถานที่อบรม'
															,'devClasses.startDate;;Class Start Date'														
															,'devExpenseTypes.name;;Name of Expense'
															,'devClassBudgets.amount;;Amount'
															,'devClassBudgets.comments;;Expense Descriptions'
														]
													,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
														'devClasses.startDate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) startDate"
														,'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
														,'devExpenseTypes.name'=>"".FRPLCEMNT4FMT." expenseTypeName"
														]
													,'editableInSubEntity'=>[ 
																//editable is information that tell formResponse to create input in datatable, its item must be subset of selectAttributes,
																//this information is for create select2 and link to devExpenseTypes, l::expenseId means link and use field expenseId in addEditModal=>references
																'expenseTypeName'=>'l::expenseId' 
																,'amount'=>'amount'
																,'comments'=>'comments' //edit by input text															
																,'_validationInfo_'=>[ 
																		//tell backend which field to be validate with main entity,if not specfitied means use that key
																		//'refused'=>'refusedId' 
																		//,'acknowledged'=>'acknowledgedId' //for create select2 and link to sysAcknowledges
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
			#region devClassInstructors
			,'devClassInstructors'=>[
								'descriptions' => 'Class\'s Internal Instructors'							
								,'addEditModal'=>[
									'dummy'=>[]
									//,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId']
									//,'hidden'=>['createdDate','createdBy']
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
												'classId'=>'devClasses.descriptions'
												,'employeeId'=>'devEmployeesView.IDNoAndFullName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
												]
									,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
												'classId'=>'Class Descriptions'							
												,'employeeId'=>'PID and Staff Full Name'
												]
								]
								,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
												'display'=>[
														"devClassInstructorsView.locationCode;;Class Location Code" 
														//,'devSubjects.name::devSubjectCourse.subjectId::devClasses.scId::classId;;ชื่อวิชา'													
														,'devClassInstructorsView.employeeFullName;;Staff Full Name'
														,'devClassInstructorsView.classDescriptions;;Class Descriptions'
														//,'devEmployees.officeName;;ฝ่ายของพนักงาน'													
														]
												,'between'=>[]
								]
								,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
												'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
														'devClassInstructorsView.employeeFullName;;Staff Full Name'
														,'devClassInstructorsView.positionName;;Position Name'													
														,'devClassInstructorsView.classDescriptions;;Class Descriptions'
														,"devClassInstructorsView.locationCode;;Class Location Code" //ต้องระบุ entityName ด้วย
														,'devClassInstructorsView.classStartDate;;Class Start Date'
														,'devClassInstructorsView.percentLoad;;Percent Load'
														,'devClassInstructorsView.comments;;Comment or Descriptions'
														]
												,'format'=>[
													//'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
												]
												,'editableInSubEntity'=>[ //see description in 'devClassEnrolllists'
													'percentLoad'=>'percentLoad'
													,'comments'=>'comments'
													]
									]
								,'join'=>[ //การ join กัน ของ table ต่างๆ ใน 'selectAttributes'=>'field'
											['left','devClassInstructorsView','on'=>[[['devClassInstructors.id','=','devClassInstructorsView.id']]]]

										]
								,'template'=>'projects.html'
								,'header_JS_CSS'=>[
									'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
								]
								,'footer_JS_CSS'=>[
									'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
								]
							]
			#endregion devClassInstructors
			#region devClassExtInstructors
			,'devClassExtInstructors'=>[
							'descriptions' => 'Class\'s External Instructors'
							,'addEditModal'=>[
									'dummy'=>[]
									//,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId']
									//,'hidden'=>['createdDate','createdBy']
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
												'classId'=>'devClasses.descriptions'
												,'extInstructorId'=>'devExtInstructors.firstName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
												]
									,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
												'classId'=>'Class Descriptions'							
												,'extInstructorId'=>'External Instructor\'s name'
												]
								]
								,'filtersBar'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
												'display'=>[													

														"devClassExtInstructorsView.locationCode;;Class Location Code" 													
														,'devClassExtInstructorsView.subjectName;;Subject '
														,'devClassExtInstructorsView.fullName;;Full Name'
														,'devClassExtInstructorsView.classDescriptions;;Class Descriptions'
														//,'devEmployees.officeName;;ฝ่ายของพนักงาน'													
														]
												,'between'=>[]
								]
								,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
														'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
																'devClassExtInstructorsView.fullName;;Full Name'
																,'devClassExtInstructorsView.percentLoad;;Percent of Load'
																,'devClassExtInstructorsView.classDescriptions;;Class Descriptions'
																,"devClassExtInstructorsView.locationCode;;Class Location Code" //ต้องระบุ entityName ด้วย
																,"devClassExtInstructorsView.phoneNumber;;Cellphone No." 
																,"devClassExtInstructorsView.emailAddress;;e-mail" 
																]
														,'format'=>[
															//'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
														]
														,'editableInSubEntity'=>[ //see description in 'devClassEnrolllists'
																'percentLoad'=>'percentLoad'
																,'comments'=>'comments'
															]
												]
								,'join'=>[ //การ join กัน ของ table ต่างๆ ใน 'selectAttributes'=>'field'
											['left','devClassExtInstructorsView','on'=>[[['devClassExtInstructors.id','=','devClassExtInstructorsView.id']]]]
										]
			]
			#endregion devClassExtInstructors
			#region devLocations
			,'devLocations'=>[
								'descriptions' => 'Locations'							
								,'addEditModal'=>[
									'dummy'=>[]
									//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
									//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
									//,'hidden'=>['createdDate','createdBy']
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
												'closedId'=>'sysClosed.descriptions'
												,'internalId'=>'sysYesNo.yesno'
												//,'preSubjectId'=>'devSubjectsView.codeAndName'
												//,'preCourseId'=>'devCourses.name'
												]
								]
								,'filtersBar'=>[
												'display'=>[
														"devLocations.code" 
														,"devLocations.descriptions" //ต้องระบุ entityName ด้วย
														,'devLocations.numberOfSeat'
														,"sysClosed.descriptions::closedId;;location status"	//;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง (จะไม่เอา descriptions ใน column นั้นมาใช้)
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
														,'format'=>[											
															'sysClosed.descriptions'=>"".FRPLCEMNT4FMT." closedDescription"
															]
												]
								,'join'=>[
											['left','sysClosed','on'=>[
																		[
																			['devLocations.closedId','=','sysClosed.id'] //and
																		]//or
																	]
											]
											,['left','sysYesNo','on'=>[
																		[
																			['devLocations.internalId','=','sysYesNo.id'] //and
																		]//or
																	]
											]
										]
								,'template'=>'projects.html'
								,'header_JS_CSS'=>[
									'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
								]
								,'footer_JS_CSS'=>[
									'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
								]
			]
			#endregion devLocations
			#region devCourses
			,'devCourses'=>[
								'descriptions' => 'Courses'							
								,'addEditModal'=>[
									'dummy'=>[]
									//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
									//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
									,'hidden'=>['createdDate','createdBy']
									,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_getUserSessionValue::userName"] //default คือค่าที่จะ insert หากไม่ระบุไป จะเอา default ใน ฐานข้อมูล _getUserSessionValue คือฟังก์ชั่นใน mainForms, sql คือ function ใน sql
									,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
												'closedId'=>'sysClosed.descriptions'
												//,'preSubjectId'=>'devSubjectsView.codeAndName'
												//,'preCourseId'=>'devCourses.name'
												]
								]
								,'filtersBar'=>[
												'display'=>[
														"devCourses.code" 
														,"devCourses.name" 																									
														,"sysClosed.descriptions::closedId;;status of course"	//;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง (จะไม่เอา descriptions ใน column นั้นมาใช้)
														]
												,'between'=>[]

								]
								,'selectAttributes'=>[ //คือ select ใน filter row
													'fields'=>[
															'devCourses.code'
															,'devCourses.name'
															,'devCourses.objectives'
															,'sysClosed.descriptions;;status'														
														]
													,'format'=>[]												
												]
								,'join'=>[
											['left','sysClosed','on'=>[
												[
													['devCourses.closedId','=','sysClosed.id'] //and
												]//or
											]
											]
										]
								,'template'=>'projects.html'
								,'header_JS_CSS'=>[
									'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
								]
								,'footer_JS_CSS'=>[
									'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
								]
							]
			#endregion devCourses
			#region devSubjects
			,'devSubjects'=>[	'descriptions' => 'Subjects'
							,'addEditModal'=>[
								'dummy'=>[]
								//,'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
								,'columnWidth'=>['id'=>2,'code'=>3,'name'=>7,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
								,'hidden'=>['createdDate','createdBy']
								,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_getUserSessionValue::userName"] //default คือค่าที่จะ insert หากไม่ระบุไป จะเอา default ใน ฐานข้อมูล _getUserSessionValue คือฟังก์ชั่นใน mainForms, sql คือ function ใน sql
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
											'closedId'=>'sysClosed.descriptions'
											,'preSubjectId'=>'devSubjectsView.codeAndName'
											,'preCourseId'=>'devCourses.name'
											]
								,'fieldLabels'=>[
											'code'=>"Subject Code"
											,'name'=>'Subject Name'
											]
							]
							,'filtersBar'=>[
												'display'=>[
													"devSubjects.name"
													,'devSubjects.classDuration'
													,'devSubjects.shopDuration'
													,"sysClosed.descriptions::devSubjects.closedId;;select status" 													
												]
												,'hidden'=>[] //hidden คือเงื่อนไขที่จะเอาไปใช้ร่วมในการ search แต่ไม่แสดงออกมาให้เลือก
												,'between'=>['devSubjects.classDuration','devSubjects.shopDuration']
											]
							,'selectAttributes'=>[
												'fields'=>[
														'devSubjects.code'
														,'devSubjects.name'
														,'devSubjects.classDuration'
														,'devSubjects.shopDuration'
														,'sysClosed.descriptions;;status'
													]
												,'format'=>[]												
											]
							,'join'=>[
									['left','sysClosed','on'=>[
																[
																	['devSubjects.closedId','=','sysClosed.id'] //ในกลุ่มนี้ หากมากกว่า 1 หมายถึง and กัน
																]//ถ้า on มีหลาย array หมายถึแต่ละ array or กัน
															]
									] 									
							]
							,'template'=>'projects.html'
							,'header_JS_CSS'=>['assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css']
							,'footer_JS_CSS'=>['assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js']
						]
			#endregion devSubjects
			#region devSubjectCourse
			,'devSubjectCourse'=>[
					'descriptions' => 'Course\'s Subjects'
					,'addEditModal'=>[
							'dummy'=>[]
							,'columnOrdering'=>['id','courseId','subjectId']
							,'columnWidth'=>['id'=>12,'subjectId'=>12,'courseId'=>12]
							,'references'=>[ //references คือ table ที่จะเอาไว้ select2 												
										'subjectId'=>'devSubjectsView.codeAndName'
										,'courseId'=>'devCourses.name'
										]
					]
					,'filtersBar'=>[
									'display'=>[
										"devCourses.name::devSubjectCourse.courseId"	
										,"devSubjects.name::devSubjectCourse.subjectId"

									]
									,'hidden'=>[] //hidden คือเงื่อนไขที่จะเอาไปใช้ร่วมในการ search แต่ไม่แสดงออกมาให้เลือก
									//,'between'=>['devSubjects.classDuration','devSubjects.shopDuration']
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
			#endregion devSubjectCourse
			,'devTest001'=>[
					'descriptions' => 'just for test 001'
			]		
			,'repExpenseReports'=>[
				'customized'=>true
				,'descriptions'=>'Expense reports'
			]			
		];
		
		$recipesFiles = scandir(APPPATH."libraries/entityRecipes");
		foreach($recipesFiles as $file){
			$endWith = substr($file,(strlen($file)-12),12);
			//echo $endWith;
			if(strtolower($endWith)=='_recipes.php'){
				//echo "===".$file."===";
				require APPPATH."libraries/entityRecipes/".$file;
			}
		}
		//exit;
		//require APPPATH."libraries/entityRecipes/sysAdmin_recipes.php";
		//var_dump($this->recipes['sysTasks']);exit;
	}
	function getRecipes(){
		return $this->recipes;
	}
	function getDescriptions($entityName){		
		return $this->recipes[$entityName];		
	}
	function getAllDescriptions(){
		return $this->recipes;
	}
	function getEntityName($taskId){
		$CI =& get_instance();
		$CI->load->database();
		$q = $CI->db->query("select taskName from {$CI->db->dbprefix}sysTasks where id=".$CI->db->escape($taskId).";");
		$row = $q->row();
		if(isset($row)){
			return $row->taskName;
		}else{
			return '404';
		}
	}
	private function infokeysArray(){
		$info = $this->recipes;
		$newArray = [];
		foreach(array_keys($info) as $key){
			array_push($newArray,$key);
		}
		return $newArray;
	}
	function entityNameByURISegment($uriSegment3){
		$entityInfoKey = $this->infokeysArray();		
		$property = explode("_", $uriSegment3);
		$entityOrdinal = (int) $property[0];
		return $entityInfoKey[$entityOrdinal];
	}
	function default_footer_JS_CSS(){
		return $this->default_footer_JS_CSS;
	}
	function default_header_JS_CSS(){
		return $this->default_header_JS_CSS;
	}
} //end of class entityRecipes
