<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
							,'sysUserGroups.name;;group name'
							,'sysUsers.updateBy;;Last update by'
							,'sysUsers.lastUpdate;;last update'
						]
					,'format'=>['sysUsers.lastUpdate'=>"CONVERT(varchar(max),".FRPLCEMNT4FMT.",103)+' '+CONVERT(varchar(max),cast(".FRPLCEMNT4FMT." as time),100) lastUpdate"]												
				]
				,'join'=>[['left','sysUserGroups','on'=>[[['sysUsers.groupId','=','sysUserGroups.id']]]]]
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
				'descriptions' => "User Group's Priviledges to Task"
				,'filtersBar'=>['display'=>["sysUserGroups.name::;;User Group Name",'sysTasks.taskName::;;Task Name']]
				,'selectAttributes'=>[
					'fields'=>[		
							'sysUserGroups.name;;User Group Name'
							,'sysUserGroups.descriptions;;User Group Descriptions'
							,'sysTasks.taskName;;Task Name'
							,'sysTaskGroups.groupName;;Task Group Name'
							,'sysUserTaskPrivileges.allowSave;;Allow U/I/D'
							,'sysTasks.ordering;;Ordering in Menu'
						]
					,'format'=>['sysTasks.ordering'=>"convert(varchar,5,".FRPLCEMNT4FMT.")+'&nbsp;' ordering"]
				]
				,'join'=>[
					['left','sysUserGroups','on'=>[[['sysUserTaskPrivileges.userGroupId','=','sysUserGroups.id']]]]
					,['left','sysTasks','on'=>[[['sysUserTaskPrivileges.taskId','=','sysTasks.id']]]]
					,['left','sysTaskGroups','on'=>[[['sysTasks.taskGroupId','=','sysTaskGroups.id']]]]
				]
				,'addEditModal'=>[				
					'references'=>['userGroupId'=>'sysTaskGroups.groupName','taskId'=>'sysTasks.taskName']	
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
					,'format'=>['sysTaskGroups.ordering'=>"convert(varchar,5,".FRPLCEMNT4FMT.")+'&nbsp;&nbsp;' ordering"]
				]
			]
			,'sysTasks'=>[
				'descriptions' => 'Tasks'
				,'filtersBar'=>['display'=>['sysTasks.taskName;;Task Name','sysTaskGroups.groupName;;Task Group Name']]
				,'selectAttributes'=>[
					'fields'=>[
							'sysTasks.taskName;;Task Name'
							,'sysTaskGroups.groupName;;Task Group Name'						
							,'sysTasks.ordering;;Ordering'
							,'sysYesNo.yesno;;Display In Menu?'
						]
					,'format'=>['sysTasks.ordering'=>"convert(varchar,5,".FRPLCEMNT4FMT.")+'&nbsp;' ordering"]
				]
				,'join'=>[
					['left','sysYesNo','on'=>[[['sysTasks.display','=','sysYesNo.id']]]]
					,['left','sysTaskGroups','on'=>[[['sysTasks.taskGroupId','=','sysTaskGroups.id']]]]
				]
				,'addEditModal'=>[				
					'references'=>['display'=>'sysYesNo.yesno','taskGroupId'=>'sysTaskGroups.groupName']	
					,'fieldLabels'=>['taskName'=>"Task Name",'display'=>'Display in menu or not?','ordering'=>'Ordering in Menu','taskGroupId'=>'Task Group']
				]
			]		
			#endregion System Administration
];

foreach($recipes as $key=>$recipe){$this->recipes[$key]=$recipe;}