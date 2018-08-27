<?php
class extraEntityInfos {
	const default_header_JS_CSS=[		
		'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
	];
	const default_footer_JS_CSS=[
		'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
	];
			
	const infos = 
	[
		'devExtInstructors'=>[
							'descriptions' => 'วิทยากรภายนอก'
							,'defaultOrientation'=>'list'
							,'parent'=>[]
							,'children'=>[]
							,'addEditModal'=>[
								'dummy'=>[]
								//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
								//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
								//,'hidden'=>['createdDate','createdBy']
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
											'subDistrictId'=>'devSubDistrictsView.fullName'											
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
														,'devSubDistrictsView.fullName;;ตำบล/อำเภอ/จังหวัด' //incase of use derived data from other table, descriptions must be specified
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
							
						]
		,'devExpenseTypes'=>[
							'descriptions' => 'ประเภทค่าใช้จ่าย'
							,'defaultOrientation'=>'list'
							,'parent'=>[]
							,'children'=>[]
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
													,'sysClosed.descriptions::devExpenseTypes.closedId;;เลือกสถานะ'
												]
												,'hidden'=>[]
											]
							,'selectAttributes'=>[
												'fields'=>[
														 'devExpenseTypes.name'
														 ,'devExpenseTypes.accountCode;;รหัสในผังบัญชี' //ถ้ามี ;; ติดมา แสดงว่าเอา description หลัง ;;
														 ,'sysClosed.descriptions;;สถานะ'
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
							
						]
		,'devClassBudgets'=>[
							'descriptions' => 'ค่าใช้จ่ายในการอบรมสัมนา'
							,'parent'=>[]
							,'children'=>[]
							,'addEditModal'=>[
								'dummy'=>[]
								//,'columnOrdering'=>['id','classId','expenseId','amount','comments']
								//,'hidden'=>['createdDate','createdBy']
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
											'classId'=>'devClasses.descriptions'											
											,'expenseId'=>'devExpenseTypes.name'											
											]
								,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
											'classId'=>'คำอธิบายเพิ่มเติมของการอบรม/สัมนา'
											,'expenseId'=>'ชนิดค่าใช้จ่าย']
							]
							,'searchAttributes'=>[
												'display'=>[
													'devExpenseTypes.name::devClassBudgets.expenseId'
													,'devClasses.descriptions::devClassBudgets.classId;;รายวิชา'
													//,'devClasses.startDate::devClassBudgets.classId;;วดป.ที่เรียน'
													//,'devClassBudgets.amount'
												]
											,'hidden'=>[]
											]
							,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น
												'fields'=>[ //มีฟิลด์อะไรบ้าง
														 'devClasses.descriptions;;คำอธิบายการอบรม'														 
														,'devLocations.code;;รหัสสถานที่'
														//,'devLocations.descriptions;;สถานที่อบรม'
														,'devClasses.startDate;;วดป.ที่เริ่มเรียน'														
														,'devExpenseTypes.name;;ชื่อค่าใช้จ่าย'
														,'devClassBudgets.amount'
														,'devClassBudgets.comments;;คำอธิบายเพิ่มเติม'
													]
												,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
													'devClasses.startDate'=>"CONVERT(varchar(max),__#@!!@#__,103) startDate"
													,'devClasses.descriptions'=>'__#@!!@#__ classDescription'													
													,'devExpenseTypes.name'=>'__#@!!@#__ expenseTypeName'
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
		,'devClasses'=>[
							'descriptions' => 'รายการอบรม/สัมนา'
							,'defaultOrientation'=>'list'
							//,'parent'=>['devSubjectCourse.scId','devClassStatuses.statusId']
							//,'children'=>[]
							,'addEditModal'=>[ //ข้อมูลประกอบของ addEditModal 
								'dummy'=>[]
								//'columnOrdering'=>['id','code','name','classDuration','shopDuration','preCourseId','preSubjectId','closedId','createdDate','createdBy']
								//,'columnWidth'=>['id'=>2,'code'=>2,'name'=>8,'preSubjectId'=>6,'preCourseId'=>6,'shopDuration'=>3,'classDuration'=>3]
								,'hidden'=>['createdDate','createdBy']
								,'default'=>['createdDate'=>'sql::getdate()','createdBy'=>"_user_func_getSession::IDNo"] //default คือค่าที่จะ insert หากไม่ระบุไป จะเอา default ใน ฐานข้อมูล _user_func_getSession คือฟังก์ชั่นใน
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 
											'statusId'=>'devClassStatuses.descriptions'
											,'scId'=>'devSubjectCourseView.courseAndSubject'
											,'locationId'=>'devLocations.descriptions'
											]
								,'fieldLabels'=>['scId'=>'รหัสหลักสูตร(ชื่อวิชา)']
								,'format'=>['startDate'=>"replace(CONVERT(varchar(max),__#@!!@#__,103),'-','/') startDate"] //format ที่จะใช้ดึงออกมาแสดงในหน้า edit 
								,'subModal'=>[
											'devClassEnrollists' =>[
													'label'=>'ผู้เข้าอบรม' //หากระบุ label จะเอา label นี้ไปใช้ หากไม่ระบุ จะไป เอา descriptions ของ entity
													,'alterView'=>'devClassEnrollistsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
													,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
													,'editable'=>True// มีปุ่ม แก้ไข/เพิ่ม/ลบ ข้อมูลได้หรือไม่
													,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
											]
											,'devClassInstructors' =>[
													'label'=>'ผู้สอน'
													,'alterView'=>'devClassInstructors.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
													,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
													,'editable'=>True// มีปุ่ม แก้ไข/เพิ่ม/ลบ ข้อมูลได้หรือไม่
													,'suppressedFieldsInAdd'=>['id','classId']  //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
												]
											,'devClassExtInstructors' =>[
													'label'=>'ผู้สอนจากภายนอก' 
													,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
													,'alterView'=>'devClassExtInstructorsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
													,'editable'=>true// มีปุ่ม แก้ไข/เพิ่ม/ลบ ข้อมูลได้หรือไม่
													,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
												]
											,'devClassBudgets' =>[
													'label'=>'ค่าใช้จ่ายในการอบรมสัมนา' 
													,'suppressedFields'=>['classId','startDate','classDescription'] 
													/**suppressedFields คือ ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก ใน selectAttributes 
														ดังนั้นชื่อฟิลด์ ใน suppressedFields จะต้องเป็น subset ของฟิลด์ใน selectAttributes
													*/
													,'alterView'=>'devClassBudgetsView.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
													,'editable'=>true// มีปุ่ม แก้ไข/เพิ่ม/ลบ ข้อมูลได้หรือไม่
													,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
											]
												
										]
							]
							,'searchAttributes'=>[ //ฟิลด์ที่จะใช้เป็นเงื่อนไขในการ search ตรง filter row
												'display'=>[ //ตัวที่จะแสดงออกมาให้เลือก
													"devClasses.startDate" //หากเป็น date สร้างสองอัน เพื่อ between 
													//,"devCourses.name::devSubjectCourse.courseId::devClasses.scId" //ต้องระบุ entityName ด้วย
													,"devSubjects.name::devSubjectCourse.subjectId::devClasses.scId"										
													,"devLocations.descriptions::devClasses.locationId;;เลือกสถานที่"
													//;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง (จะไม่เอา descriptions ใน column นั้นมาใช้)
													,"devClasses.descriptions"
													,"devClassStatuses.descriptions::devClasses.statusId;;สถานะของการอบรมสัมนา" //;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง
													
												]
												,'hidden'=>[ //เงื่อนไขที่จะใช้ร่วมในการค้นด้วย แต่ไม่แสดงออกมา
													"devClasses.createdDate > dateadd(year,-1,getdate())"
													,"devClasses.descriptions not like '%test%' "
												]
											]
							,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น
												'fields'=>[ //มีฟิลด์อะไรบ้าง
														 'devCourses.name'
														 ,'devSubjects.name'
														,'devLocations.code;;รหัสสถานที่'
														,'devLocations.descriptions;;สถานที่อบรม'
														,'devClasses.startDate;;วดป.ที่เริ่มเรียน'
														,'devClasses.descriptions;;คำอธิบายการอบรม'
														,'devClassStatuses.descriptions;;สถานะ'
													]
												,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
													'devClasses.startDate'=>"CONVERT(varchar(max),__#@!!@#__,103) startDate"
													//'devClasses.startDate'=>"CONVERT(varchar(max),__#@!!@#__,108) startDate"
													,'devCourses.name'=>"__#@!!@#__ courseName"
													,'devSubjects.name'=>"__#@!!@#__ subjectName"
													,'devClasses.descriptions'=>"__#@!!@#__ classDescription"
													,'devLocations.descriptions'=>"__#@!!@#__ locationDescription"
													,'devClassStatuses.descriptions'=>"__#@!!@#__ statusDescription"
													]
											]
							,'join'=>[
									['left','devSubjectCourse','on'=>[
																[
																	['devClasses.scId','=','devSubjectCourse.id'] //ในกลุ่มนี้ หากมากกว่า 1 หมายถึง and กัน
																]//ถ้า on มีหลาย array หมายถึแต่ละ array or กัน
															]
									] 
									,['left','devCourses','on'=>[[['devSubjectCourse.courseId','=','devCourses.id']]]]
									,['left','devSubjects','on'=>[[['devSubjectCourse.subjectId','=','devSubjects.id']]]]
									,['left','devLocations','on'=>[[['devClasses.locationId','=','devLocations.id']]]]
									,['left','devClassStatuses','on'=>[
																 [
																	['devClasses.statusId','=','devClassStatuses.id']																
																]																
															]
									]
							]
							,'template'=>'projects.html'
							,'header_JS_CSS'=>[
								'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
							]
							,'footer_JS_CSS'=>[
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
						]
		,'devClassEnrollists'=>[
							'descriptions' => 'ผู้เข้าอบรมในแต่ละการอบรม/สัมนา'
							,'parent'=>[]
							,'children'=>[]
							,'addEditModal'=>[
								'dummy'=>[]
								,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId','testTime']
								//,'hidden'=>['createdDate','createdBy']
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
											'classId'=>'devClasses.descriptions'
											,'employeeId'=>'devEmployees.IDNoAndFullName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
											,'acknowledgedId'=>'sysAcknowledges.descriptions'
											,'refusedId'=>'sysRefuses.descriptions'							
											]
								,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
											'classId'=>'คำอธิบายเพิ่มเติมของการอบรม/สัมนา'
											,'employeeId'=>'(เลขประจำตัวปปช.)ชื่อ สกุล พนักงาน']
							]
							,'searchAttributes'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
											'display'=>[
													//"devClassEnrollistsView.locationDescriptions;;สถานที่อบรม" 
													"devClassEnrollistsView.locationCode;;รหัสสถานที่อบรม"
													//,'devSubjects.name::devSubjectCourse.subjectId::devClasses.scId::classId;;ชื่อวิชา'
													//,'devClasses.startDate::classId'
													,'devClassEnrollistsView.employeeFullName;;ชื่อสกุลพนักงาน'
													,'devClassEnrollistsView.classDescriptions;;คำอธิบายการอบรม'
													//,'devEmployees.officeName;;ฝ่ายของพนักงาน'
													//,'devClassEnrollistsView.id'
													]
											,'between'=>[]
							]
							,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
													'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
															'devClassEnrollistsView.employeeFullName;;ชื่อพนักงาน'
															,'devClassEnrollistsView.positionName;;ตำแหน่งหลัก'
															,'devClassEnrollistsView.classDescriptions;;คำอธิบายการอบรม'
															,"devClassEnrollistsView.locationCode;;รหัสสถานที่อบรม" //ต้องระบุ entityName ด้วย
															,'devClassEnrollistsView.classStartDate;;วดป.เริ่มเรียน'
															,'devClassEnrollistsView.refused;;ปฏิเสธ'
															,'devClassEnrollistsView.acknowledged;;รับทราบ'
															,'devClassEnrollistsView.comments;;หมายเหตุ'
															,'devClassEnrollistsView.testDate;;ทดสอบวัน'
															,'devClassEnrollistsView.testDateTime;;ทดสอบวัน.เวลา'
															,'devClassEnrollistsView.testTime;;ทดสอบเวลา'
														]
													,'format'=>[
														//'devClasses.descriptions'=>"__#@!!@#__ classDescription"
														'devClassEnrollistsView.testDate'=>"replace(CONVERT(varchar(max),__#@!!@#__,103),'-','/') testDate"
														,'devClassEnrollistsView.testDateTime'=>"CONVERT(varchar(10),__#@!!@#__,103)+STUFF(RIGHT(' ' + CONVERT(VarChar(7),cast(__#@!!@#__ as time), 0), 7), 6, 0, ' ') testDateTime"
														,'devClassEnrollistsView.testTime'=>"STUFF(RIGHT(' ' + CONVERT(VarChar(7),__#@!!@#__, 0), 7), 6, 0, ' ') testTime"
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
		]
		,'devClassInstructors'=>[
							'descriptions' => 'ผู้สอนในแต่ละการอบรม/สัมนา'
							,'parent'=>[]
							,'children'=>[]
							,'addEditModal'=>[
								'dummy'=>[]
								//,'columnOrdering'=>['id','classId','employeeId','acknowledgedId','refusedId']
								//,'hidden'=>['createdDate','createdBy']
								,'references'=>[ //references คือ table ที่จะเอาไว้ select2 ในฟิลเตอร์ rows
											'classId'=>'devClasses.descriptions'
											,'employeeId'=>'devEmployees.IDNoAndFullName' //ยังไม่เชื่อมให้ เพราะใน columlistInfo ไม่ได้บอกว่า มีการ references ไป table หลัก(แก้ไขแล้ว)
											]
								,'fieldLabels'=>[ //ฟิลด์ label ในหน้า Addedit Modal (หากไม่ระบุ จะไปเอา ใน description จากฐานข้อมูลแทน)
											'classId'=>'คำอธิบายเพิ่มเติมของการอบรม/สัมนา'							
											,'employeeId'=>'(เลขประจำตัวปปช.)ชื่อ สกุล พนักงาน'
											]
							]
							,'searchAttributes'=>[ //ฟิลด์ที่จะ ใช้ search ใน filter row ใช้ join ร่วมกับ selectAttributes ด้านล่าง ('join'=>)
											'display'=>[ 													
													//"devClassEnrollistsView.locationDescriptions;;สถานที่อบรม" 
													"devClassInstructorsView.locationCode;;รหัสสถานที่อบรม" 
													//,'devSubjects.name::devSubjectCourse.subjectId::devClasses.scId::classId;;ชื่อวิชา'
													//,'devClasses.startDate::classId'
													,'devClassInstructorsView.employeeFullName;;ชื่อสกุลพนักงาน'
													,'devClassInstructorsView.classDescriptions;;คำอธิบายการอบรม'
													//,'devEmployees.officeName;;ฝ่ายของพนักงาน'													
													]
											,'between'=>[]
							]
							,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
											'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
													'devClassInstructorsView.employeeFullName;;ชื่อพนักงาน'
													,'devClassInstructorsView.positionName;;ตำแหน่งหลัก'													
													,'devClassInstructorsView.classDescriptions;;คำอธิบายการอบรม'
													,"devClassInstructorsView.locationCode;;รหัสสถานที่อบรม" //ต้องระบุ entityName ด้วย
													,'devClassInstructorsView.classStartDate;;วดป.เริ่มเรียน'
													,'devClassInstructorsView.percentLoad'
													,'devClassInstructorsView.comments;;หมายเหตุ'
													]
											,'format'=>[
												//'devClasses.descriptions'=>"__#@!!@#__ classDescription"
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
						]
		,'devClassExtInstructors'=>[
						'descriptions' => 'วิทยากรภายนอกของการอบรม'
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
													"devClassExtInstructorsView.locationCode;;รหัสสถานที่อบรม" 													
													,'devClassExtInstructorsView.subjectName;;วิชาที่สอน'
													,'devClassExtInstructorsView.fullName;;ชื่อสกุล'
													,'devClassExtInstructorsView.classDescriptions;;คำอธิบายการอบรม'
													//,'devEmployees.officeName;;ฝ่ายของพนักงาน'													
													]
											,'between'=>[]
							]
							,'selectAttributes'=>[ //ฟิลด์ที่จะแสดงออกมาในผลการ search
													'fields'=>[ //ไม่ต้องใส่ว่าฟิลด์เชื่อมกันยังไง เพราะอยู่ใน join อยู่แล้ว
															'devClassExtInstructorsView.fullName;;ชื่อสกุล'
															,'devClassExtInstructorsView.percentLoad;;เปอร์เซ็นต์โหลด'
															,'devClassExtInstructorsView.classDescriptions;;คำอธิบายการอบรม'
															,"devClassExtInstructorsView.locationCode;;รหัสสถานที่อบรม" //ต้องระบุ entityName ด้วย
															,"devClassExtInstructorsView.phoneNumber;;เบอร์โทร" 
															,"devClassExtInstructorsView.emailAddress;;e-mail" 
															]
													,'format'=>[
														//'devClasses.descriptions'=>"__#@!!@#__ classDescription"
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
		,'devLocations'=>[
							'descriptions' => 'สถานที่ฝึกอบรม'							
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
														'sysClosed.descriptions'=>"__#@!!@#__ closedDescription"
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
		]
		,'devCourses'=>[
							'descriptions' => 'หลักสูตร'
							,'parent'=>[]
							,'children'=>[]
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
													,"sysClosed.descriptions::closedId;;เลือกสถานะของหลักสูตร"	//;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง (จะไม่เอา descriptions ใน column นั้นมาใช้)
													]
											,'between'=>[]
											
							]
							,'selectAttributes'=>[ //คือ select ใน filter row
												'fields'=>[
														'devCourses.code'
														,'devCourses.name'
														,'devCourses.objectives'
														,'sysClosed.descriptions;;สถานะ'														
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
								'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js'
							]
						]
						
		,'devSubjects'=>[	'descriptions' => 'รายวิชา'
						,'defaultOrientation'=>'list'
						//,'parent'=>['preSubjectId','closedId']
						,'children'=>[]
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
												,"sysClosed.descriptions::devSubjects.closedId;;เลือกสถานะ" 													
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
													,'sysClosed.descriptions;;สถานะ'
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
						,'footer_JS_CSS'=>['assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','assets/js/defaultForEntity.js']
					]
		
		,'devSubjectCourse'=>[
							'descriptions' => 'รายวิชาในหลักสูตร'
							,'parent'=>[]
							,'children'=>[]
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
												'devSubjects.name'=>"__#@!!@#__ subjectName"
												,'devCourses.name'=>"__#@!!@#__ courseName"
											]												
										]
							,'join'=>[
								['left','devSubjects','on'=>[[['devSubjectCourse.subjectId','=','devSubjects.id']]]]
								,['left','devCourses','on'=>[[['devSubjectCourse.courseId','=','devCourses.id'] ]]]
							]
						]		
	];
	
	public static function infos()
	{
		$allInfo = self::infos;
		$allInfo['footer_JS_CSS'] = self::default_footer_JS_CSS;
		$allInfo['header_JS_CSS'] = self::default_header_JS_CSS;
		return $allInfo;
	}
	public static function getDescriptions($entityName)
	{		
		return self::infos[$entityName];		
	}
	public static function getAllDescriptions()
	{
		return self::infos;
	}
	public static function getEntityName($taskId)
	{
		$CI =& get_instance();
		$CI->load->database();
		$q = $CI->db->query("select taskName from {$CI->db->dbprefix}gntTasks where id=".$CI->db->escape($taskId).";");
		$row = $q->row();
		if(isset($row))
		{
			return $row->taskName;
		}
		else
		{
			return '404';
		}
	}
}