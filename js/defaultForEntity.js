
/*
* @descriptions defaultForEntity.js is script file that have been loaded in every entity pages.
* @author Panu Boonpromsook
*/
$(document).ready(function() {
                
                 //init input datetime in filter row to use the specific format
                $(".cientityFilter").each(function(){
                    if($(this).hasClass('datetimepicker')){
                        var cientityDTFormat = $(this).attr('cientityDTFormat');
                        $(this).datetimepicker({format:cientityDTFormat,useCurrent: false});
                   }
                });
                
                //init input datetime in main-enitity to use the specific format
                $(".cientityInputField").each(function(){
                    if($(this).hasClass('datetimepicker')){
                        var cientityDTFormat = $(this).attr('cientityDTFormat');                        
                        $(this).datetimepicker({format:cientityDTFormat,useCurrent: false});
                   }
                });
    
	//init select2 for "select" in filter row
	$(".cientitySelectOptionsOverflow").each( 
	function(){
		var myUrl = $(this).attr('infoForAjaxOptions');
		$(this).select2({ajax:{url:myUrl,dataType:'json',type:"POST",delay:250},width: '100%'});
	});
	
	//init select2 for "select" in AddEditModal
	$(".cientitySelectFromReference").each( 
	function(){
		var myUrl = $(this).attr('infoForAjaxOptions');
		$(this).select2({		
			ajax:{url:myUrl,dataType:'json',type:"POST",delay:250}
			,width: '100%'
			,dropdownParent: $("#cientityAddEditModal") //***
			,language: "th"
		});
	});	
                /**
                 *  init select2 after loaded sub-entity interface
                 * @param {string} cientitySubEntityModalPanelId
                 * @returns {undefined}
                 */
	function cientity_InitSelect2InputInSubEntity(cientitySubEntityModalPanelId){
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']")
		.find(".cientitySelectFromReferenceForSubEntity")
		.each( //sub entity
			function(){
			
			var myUrl = $(this).attr('infoForAjaxOptions');
			$(this).select2(
					{ajax:{url:myUrl,dataType:'json',type:"POST",delay:250}
					,width: '100%'
					
					//see description in ***
					,dropdownParent: $(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']") 
					
					,language: "th"
				});
			});
	}
	
	//init select2 input in SubEntity
	$(".cientitySubmodelPanel").each(function(){ 
                        var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
                        cientity_InitSelect2InputInSubEntity(cientitySubEntityModalPanelId);
	});
	
	//confirm add button in sub-entity is clicked 
	$(".cientitySubEntityConfirmAddButton").click(function(){
		var subEntityEntityId = $(this).attr('entityOrdinal');
		var dataToPost = new Object();
		dataToPost['mainEntityInfo'] = new Object();
		$(".cientityInputField").each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost['mainEntityInfo'][cientityfieldReferenceNumber] = $(this).val();
		});
		dataToPost['mainEntityInfo']['entityOrdinal'] = $(".addEditModalSubmitButton").attr('entityOrdinal');
		dataToPost['mainEntityInfo']['operation'] = 0; //dummy
		
		dataToPost['subEntityInfo'] = new Object();
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+subEntityEntityId+"']")
		.find(".cientityInputFieldForSubEntity")
		.each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost['subEntityInfo'][cientityfieldReferenceNumber] = $(this).val();			
		});
		dataToPost['subEntityInfo']['entityOrdinal'] = subEntityEntityId;
		dataToPost['subEntityInfo']['operation'] = 1;
		
		var notifications = cientitypopupNotification('info','Adding',':::loading',0);
		$.ajax({
			url: cientity_base_url+'m/insertFromSubEntity'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'                                                
			,success:function(data){
				$("#cientityAlertDivId_"+notifications.idNum).remove();
				$(".notification-popup").addClass('hide');
				if ((data.results) && (data.results.notifications)){ 
                                                                        cientity_displayAllNotifications(data.results.notifications,'Adding');
                                                                        
                                                                        //if added successed, refresh sub-entity table
                                                                        if(data.results.notifications.success){
                                                                                if(data.results.notifications.success.length!==0){                                                                                    
                                                                                    cientityLoadDataToSubEntityTable();
                                                                                }
                                                                        }
                                                                }
                                                                
			}
		});		
	});
	
	/**
	 * perform update record in subentity
	 * @param {object} el
	 *      el is input or select in a row of data table. the updating will be activated every time the INPUT or SELECT is changed their value.
	 * @returns {undefined}
	 */
	function do_cientityUpdateSubEntityRecord(el){
		var cientityDataIdRow = $(el).closest('tr').attr('cientityDataIdRow');
		
		var temp = cientityDataIdRow.split('_');
		var entityOrdinal = temp[1];
		
		var dataToPost = new Object();
		//entity id
		dataToPost['entityOrdinal'] = entityOrdinal; 
		//row id
		dataToPost['0'] = temp[0]; 
		//column id
		dataToPost['1'] = $(el).attr('cientityKeyReference'); 
		//updated value
		dataToPost['2'] = $(el).val(); 
		var rollbackuValue = $(el).attr('cientityRollbackValue');
		var notifications = cientitypopupNotification('info','Updating..',':::loading',0);
                                    $.ajax({
                                        url: cientity_base_url+'m/UpdateSubEntityRecord'
                                        ,data:dataToPost
                                        ,type:"POST"
                                        ,dataType:'json'
                                        ,success:function(data){
                                                $("#cientityAlertDivId_"+notifications.idNum).remove();
                                                if(data.results.notifications) cientity_displayAllNotifications(data.results.notifications,'updating result'); 
                                                //incase of error refetch data 
                                                if(data.results.notifications.danger.length>0){
                                                                $(el).val(rollbackuValue);
                                                }else{
                                                                $(el).attr('cientityRollbackValue',dataToPost['2']);
                                                }
                                        }
		});
	}
	
	
	/**
	 * init datatable for sub-entity, and init other component after each loading
	 * @param {string} className
	 * className 
	 * @param {string} cientitySubEntityModalPanelId
	 * panelId or tabId in sub-entity selection tab(nav bar)
	 * @returns {undefined}
	 */
	function cientityInitDataTableAndOtherControl(className, cientitySubEntityModalPanelId){
		
		$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] input.datetimepicker")
                                        .each(function(){
                                                var cientityDTFormat = $(this).attr('cientityDTFormat');			
                                                $(this).datetimepicker({
                                                                format:cientityDTFormat
                                                                ,useCurrent: false
                                                })			
                                                .on("dp.change",function(e){
                                                        //bug id 20180823-01 prevent onchange fire twice
                                                        if(($(this).val()) !== ($(this).attr('cientityRollbackValue'))){                                                         
                                                                        do_cientityUpdateSubEntityRecord(this);
                                                        }                                                
                                                });
                                        });
		
			$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] select.cientitySubEntitySelectTd").each(function(){
					var myUrl = $(this).attr('infoForAjaxOptions');
			$(this).select2({ajax:{url:myUrl,dataType:'json',type:"POST",delay:250},width: '100%'
                                                                ,language: "th"
                                                                ,dropdownParent: $("table.cientitysubEntityDatatable[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']") 
                                                            });
			});
			
			$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] input, ."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] select")
					.change(function(){
						if(!($(this).hasClass('cientitySelectToActionSubentity'))) { //if not select checkbox at rightmost of row
								do_cientityUpdateSubEntityRecord(this);
						}
				})
				.keypress(function(e) {
						if(e.which === 13) {
							//do_cientityUpdateSubEntityRecord(this);
						}
					});
                                 
		var tbLength = $("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] th").length;

                                 //init datatable
		$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").DataTable({
			"bFilter": false,
			"bLengthChange": false,			
			"scrollCollapse": true,			
			"columnDefs" : [{"targets":tbLength-1, "orderable":false}]
		});

		$(".cientitySelectToggleAll[cientityEntityReference='"+cientitySubEntityModalPanelId+"']")
		.unbind('click')
		.click(function(){
			var checked = $(this).is(':checked');			
			$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']")
				.find(".cientitySelectToActionSubentity")
				.each(function(){
						$(this).prop('checked',checked);
			});
		});

		$(".cientityToggleSelectedSubEntityCheckbox[cientityEntityReference='"+cientitySubEntityModalPanelId+"']")
		.unbind('click')
		.click(function(){
			$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").find(".cientitySelectToActionSubentity").each(function(){
				var checked = $(this).is(':checked');
				$(this).prop('checked',!(checked));
			});
		});		

		$(".cientityDeleteExistingSubEntityRecord[cientityEntityReference='"+cientitySubEntityModalPanelId+"']")
		.unbind('click')
		.click(function(){			
			do_cientitydeleteMultipleRecord(cientitySubEntityModalPanelId,className);
			
		});
	}
	
	/*
	* en: delete multiple record in sub-entity
                * @param {string} entityId
                * @param {string} className
	*/
	function do_cientitydeleteMultipleRecord(entityId,className){
		var countChecked = $("."+className+"[cientitySubEntityModalPanelId='"+entityId+"']").find(".cientitySelectToActionSubentity:checked").length;		
		if(countChecked>0){
			
			var notifications = cientitypopupNotification('info','Deleting',':::loading',0);
			$("."+className+"[cientitySubEntityModalPanelId='"+entityId+"']")
			.find(".cientitySelectToActionSubentity")			
			.each(function(){					
					if($(this).is(':checked')){					
						var dataId = $(this).attr('cientityDataId');					
						cientity_doDeleteMultipleRecord(notifications,entityId,dataId);
					}
				});
		}else{
			var notifications = cientitypopupNotification('danger','Deleting','error: There\'s no record selected.',0);
		}		
	}
	
	//submit info of each sub-entity to perform delete at back-end
	function cientity_doDeleteMultipleRecord(notifications,entityOrdinal,dataId){
		cientity_doDeletSingleRecord(notifications,entityOrdinal,dataId);
	}

	$(".cientityFilterStartSearch").click(function(){
		cientity_getRowListByConditionsInFilterRow();		
	});

	$(".cientityFilter").keyup(function(e){
		if(e.which === 13) {
			cientity_getRowListByConditionsInFilterRow();		
		}
	});

	/*		
		en:fetch data data from back-end after click search in filter rows
		th:ดึงข้อมูลจากฐานข้อมูลมาแสดงหลังจากคลิกปุ่มค้น
	*/
	function cientity_getRowListByConditionsInFilterRow(){
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = $(".cientityFilterStartSearch").attr('entityOrdinal');
		$(".searchProgressBarRow").show();
		$(".searchResultsDataTableRow").hide();
		$(".cientityFilter").each(function(){				
                                        if($(this).hasClass('cientityFormDate')){				

                                        }
                                        dataToPost[$(this).attr('cientityFormFilterOrder')] = $(this).val();
		});
		$.ajax({
			url: cientity_base_url+'m/getRowListByConditionsInFilterRow'
			,data:dataToPost
			,type:"POST"
			,dataType:'json'
			,success:function(data){
				$(".searchProgressBarRow").hide();	
				
				//en:if search result is found, th:ถ้ามีผลการค้นส่งกลับมา				
				if(data.searchResults) 
				{
                                                                            $(".cientityDisplaySearchResult").html(data.searchResults.results);				
                                                                            $(".searchResultsDataTableRow").show();
                                                                            $('.cientityEditExistingEntityRecord').unbind('click').click(function(){		
                                                                                    $('#cientityOperationAddOrEditDesc').html('Edit ');
                                                                                    $('.cientityOperationForAddEditModal').val('0');
                                                                                    cientityPutDataIntoAddEditModalForm(this);						
                                                                            });
                                                                        if((data.searchResults.notifications)) cientity_displayAllNotifications(data.searchResults.notifications,'danger/warning');
				}
				init_cientityDeleteExistingEntityRecord();

				//th:เอาไว้จัดการกับ error message บางกรณีเช่น session หลุดไปแล้ว และแจ้งเตือนให้เข้าสู่ระบบใหม่
				//en:display error message in case of any error at back-end such as session is dead.
				
			}
		});
	}
	
	//refresh all inputs in addEditModal after click "add" or "edit"
	$('#cientityAddNewEntityRecord').click(function(){
		$('#cientityOperationAddOrEditDesc').html('Add ');
		$(".cientityInputField").each(function(){
			$(this).val('');
			//th:ถ้าเป็น select เอาทุก option ออก
			//en:if element is "select" then remove all options
			if($(this).hasClass('cientitySelectFromReference')) 
			{
				$(this).find('option').remove().end();
			}
		});
		$('.cientityOperationForAddEditModal').val('1');
		//en:hide SubEntity, th:ซ่อน SubEntity		
		$(".cientitySubEntityRow").addClass('hide'); 
	});
	
	$('.addEditModalSubmitButton').click(function(){
		cientityDoSubmitAddEditModalForm(this);		
	});
	
	/**
	* en:ส่งข้อมูลในฟอร์ม addEditModal เพื่อไปบันทึก
	* th:send data in addEditModal form to save at back-end
                * @@param {object} el
	*/
	function cientityDoSubmitAddEditModalForm(el){
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = $(el).attr('entityOrdinal');
		$(".cientityInputField").each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost[cientityfieldReferenceNumber] = $(this).val();
		});
		var notifications = cientitypopupNotification('info','Data is being saved',':::loading',0);
		dataToPost['operation'] = $('.cientityOperationForAddEditModal').val();
		$.ajax({
			url: cientity_base_url+'m/saveAddEditData'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'
			,success:function(data){
				$("#cientityAlertDivId_"+notifications.idNum).remove();
				$(".notification-popup").addClass('hide');
				if ((data.results) && (data.results.notifications)) cientity_displayAllNotifications(data.results.notifications,'Saving result');				
			}
		});
	}
	
	/**
	* th:ดึงข้อมูลจากฐานข้อมูลมาแสดงใน AddEditModal เพื่อแก้ไข
	* en:fetch data from back-end and put in AddEditModal for edit.
	*/
                /**
                 * 
                 * @param {object} el
                 * @returns {undefined}
                 */
	function cientityPutDataIntoAddEditModalForm(el){
		$(".cientityInputField").each(function(){			
			$(this).find('option').remove();
		});
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = $(el).attr('cientityEntityReference');
		dataToPost['id'] = $(el).attr('cientityDataId');
		$.ajax({
			url: cientity_base_url+'m/loadDataToEditInModal'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'
			,success:function(data){				
				if(data.results.fields){
						cientityPutDataIntoAddEditForm(data.results.fields);
						cientityLoadDataToSubEntityTable();
				}
				if(data.results.references) cientityPutRefDataIntoAddEditForm(data.results.references);

				//เอาไว้จัดการกับ error message บางกรณีเช่น session หลุดไปแล้ว และแจ้งเตือนให้เข้าสู่ระบบใหม่
				if((data.results) && (data.results.notifications)) cientity_displayAllNotifications(data.results.notifications,'');
				$(".cientitySubEntityRow").removeClass('hide'); //โชว์ SubEntity
			}
		});
	}
	
	//display all notificaiton sent from back-end
	function cientity_displayAllNotifications(notifications,task){
		$.each(notifications,function(alertType,value){
			$.each(value, function (key, alertMessage){
				cientitypopupNotification(alertType,task,alertMessage);
			});
		});
	}
	
	//put data in to addEditModal form 
	function cientityPutDataIntoAddEditForm(fields){
		fields.forEach(function(item,index){			
			$(".cientityInputField[cientityfieldReferenceNumber='"+index+"']").val(item);
		});
	}
	
	//put references information for select2 in addEditModal
	function cientityPutRefDataIntoAddEditForm(references){		
                        references.forEach(function(item,index){			
                                    var tempArr = item.split('#++||||++#'); 
                                    var itemIdex = tempArr[0];
                                    //th:เผื่อ ฟิลด์ references นั้น มีค่าเป็น null
                                    //en:prevent error if current field is null
                                    tempArr[1] = (tempArr[1]?tempArr[1]:""); 
                                    $(".cientityInputField[cientityfieldReferenceNumber='"+itemIdex+"']").append("<option value='"+tempArr[1]+"'>"+tempArr[2]+"</option>");
                                    $(".cientityInputField[cientityfieldReferenceNumber='"+itemIdex+"']").val(tempArr[1]);			
		});
	}
	
	//th: จัดการกับ notifications
	//
                /**
                 * Display notificaitons as pop-up.
                 * @param {string} alertType
                 * @param {string} taskMsg
                 * @param {string} alertMsg
                 * @param {int} timeOut 
                 * @returns {defaultForEntityL#6.cientitypopupNotification.returnData}
                 */
	function cientitypopupNotification(alertType,taskMsg,alertMsg, timeOut){
		var fsetTimeout = timeOut?timeOut:3000;
				
		$(".notification-popup").css({'opacity':.98,'padding':'1px','border-color':'#55ce63','background-color':'#55ce63'});
				
		if($(".cientityAlert").length===0){
			var idNum = 1;
		}else{
			var lastId = $(".cientityAlert").last().attr('id');
			var temp = lastId.split("_");
			var idNum = parseInt(temp[1])+1;			
		}
		alertMsg=alertMsg===":::loading"
			?"<div class=\"progress\"><div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"45\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\"><span class=\"sr-only\">45% Complete</span></div></div>"
			:alertMsg;
		var alertHtml = 
		"<div class=\"alert cientityAlert alert-"+alertType+" alert-dismissible\" role=\"alert\" id=\"cientityAlertDivId_"+idNum+"\"> <button type=\"button\" class=\"close cientityCloseAlert\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span> </button><p><span class=\"task\">"+taskMsg+"</span></p> <p><span class=\"notification-text\">"+alertMsg+"</span></p></div>";
		
		$("#cientityAlertDiv").append(alertHtml);		
		$(".cientityAlert")
			.css({'margin-bottom':'1px'})
			
			//th: เผื่อลค.คลิก closed เอง…
			//en: if user click dismiss button
			.on('closed.bs.alert', function () {				
				if($(".cientityAlert").length===0){
					$(".notification-popup").addClass('hide');
				}
			});
		
		$(".notification-popup").removeClass('hide');	
		var notificationTimeOut = setTimeout(
			function(){				
				$('#cientityAlertDivId_'+idNum).fadeTo("slow", 0, function(){					
					$('#cientityAlertDivId_'+idNum).remove();					
					if($(".cientityAlert").length===0){
						$(".notification-popup").addClass('hide');
					}
				});				
			//},fsetTimeout);
			},5000);
		 
		var returnData = {notificationTimeOut:notificationTimeOut, idNum:idNum, alertType:alertType};
		return returnData;
	}
	/*
	function cientityResetetPopupNotification(alertType,taskMsg,alertMsg, objData){
		var oldAlertType=objData.alertType;
		var idNum = objData.idNum;
		var oldnotificationTimeOut = objData.notificationTimeOut;
		clearTimeout(oldnotificationTimeOut);
		$('#cientityAlertDivId_'+idNum).removeClass("alert-"+oldAlertType);
		$('#cientityAlertDivId_'+idNum).addClass("alert-"+alertType);
		$('#cientityAlertDivId_'+idNum+" .progress").remove();
		$('#cientityAlertDivId_'+idNum+" .notification-text").html(alertMsg);
		var notificationTimeOut = setTimeout(
			function(){				
				$('#cientityAlertDivId_'+idNum).fadeTo("fast", 0, function(){					
					$('#cientityAlertDivId_'+idNum).remove();					
					if($(".cientityAlert").length===0){
						$(".notification-popup").addClass('hide');
					}
				});				
			},2500);
	}
	*/
	//delete data
	function init_cientityDeleteExistingEntityRecord(){
		$('.cientityDeleteExistingEntityRecord').unbind('click').click(function(){		
			cientity_DopreparePopupDelete_Confirmations(this);
		});
	}
	
	function cientity_DopreparePopupDelete_Confirmations(el){
		$('#cientityEntityIdToDelete').val($(el).attr('cientityEntityReference'));
		$('#cientityDataIdToDelete').val($(el).attr('cientityDataId'));		
	}
	
	$('#cientityConfirmDelete').click(function(){	
		var notifications = cientitypopupNotification('info','Deleting',':::loading',0);
		var entityOrdinal = $("#cientityEntityIdToDelete").val();
		var dataId = $("#cientityDataIdToDelete").val();
		cientity_doDeletSingleRecord(notifications,entityOrdinal,dataId);
	});
	
	function cientity_doDeletSingleRecord(notifications,entityOrdinal,dataId){
		
		$.ajax({
			url: cientity_base_url+'m/deleteData'
			,data:{entityOrdinal:entityOrdinal,dataId:dataId}
                                                ,type:"POST"
			,dataType:'json'
			,success:function(data){
				if(notifications) $("#cientityAlertDivId_"+notifications.idNum).remove();
				$(".notification-popup").addClass('hide');
				if(data.results.notifications) cientity_displayAllNotifications(data.results.notifications,'Deletion');				
				if(data.results.notifications.success[0]){
                                                                        $("tr[cientityDataIdRow='"+dataId+"_"+entityOrdinal+"']").fadeOut("slow");
                                                                        cientity_getRowListByConditionsInFilterRow();         
				}
			}
		});
	}
	
	$(".cientitySubEntityModalNavBar li").click(function(){
		cientityLoadSubEntityContents(this);
		
	});
	
	function cientityLoadSubEntityContents(el){
		var cientitySubEntityModalPanelId = $(el).attr('cientitySubEntityModalPanelId');		
		$(".cientitySubEntityModalNavBar li").removeClass('active');
		$(el).addClass('active');
		$(".cientitySubmodelPanel").addClass('hide');
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');
		cientityLoadDataToSubEntityTable();
	}
	
	/**
	* load data of sub-entity and put into table of sub-entity
	*/
	function cientityLoadDataToSubEntityTable(){
		//th:วนหาอันที่ active ก่อน
		//en:loop to find active navbar
		var cientitySubEntityModalPanelId = -1;
		$(".cientitySubEntityModalNavBar li").each(function(){
			if($(this).hasClass("active")){
				cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
			}		
		});
		
		
		//th:เอา ค่าของฟิลด์ทั้งหมดส่งไปใน mainEntityInfo เพราะไม่รู้ว่าอันไหนคือ id
		var dataToPost = new Object();
		dataToPost.mainEntityInfo = new Object();
		dataToPost.subEntityInfo = new Object();
		$(".cientityInputField").each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost['mainEntityInfo'][cientityfieldReferenceNumber] = $(this).val();
		});
		dataToPost['mainEntityInfo']['entityOrdinal'] = $('.addEditModalSubmitButton').attr('entityOrdinal');
		dataToPost['subEntityInfo']['entityOrdinal'] = cientitySubEntityModalPanelId;                                
		$(".cientityDisplaySearchResultSubEnitity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").html('');
		$(".searchProgressBarRowSubEntity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');	
		//ส่งข้อมูลไปยัง backend
		$.ajax({
			url: cientity_base_url+'m/loadDataToSubEntityTable'
			,data:dataToPost
			,type:"POST"
			,dataType:'json'
			,success:function(data){
                                                        $(".searchProgressBarRowSubEntity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").addClass('hide');	
                                                        $(".cientityDisplaySearchResultSubEnitity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").empty();
                                                        if(data.subEntityResults){
                                                                $(".cientityDisplaySearchResultSubEnitity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']")
                                                                .html(data.subEntityResults.results);
                                                        }
                                                        cientityInitDataTableAndOtherControl("cientitysubEntityDatatable", cientitySubEntityModalPanelId);
                                                        if ((data.results) && (data.results.notifications)) cientity_displayAllNotifications(data.results.notifications,'Notification');
                                                        if (data.notifications) cientity_displayAllNotifications(data.notifications,'Notification');
			}
		});
	}

	$(".cientityShowAddEditSubEntityPanel").click(function(){
		var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
		$(".cientitySubPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');
	});
	$(".cientityHidePanel").click(function(){
		var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
		$(".cientitySubPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").addClass('hide');
	});
});


//***th: select2 ที่อยู่บน component อื่นใน modal ทำงานเพี้ยน แก้ปัญหาโดยระบุ พ่อของมันให้ เอาเทคนิคนี้มาจาก มาจาก https://stackoverflow.com/questions/18487056/select2-doesnt-work-when-embedded-in-a-bootstrap-modal/33884094#33884094
//***en: select2 which placed on other component,in modal,not works as expected. The problem solved by tell selec2 its parent. This technic derived from https://stackoverflow.com/questions/18487056/select2-doesnt-work-when-embedded-in-a-bootstrap-modal/33884094#33884094
