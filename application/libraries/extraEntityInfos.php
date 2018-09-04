<?php
/**
 * Class extraEntityInfos 
 * @author Panu Boonpromsook
 * 
 * ExtraEntityInfos declares and supplies an information each entity for class formResponses and mainForm.
 * Information in this file will be varied on table in database, or table design. 
 */
class ExtraEntityInfos {
	/**
	 * default header of Javascript and CSS file linked
	 */
	const default_header_JS_CSS=[		
		'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
	];
	/**
	 * default footer  Javascript and CSS file linked
	 */
	const default_footer_JS_CSS=[
		'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
	];
	/**
	 * conts info is array of entity properties, these are information that will be use to 
	 * - construct SQL for search in filter row
	 * - select data from table
	 * - etc.
	 * devClasses and devClassEnrollists will be use as examples, so I will put a comment to described each Item one by one.
	 */
	const infos = [
		#region devClasses
		/**
		 * The 'devClasses' key is library name or you can call entity name. 
		 * if not specified or not presented or is empty array:
		 * - cannot create addEditModal and the rest components of devClasses.
		 */
		'devClasses'=>[
			
			/**
			 * The 'descriptions' key is label of each entity, will be use as menu items.
			 * if not specified or not presented or is empty array:
			 * - CI-Entity will use table name as menu item.
			 * - Warning on first page of entity will be occurred, but able to add or edit record
			 * - Critical:yes
			 */
			'descriptions' => 'Classes or Seminar or Training'
			
			/**
			 * The 'addEditModal' key contains information for construct add/edit modal (addEditModal). This is addEditModal of 
			 * main-entity, I will described sub-entity in 'subEntity' section key below.
			 * if not specified or not presented or is empty array:
			 * - CI-Entity will use default value for construct add/edit modal. Error may be occurred in some case of table structure.
			 * Critical:yes
			 * Effected:addEditModal, SQL string for insert and update.
			 */
			,'addEditModal'=>[
				
				/**
				 * The 'columnOrdering' keys tell CI-Entity to ordering input of table columns in add/edit modal. This key also 
				 * effected to ordering of column in SQL string for update and insert. 
				 * if not specified or not presented or is empty array: 				 
				 * - CI-Entity will use ordinal of column in a table to ordering input
				 * Critical:no.
				 * Effected:addEditModal, SQL string for insert and update
				 */
				'columnOrdering'=>['id','scId','startDate','locationId','statusId','descriptions','capacity','createdBy','createdDate']
				
				/**
				 * The 'columnWidth' keys tell CI-Entity how width in bootstrap-based of input, it will use width=6 if not presented.
				 * Critical:no.
				 * Effected:addEditModal
				 * 
				 * For more informations, field id will disabled because it is auto-increment (identity). 
				 * Please see "How to design database to suits CI-Entity" in CI-Entity Document.				 * 
				 */
				,'columnWidth'=>['descriptions'=>12]
				
				/**
				 * The 'hidden' keys contain column name that will not be display as input.
				 * if not specified or not presented or is empty array:
				 * - it must be specified if the entity got any column that will not let user enter, won't not create input for user. 
				 *   for example datetime of insert time.	 
				 * Critical:no.
				 * Effected:SQL string for insert, addEditModal.
				 */
				,'hidden'=>['createdDate','createdBy']
				
				/**
				 * The 'default' key tell CI-Entity how to get default values and construct SQL string, for insert, of that column.
				 *  There are  two types of default value: SQL and user function. The keyword 'sql' means use T-SQL function or expression
				 *  followed by string "::". Otherwise, it will use the method [methodName],_getUserSessionValue is method name. 
				 * The key word followed by string "::" is parameter of the method.
				 * The following tells CI-Entity that default value of column createDate is T-SQL function getdate(), and the default
				 * value of column createdBy must be get from function _getUserSessionValue('userName')
				 * 
				 * if not specified or not presented or is empty array:
				 * - if  values of hidden key is not empty, as described above, the 'default' key must be declared.
				 * Critical:yes, if 'hidden' key is presented.
				 * Effected:SQL string for insert.
				 */
				,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_getUserSessionValue::userName"] 
				
				/**
				 * The 'references' key tells CI-Entity where to get options lists for select2 in addEditModal. In some case
				 *  of creating HTML input tag, we must use HTML select tag for select the existing record from other table.
				 * Each element of array ...['references'] consists of key and values: key is column name and value is 
				 * referenced table.column name . For instance, as following, 'statusId'=>'devClassStatuses.descriptions'
				 * means column statusId of table devClasses will be create select tag. This select tag's option will be get 
				 * from table the field descriptions of table devClassStatuses.
				 * Critical:no, but it is not easy for user to enter the id referenced table without selectable.
				 * Effected:addEditModal.
				 */
				,'references'=>[ 
							'statusId'=>'devClassStatuses.descriptions'
							,'scId'=>'devSubjectCourseView.courseAndSubject'
							,'locationId'=>'devLocations.descriptions'
							]
				
				/**
				 * The 'fieldLabels' key is alternative message to use as column label in addEditModal.
				 * if not specified or not presented or is empty array:
				 *	CI-Entity will use descriptions of column as column label instead.
				 * Critical:no
				 * Effected:addEditModal.
				 */
				,'fieldLabels'=>[
					'scId'=>"Course Code and Subject's Name"
					,'capacity'=>'class capacity'
					]
				
				/**
				 * The 'format' key will be specified when you have to change the format of display field in addEditModal,
				 * especially for date or datetime field. The date or datetime column type have to convert to string before
				 * send to end-user.				
				 * if not specified or not presented or is empty array:
				 *	CI-Entity user interface of some entity may act in weird way.
				 * Critical:no
				 * Effected:addEditModal.				 
				 */
				,'format'=>['startDate'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') startDate"] 
				
				/**
				 * The 'subEntity' key tells CI-Entity to put sub-entity add/edit section under main-entity add/edit form in addEditModal. 
				 * For instance, in class add/edit section in main-entity, you want to put class enrollment and class instructor list as sub-entity,
				 * then 'subEntity' key will help you to make it happened.
				 * 
				 * The keys of 'subEntity' key is entity name that will be constructed as sub-entity. 
				 * 
				 */
				,'subEntity'=>[
							'devClassEnrollists' =>[
									'label'=>'Enrollments' //หากระบุ label จะเอา label นี้ไปใช้ หากไม่ระบุ จะไป เอา descriptions ของ entity
									,'alterView'=>'devClassEnrollistsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
									,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
									,'editable'=>true// มีปุ่มให้เลือกลบทางขวาสุดหรือไม่
									,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
							]
							,'devClassInstructors' =>[
									'label'=>'Internal Instructors'
									,'alterView'=>'devClassInstructors.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
									,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
									,'editable'=>True// มีปุ่มให้เลือกลบทางขวาสุดหรือไม่
									,'suppressedFieldsInAdd'=>['id','classId']  //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
								]
							,'devClassExtInstructors' =>[
									'label'=>'External Instructors' 
									,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
									,'alterView'=>'devClassExtInstructorsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
									,'editable'=>true// มีปุ่มให้เลือกลบทางขวาสุดหรือไม่
									,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
								]
							,'devClassBudgets' =>[
									'label'=>'Expenses' 
									,'suppressedFields'=>['classId','startDate','classDescription'] 
									/**suppressedFields คือ ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก ใน selectAttributes 
										ดังนั้นชื่อฟิลด์ ใน suppressedFields จะต้องเป็น subset ของฟิลด์ใน selectAttributes
									*/
									,'alterView'=>'devClassBudgetsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
									,'editable'=>true// มีปุ่มให้เลือกลบทางขวาสุดหรือไม่
									,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
							]												
						]
			]
			,'searchAttributes'=>[ //ฟิลด์ที่จะใช้เป็นเงื่อนไขในการ search ตรง filter row
								'display'=>[ //ตัวที่จะแสดงออกมาให้เลือก
									"devClasses.startDate" //หากเป็น date สร้างสองอัน เพื่อ between 													
									,"devSubjects.name::devSubjectCourse.subjectId::devClasses.scId" //ต้องระบุ entityName ด้วย										
									,"devLocations.descriptions::devClasses.locationId;;Select Location"									
									,"devClasses.capacity"
									,"devClasses.descriptions"
									,"devClassStatuses.descriptions::devClasses.statusId;;Class Status" //;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง
									]
								,'between'=>['devClasses.capacity']  //between is that will be created search filter from and to, it only applicable with none-referenced field
								,'hidden'=>[ //เงื่อนไขที่จะใช้ร่วมในการค้นด้วย แต่ไม่แสดงออกมา
									"devClasses.createdDate > dateadd(year,-1,getdate())"
									,"devClasses.descriptions not like '%test%' "
								]
							]
			,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น
								'fields'=>[ //มีฟิลด์อะไรบ้าง
										 'devCourses.name'
										 ,'devSubjects.name'														
										,'devLocations.code;;Location Code'
										,'devLocations.descriptions;;Location Desc.'
										,'devClasses.startDate;;Start Date'
										,'devClasses.descriptions;;Class Desc.'
										,'devClasses.capacity;;capacity'
										,'devClassStatuses.descriptions;;Status'
									]
				
								/**
								 * It also uses for prevent duplicate colum name from difference table, for examples, table Course contain 
								* field named name and table Subject contains field named name.
								 */
								,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
									'devClasses.startDate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) startDate"													
									,'devCourses.name'=>"".FRPLCEMNT4FMT." courseName"
									,'devSubjects.name'=>"".FRPLCEMNT4FMT." subjectName"
									,'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
									,'devLocations.descriptions'=>"".FRPLCEMNT4FMT." locationDescription"
									,'devClassStatuses.descriptions'=>"".FRPLCEMNT4FMT." statusDescription"
									]
							]
			,'join'=>[
					['left','devSubjectCourse','on'=>[
												[
													['devClasses.scId','=','devSubjectCourse.id'] //and scope
												]//or scope
											]
					] 
					,['left','devCourses','on'=>[[['devSubjectCourse.courseId','=','devCourses.id']]]]
					,['left','devSubjects','on'=>[[['devSubjectCourse.subjectId','=','devSubjects.id']]]]
					,['left','devLocations','on'=>[[['devClasses.locationId','=','devLocations.id']]]]
					,['left','devClassStatuses','on'=>[
												 [
													['devClasses.statusId','=','devClassStatuses.id'] // and scope
												]//or scope				
											]
					]
			]			
			,'header_JS_CSS'=>[
				'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
			]
			,'footer_JS_CSS'=>[
				'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
			]
		]
		#endregion devClasses
		#region devClassEnrollists
		,'devClassEnrollists'=>[
			'descriptions' => 'Class Enrollments'							
			,'addEditModal'=>[
				'dummy'=>[]
				,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId','testTime']
				//,'hidden'=>['createdDate','createdBy']
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
			,'searchAttributes'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
							'display'=>[													
									"devClassEnrollistsView.locationCode;;Location Code"													
									,'devClassEnrollistsView.employeeFullName;;Employee Name'
									,'devClassEnrollistsView.classDescriptions;;Class Descriptions'													
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
											//,'devClassEnrollistsView.testDate;;ทดสอบวัน'
											//,'devClassEnrollistsView.testDateTime;;ทดสอบวัน.เวลา'
											//,'devClassEnrollistsView.testTime;;ทดสอบเวลา'
										]
									,'format'=>[
										//'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
										'devClassEnrollistsView.testDate'=>"replace(CONVERT(varchar(max),".FRPLCEMNT4FMT.",103),'-','/') testDate"
										,'devClassEnrollistsView.testDateTime'=>"CONVERT(varchar(10),".FRPLCEMNT4FMT.",103)+STUFF(RIGHT(' ' + CONVERT(VarChar(7),cast(".FRPLCEMNT4FMT." as time), 0), 7), 6, 0, ' ') testDateTime"
										,'devClassEnrollistsView.testTime'=>"STUFF(RIGHT(' ' + CONVERT(VarChar(7),".FRPLCEMNT4FMT.", 0), 7), 6, 0, ' ') testTime"
									]													
									,'editableInSubEntity'=>[ //editable is information that tell formResponse to create input in datatable
											'refused'=>'l::refusedId' //for create select2 and link to sysRefuses, l:refusedId measn link and use field refusedId in addEditModal=>references
											,'acknowledged'=>'l::acknowledgedId'
											,'comments'=>'comments' //edit by input text
											,'testDate'=>'testDate'
											,'testDateTime'=>'testDateTime'
											,'testTime'=>'testTime'
											,'_validationInfo_'=>[ 
													//tell backend which field to be validate with main entity,if not specfitied means use that key
													'refused'=>'refusedId' 
													,'acknowledged'=>'acknowledgedId' //for create select2 and link to sysAcknowledges
													//,'comments'=>'comments' //edit by input text
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
								//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
								//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
								//,'hidden'=>['createdDate','createdBy']
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
											'subDistrictId'=>'devSubDistrictsView.fullEnName'											
											]
							]
							,'searchAttributes'=>[
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
							,'searchAttributes'=>[
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
							,'searchAttributes'=>[
												'display'=>[
													'devExpenseTypes.name::devClassBudgets.expenseId'
													,'devClasses.descriptions::devClassBudgets.classId;;Class Descriptions'
													//,'devClasses.startDate::devClassBudgets.classId;;วดป.ที่เรียน'
													//,'devClassBudgets.amount'
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
							,'searchAttributes'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
											'display'=>[ 													
													//"devClassEnrollistsView.locationDescriptions;;สถานที่อบรม" 
													"devClassInstructorsView.locationCode;;Class Location Code" 
													//,'devSubjects.name::devSubjectCourse.subjectId::devClasses.scId::classId;;ชื่อวิชา'
													//,'devClasses.startDate::classId'
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
											'classId'=>'คำอธิบายเพิ่มเติมของการอบรม/สัมนา'							
											,'extInstructorId'=>'ชื่อ ผู้สอนจากภายนอก'
											]
							]
							,'searchAttributes'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
											'display'=>[ 													
													//"devClassEnrollistsView.locationDescriptions;;สถานที่อบรม" 
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
							,'searchAttributes'=>[
											'display'=>[
													"devLocations.code" 
													,"devLocations.descriptions" //ต้องระบุ entityName ด้วย
													,'devLocations.numberOfSeat'
													,"sysClosed.descriptions::closedId;;เลือกสถานะสถานที่"	//;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง (จะไม่เอา descriptions ใน column นั้นมาใช้)
													]
											,'between'=>['devLocations.numberOfSeat']
											
							]
							,'selectAttributes'=>[
													'fields'=>[
															'devLocations.code'
															,'devLocations.descriptions;;คำอธิบายเพิ่มเติม'
															,'sysYesNo.yesno;;สถานที่ของบริษัท'
															,'devLocations.numberOfSeat'
															,'devLocations.numberOfComputers'
															,'devLocations.projector;;โปรเจคเตอร์(เครื่อง)'
															,'devLocations.microphone;;ไมค์(ตัว)'
															,'sysClosed.descriptions;;เปิดใช้อยู่'									
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
								,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_user_func_getSession::IDNo"] //default คือค่าที่จะ insert หากไม่ระบุไป จะเอา default ใน ฐานข้อมูล _user_func_getSession คือฟังก์ชั่นใน mainForms, sql คือ function ใน sql
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
											'closedId'=>'sysClosed.descriptions'
											//,'preSubjectId'=>'devSubjectsView.codeAndName'
											//,'preCourseId'=>'devCourses.name'
											]
							]
							,'searchAttributes'=>[
											'display'=>[
													"devCourses.code" 
													,"devCourses.name" 
													//,"devCourses.objectives" //ต้องระบุ entityName ด้วย													
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
							,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_user_func_getSession::IDNo"] //default คือค่าที่จะ insert หากไม่ระบุไป จะเอา default ใน ฐานข้อมูล _user_func_getSession คือฟังก์ชั่นใน mainForms, sql คือ function ใน sql
							,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
										'closedId'=>'sysClosed.descriptions'
										,'preSubjectId'=>'devSubjectsView.codeAndName'
										,'preCourseId'=>'devCourses.name'
										]
						]
						,'searchAttributes'=>[
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
							,'searchAttributes'=>[
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
	
	public static function infos(){
		$allInfo = self::infos;
		$allInfo['footer_JS_CSS'] = self::default_footer_JS_CSS;
		$allInfo['header_JS_CSS'] = self::default_header_JS_CSS;
		return $allInfo;
	}
	public static function getDescriptions($entityName){		
		return self::infos[$entityName];		
	}
	public static function getAllDescriptions(){
		return self::infos;
	}
	public static function getEntityName($taskId){
		$CI =& get_instance();
		$CI->load->database();
		$q = $CI->db->query("select taskName from {$CI->db->dbprefix}gntTasks where id=".$CI->db->escape($taskId).";");
		$row = $q->row();
		if(isset($row)){
			return $row->taskName;
		}else{
			return '404';
		}
	}
}