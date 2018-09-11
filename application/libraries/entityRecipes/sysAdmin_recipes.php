<?php
$recipes = [
	#region System Administration
			'sysUsers'=>[
				'descriptions' => 'Users'
				,'filtersBar'=>[
					'display'=>["sysUsers.userName;;User Name"]
					,'hidden'=>[] 
					]
					//,'between'=>['devSubjects.classDuration','devSubjects.shopDuration']

				,'selectAttributes'=>[
					'fields'=>[
							'sysUsers.userName'
							,'devEmployeesView.FLName;;Employee Name'
							,'sysUserGroups.name;;group name'
							,'sysUsers.updateBy;;Last update by'
							,'sysUsers.lastUpdate;;last update'
						]
					,'format'=>[
						'sysUsers.lastUpdate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103)+' '+CONVERT(varchar(max),cast(".FRPLCEMNT4FMT." as time),100) lastUpdate"						
						]												
				]
				,'join'=>[
						['left','sysUserGroups','on'=>[[['sysUsers.groupId','=','sysUserGroups.id']]]]
						,['left','devEmployeesView','on'=>[[['sysUsers.userName','=','devEmployeesView.employeeCode']]]]
					]
				,'addEditModal'=>[
					'dummy'=>[]				
					,'references'=>['groupId'=>'sysUserGroups.name']
					,'fieldLabels'=>['lastUpdate'=>"Last Update"]
					//,'format'=>['lastUpdate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103)+' '+CONVERT(varchar(max),cast(".FRPLCEMNT4FMT." as time),100) lastUpdate"]
					,'hidden'=>['updateBy','lastUpdate']
					,'default'=>['updateBy'=>'_getUserSessionValue::userName','lastUpdate'=>'sql::getdate()']
					,'format'=>['lastUpdate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103)+' '+CONVERT(varchar(max),cast(".FRPLCEMNT4FMT." as time),100) lastUpdate"]
				]			
			]
			,'sysUserGroups'=>[
				'descriptions' => 'User Groups'
				,'filtersBar'=>['display'=>["sysUserGroups.name;;User Group Name"]]
				,'selectAttributes'=>[
					'fields'=>[						
							'sysUserGroups.name;;Group Name'
							,'sysUserGroups.descriptions;;Descriptions'
							,'sysYesNo.yesno;;Available?'
						]				
				]
				,'join'=>[['left','sysYesNo','on'=>[[['sysUserGroups.statusId','=','sysYesNo.id']]]]]
				,'addEditModal'=>[				
					'references'=>['statusId'=>'sysYesNo.yesno']	
					,'fieldLabels'=>['name'=>"Group Name"]
				]
			]
			,'sysUserTaskPrivileges'=>[ //devSubjects.name::;;Subject Name
				'descriptions' => "User Group's Priviledges"
				,'filtersBar'=>['display'=>["sysUserGroups.name::;;User Group Name",'sysTasks.taskName::;;Task Name']]
				,'selectAttributes'=>[
					'fields'=>[		
							'sysUserGroups.name;;User Group Name'
							,'sysUserGroups.descriptions;;User Group Descriptions'
							,'sysTasks.taskName;;Task Name'
							,'sysTaskGroups.groupName;;Task Group Name'
							,'sysUserTaskPrivileges.allowSave;;Allow U/I/D'
							,'sysTasks.ordering;;Task Ordering in Menu'
						]
					,'format'=>['sysTasks.ordering'=>"convert(varchar(max),".FRPLCEMNT4FMT.")+'&nbsp;' ordering"]
				]
				,'join'=>[
					['left','sysUserGroups','on'=>[[['sysUserTaskPrivileges.userGroupId','=','sysUserGroups.id']]]]
					,['left','sysTasks','on'=>[[['sysUserTaskPrivileges.taskId','=','sysTasks.id']]]]
					,['left','sysTaskGroups','on'=>[[['sysTasks.taskGroupId','=','sysTaskGroups.id']]]]
				]
				,'addEditModal'=>[				
					'references'=>['userGroupId'=>'sysUserGroups.name','taskId'=>'sysTasks.taskName']	
					,'fieldLabels'=>['userGroupId'=>"User Group",'taskId'=>'Task Name','allowSave'=>'Allow Edit/Delete/Insert']
				]
			]
			,'sysTaskGroups'=>[
				'descriptions' => 'Task Groups'
				,'filtersBar'=>['display'=>['sysTaskGroups.groupName;;Task Group Name']]
				,'selectAttributes'=>[
					'fields'=>[
							'sysTaskGroups.groupName;;Task Group Name'
							,'sysTaskGroups.ordering;;Ordering in Menu'						
						]
					,'format'=>['sysTaskGroups.ordering'=>"convert(varchar(max),ordering,".FRPLCEMNT4FMT.")+'&nbsp;&nbsp;' ordering"]
				]
				,'addEditModal'=>[					
					'fieldLabels'=>['groupName'=>"Task's Group Name",'ordering'=>'Ordering in Menus']
				]
			]
			,'sysTasks'=>[
				'descriptions' => 'Tasks'
				,'filtersBar'=>['display'=>['sysTasks.taskName;;Task Name','sysTaskGroups.groupName;;Task Group Name']]
				,'selectAttributes'=>[
					'fields'=>[
							'sysTasks.taskName;;Task Name(descriptions)'
							,'sysTaskGroups.groupName;;Task Group Name'						
							,'sysTasks.ordering;;Ordering'
							,'sysYesNo.yesno;;Display In Menu?'
						]
					,'format'=>['sysTasks.ordering'=>"convert(varchar(max),".FRPLCEMNT4FMT.")+'&nbsp;' ordering"]
				]
				,'join'=>[
					['left','sysYesNo','on'=>[[['sysTasks.display','=','sysYesNo.id']]]]
					,['left','sysTaskGroups','on'=>[[['sysTasks.taskGroupId','=','sysTaskGroups.id']]]]
				]
				,'addEditModal'=>[				
					'references'=>['display'=>'sysYesNo.yesno','taskGroupId'=>'sysTaskGroups.groupName']	
					,'fieldLabels'=>['taskName'=>"Task Name",'display'=>'Display in menu or not?','ordering'=>'Ordering in Menu','taskGroupId'=>'Task Group']
					//,'disabled'=>['taskName']
				]
			]		
			#endregion System Administration
];
