<?php
$recipes = 
[
'devClassEnrollists'=>[
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
];