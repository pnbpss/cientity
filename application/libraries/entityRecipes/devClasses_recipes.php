<?php
/**
*  For none-Thai, you can ignore Thai comment in this file. It provided for easier Thai reader to understand the code. The contexts are same as described English. 
*/
$recipes = [
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
			 * The 'customized' key value is true this means this entity will be customized by developer, please see 
			 * 'repExpenseReports' below as example. If the entity is customized this mean you have to write a code 
			 *  of filter-row and search mechanism by yourself. The default value of of this key is false (not defined = false).
			 */
			,'customized'=>false

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
				 * Please see "How to design database to suits CI-Entity" in CI-Entity Document.
				 */
				,'columnWidth'=>['descriptions'=>12]

				/**
				 * The 'hidden' keys contain column name that will not be display as input in addEditModal.
				 * if not specified or not presented or is empty array:
				 * - it must be specified if the entity got any column that will not let user enter, won't not create input for user. 
				 *   for example datetime of insert time.	 
				 * Critical:no.
				 * Effected:SQL string for insert, addEditModal.
				 */
				,'hidden'=>['createdDate','createdBy']

				/**
				 * The 'default' key tells CI-Entity how to get default values and how to construct SQL string of that column, for insert only.
				 *  There are  two types of default value: SQL and user function. The keyword 'sql' means use T-SQL function or expression
				 *  followed by notation "::". Otherwise, it will use the method [methodName], for example _getUserSessionValue is method name. 
				 * The key word followed by string "::" is parameter of the method.
				 * The following tells CI-Entity that default value of column createDate is T-SQL function getdate(), and the default
				 * value of column createdBy must be gotten from function _getUserSessionValue('userName')
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
				,'fieldLabels'=>['scId'=>"Course Code and Subject's Name",'capacity'=>'class capacity']

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
				 * The 'disabled' key values tell CI-Entity to disabled the input in addEditModal.
				 * For example, if capacity and locationId input needed to be disabled, then use the follwing syntax:
				 * ,'disabled'=>['capacity','locationId']
				 */
				,'disabled'=>[]
				
				/**
				 * The 'subEntity' key tells CI-Entity to put sub-entity add/edit section under main-entity add/edit form in addEditModal. 
				 * For instance, in class add/edit section in main-entity, you want to put class enrollment and class instructor list as sub-entity,
				 * then 'subEntity' key will help you to make it happened.
				 * 
				 * The keys of 'subEntity' key is entity name that will be constructed as sub-entity. 
				 *  if not specified or not presented or is empty array:
				 *	The sub-entity won't be construct.
				 * Critical:no
				 */
				,'subEntity'=>[

					/**
					 * The 'devClassEnrollists' key values are the names of entity will be act as sub-entity in add/edit modal of main-entity. 
					 * The name of sub-entity, for example the first sub-entity('devClassEnrollists') as following, must be also declared as main-entity.
					 */
					'devClassEnrollists' =>[

						/**
						 * The 'label' key value is label well be displayed on navigate bar (navbar) of each sub-entity. 
						 *  if not specified or not presented or is empty array:
						*	CI-Entity will use entity descriptions instead.
						 * Critical:no
						 */
						'label'=>'Enrollments' 

						/**
						 * The 'alterView' key value is table/view and column name where CI-Entity will be use to fetch data
						 * to display in sub-entity panel. Table/view name and column name are separated by dot(.).Column name is linked 
						 * column of sub-entity to table or view. You might be questioned that why don't we use table name directly?. 
						 * In some situation, you might want  to display more informations than using pure table of sub-entity.						 
						 * This demo version used [devClassEnrollistsView.classId] as 'alterView' because devClassEnrollists, table,
						 * does not contains some necessary informations, such as student name, class start date, etc.  
						 * if not specified or not presented or is empty array:
						*	CI-Entity will not be able to display sub-entity.
						 * Critical:yes
						 */
						,'alterView'=>'devClassEnrollistsView.classId' 

						/**
						 * The 'suppressedFields' key values are column name of main-entity's 'selectAttributes' that 
						 * won't be display, listed, in sub-entity. This is the section of 'devClassEnrollists' sub-entity, 
						 * the 'selectAttributes' key values of 'devClassEnrollists' will be subtracted if it exists in 'suppressedFields'.
						 * *** The 'suppressedFields' values must be subset of main-entity's selectAttributes.
						 * if not specified or not presented or is empty array:
						 *	The row of data-table of sub-entity might displayed some unnecessary informations.
						 * Critical:no, optional.
						 */
						,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] 

						/**
						 * The 'allowDelete' key value tells CI-Entity to display check box for select to delete on each row of sub-entity
						 * data table or not. True means display check box for select to delete, and false means check box won't be 
						 * displayed.
						 *  Critical:no optional.
						 */
						,'allowDelete'=>true // มีปุ่มให้เลือกลบทางขวาสุดหรือไม่

						/**
						 * The 'suppressedFieldsInAdd' keys are field name that suppressed in sub-entity add form. 
						 *  if not specified or not presented or is empty array:
						 *	Check box won't be displayed for select on right side of each row in sub-entity data table.
						 * The fields that will be suppressed must be:
						 * - Identity
						 * - Foreign key from main-entity
						 * - Nullable field that you think it is ignorable.
						 *  Critical:no, optional.
						 */
						,'suppressedFieldsInAdd'=>['id','classId'] //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
					]
					,'devClassInstructors' =>[
						'label'=>'Internal Instructors'
						,'alterView'=>'devClassInstructors.classId' //(จำเป็น) หลัง . คือเชื่อมกันด้วยฟิลด์ไหนกับ entity หลัก 
						,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] //ฟิลด์ที่ไม่ต้องแสดงออกมาใน subentity โดยไปลบออกจาก entity หลัก
						,'allowDelete'=>True // มีปุ่มให้เลือกลบทางขวาสุดหรือไม่
						,'suppressedFieldsInAdd'=>['id','classId']  //field ที่ไม่ต้องแสดงออกมาในส่วนของการ add ใน sub entity
						]
					,'devClassExtInstructors' =>[
						'label'=>'External Instructors' 
						,'suppressedFields'=>['classStartDate','classDescriptions','locationCode'] 
						,'alterView'=>'devClassExtInstructorsView.classId' 
						,'allowDelete'=>true 
						,'suppressedFieldsInAdd'=>['id','classId'] 
						]
					,'devClassBudgets' =>[
						'label'=>'Expenses' 
						,'suppressedFields'=>['classId','startDate','classDescription']
						,'alterView'=>'devClassBudgetsView.classId' 
						,'allowDelete'=>true 
						,'suppressedFieldsInAdd'=>['id','classId'] 
					]
				]
			]

			/**
			 * The 'filtersBar' key values are informations that CI-Entity uses to construct filter row and searching mechanism.
			 * if not specified or not presented or is empty array:
			 *	The filter row won't be constructed.
			 * Critical:yes
			 */
			,'filtersBar'=>[ 

				/**
				 * The 'display' key values is column of any table that will be used as search key, search conditions.
				 * it also contains of necessary informations such as label, when to use and how to construct input (text or select).
				 * The instructions of following using, in this demo version, will be described one by one.
				 * if not specified or not presented or is empty array:
				 *	Filter row will not be construct as desired.
				 * Critical:yes
				 */
				'display'=>[
					/**
					 * The 'devClasses.startDate' value tell CI-Entity that to create input tag,in filter row, for search using startDate column's data.
					 * In this case, input text will be created 
					 * For more informations 
					 * - If the field datatype is date or datetime, CI-Entity will attached DatePicker, JQuery plug-in, with it.
					 * - If the field datatype is date or datetime, CI-Entity will make this input twice which means user can search by from-to conditions.
					 */
					"devClasses.startDate" 

					/**
					 * The "devSubjects.name::;;Subject Name" represented:
					 * - Use data in [name] column in [devSubects] table as search condition
					 * - :: notation means the search component will constructed as select, CI-Entity will be attached select2 plug-in with this component.
					 * - ;; notation means CI-Entity will be use the string follow by ;; as alternate label. If ;; is not presented CI-Entity will use column descriptions as specified in database design instead.
					 */
					,"devSubjects.name::;;Subject Name"

					/**
					 * The "devLocations.descriptions::devClasses.locationId;;Class Location" values represent:
					 * - it works the same way as ,"devSubjects.name::;;Subject Name"
					 * - The keyword devClasses.locationId is specified for notify developer that how it linked between 
					 *   two table. It doesn't effected to filter-row construction anyway. So, you can shorten it like this
					 *   "devLocations.descriptions::;;Class Location"
					 */
					,"devLocations.descriptions::devClasses.locationId;;Class Location"
					,"devClasses.capacity;;Class Capacity"
					,"devClasses.descriptions;;Class Descriptions"
					,"devClassStatuses.descriptions::devClasses.statusId;;Class Status" //;; สิ่งที่อยู่หลัง ;; คือคำอธิบายที่กำหนดไปเอง
					]

				/**
				 * The 'between' key values are informations that tell CI-Entity to create input component twice. The 
				 * descriptions above, about "devClasses.startDate" value, tell that the field that have date or datetime 
				 * datatype will be created twice.  Yes, it is. The date or datetime will be automatically create input component
				 * twice. For other datatype you have to tell CI-Entity to make it twice to form from and to by specified the 
				 * table and column name in the key 'between' in the format as following. 
				 */
				,'between'=>['devClasses.capacity']  

				/**
				 * The 'hidden' key values are additional search conditions that will be added to SQL string to perform 
				 * search after user submit the search form. You can write T-SQL directly to value of this key. 
				 * The "and" clause is combination of each value. The following use will produces:
				 * " devClasses.createdDate > dateadd(year,-1,getdate()) and devClasses.descriptions not like '%test%' "
				 */
				,'hidden'=>[ //เงื่อนไขที่จะใช้ร่วมในการค้นด้วย แต่ไม่แสดงออกมา
					"devClasses.createdDate > dateadd(year,-1,getdate())"
					,"devClasses.descriptions not like '%test%' "
				]
			]

			/**
			 * The 'selectAttributes' key values are informations that CI-Entity uses to to construct search SQL for perform 
			 * search query after user submit filter-row search. It consists of 'fields' and 'format' values that are field to select 
			 * and format of those fields respectively.
			 * The 'selectAttributes' key values work cooperatively with 'join' key values, which will be described in 'join' section.
			 * if not specified or not presented or is empty array:
			 *	CI-Entity unable to performs search.
			 * Critical:yes
			 */
			,'selectAttributes'=>[ //ข้อมูลประกอบของการ select ออกมาเพื่อแสดง หลังจาก ค้น

				/**
				 * The 'fields' values are field name of table that you want to select from database. The fields may be came from 
				 * different table, the 'join' key values will be take care of this.
				 * The notation ;; is separator between field name and label of table(HTML) column header. If ;; is not specified CI-Entity use
				 * the description of column, from database design, instead.
				 */
				'fields'=>[ 
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
				 * The 'format' key values work closely with the 'fields' key values, which represented the format of each field.
				 * The 'format' key values must be specified if the following situation occurred:
				 *	- There are duplicated column name from multiple table. As following using, two tables 
				 *	('devCourses.name' and 'devSubjects.name') have same column name. This must be confused with SQL query result.
				 *	We have to alternate column name by adding 'format' element of these fields to distinguish them.
				 *	- Use field that datatype is date or datetime or time. The database engine yields query result in different 
				 *	way upto localization of server, this cause not easy to handle the query result. 
				 *	- You want to customize format of query result your way. 
				 * 
				 *  What is FRPLCEMNT4FMT constant? 
				 *  FRPLCEMNT4FMT constant is string that will be replaced by exact table name. The table name in this file
				 * , extraEntityInfo.php, is not exact table name that will be used for SQL String construction. 
				 *  The table names ,in this file must be prefixed with DBPREFIX constant by CI-ENtity. The DBPREFIX constant must be given
				 *  in configs.php. Using FRPLCEMNT4FMT constant is flexibility to allow developer to change the DBPREFIX.
				 */
				,'format'=>[ //รูปแบบที่จะแสดงออกมาหลังจากคลิกปุ่มค้น
					'devClasses.startDate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103) startDate"
					,'devCourses.name'=>"".FRPLCEMNT4FMT." courseName"
					,'devSubjects.name'=>"".FRPLCEMNT4FMT." subjectName"
					,'devClasses.descriptions'=>"".FRPLCEMNT4FMT." classDescription"
					,'devLocations.descriptions'=>"".FRPLCEMNT4FMT." locationDescription"
					,'devClassStatuses.descriptions'=>"".FRPLCEMNT4FMT." statusDescription"
					]

				/**
				 * The 'editableInSubEntity' key values will be used when this entity acts  as sub-entity of other entity.
				 * The 'editableInSubEntity' value is empty, as shown below, because 'devClasses' is not sub-entity to any other entity.
				 * 
				 * The complete explanation about 'editableInSubEntity' key is described in 'devClassEnrollist' section.
				 * (press control+f to search word "### complete explanation about 'editableInSubEntity' key ###" ).
				 */
				,'editableInSubEntity'=>[]
			]

			/**
			 * The 'join' key values are information that CI-Entity applies to construct join clause for select query, the query after user perform search in filter row.
			 * The each value represented joining between main table (main-entity) and other table.
			 * The meaning of first key of 'join':
			 *  - 'left' is type of joining
			 *  - 'devSubjectCourse' is name of table which will be joined with
			 *  - 'on' is joining conditions. It categorized in to and-scope and or-scope.
			 * Critical:yes, if select data from multiple table.
			 * The given 'join' key values as following will be leaded to construct join clause as:
			 * Suppose the comment(#) is removed, and third, fourth, fifth join table is not given.
			 * "
			 * from devClasses 
			 * left join devSubjectCourse on 
			 *					(
			 *						(devClasses.scId = devSubjectCourse.id) 
			 *						and 
			 *						(tableA.column1=tableB.column1)
			 *					)
			 *					or
			 *					(
			 *						(tableB.column1=tableC.column1) 
			 *						and
			 *						(tableD.column1>tableE.column1) 
			 *					)
			 * left join devCourses on 
			 *					(
			 *						(devSubjectCourse.subjectId=devSubjects.id)
			 *					)
			 * "
			 */
			,'join'=>[
					[
						'left' //type of join such as left, inner, right, full.
						,'devSubjectCourse' //table name that main-entity will join with.
						,'on'=> //contains of joining conditions
							[ 
								[
									['devClasses.scId','=','devSubjectCourse.id'] //and scope
							#		,['tableA.column1','=','tableB.column1'] // and scope
								]//or scope
							#	,[
							#		,['tableB.column1','=','tableC.column1'] // and scope
							#		,['tableD.column1','>','tableE.column1'] // and scope
							#	]// or scope
							]
					] // first table to join	

					,['left','devCourses','on'=>[[['devSubjectCourse.courseId','=','devCourses.id']]]] //second table to join with 'devClasses'
					,['left','devSubjects','on'=>[[['devSubjectCourse.subjectId','=','devSubjects.id']]]] //third table to join with 'devClasses'
					,['left','devLocations','on'=>[[['devClasses.locationId','=','devLocations.id']]]] //fourth table to join with 'devClasses'
					,['left','devClassStatuses','on'=>[[['devClasses.statusId','=','devClassStatuses.id']]]] //fifth table to join with 'devClasses'
			]

			/**
			 * 'header_JS_CSS' is header JS file or CSS file that have to be linked in main_view.php or entity_view.php. This informations will be placed in header tag of html
			 */
			,'header_JS_CSS'=>[
				'assets/css/bootstrap.min.css','assets/css/dataTables.bootstrap.min.css','assets/css/font-awesome.min.css','assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/plugins/summernote/dist/summernote.css','assets/css/style.css'
			]

			/**
			 * 'footer_JS_CSS' is link information of JS file or CSS file that have to be linked in main_view.php or entity_view.php. This informations will placed in bottom of body tag.
			 */
			,'footer_JS_CSS'=>[
				'assets/js/jquery-3.2.1.min.js','assets/js/bootstrap.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap.min.js','assets/js/jquery.slimscroll.js','assets/plugins/morris/morris.min.js','assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/app.js','assets/plugins/summernote/dist/summernote.min.js','js/defaultForEntity.js'
			]
		]
#endregion devClasses
];

