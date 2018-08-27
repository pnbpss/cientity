
/*
* @descriptions defaultForEntity.js เป็น script ที่ต้องโหลดในทุกๆหน้าของ entity เพื่อจัดการกับ components ต่างๆที่ customize
* @author Panu Boonpromsook
*/
$(document).ready(function() {
	$(".cientitySelectOptionsOverflow").each( //filter row
	function(){
		var myUrl = $(this).attr('infoForAjaxOptions');
		$(this).select2({ajax:{url:myUrl,dataType:'json',type:"POST",delay:250},width: '100%'});
	});
	
	$(".cientitySelectFromReference").each( //add edit main entity
	function(){
		var myUrl = $(this).attr('infoForAjaxOptions');
		$(this).select2({		
			ajax:{url:myUrl,dataType:'json',type:"POST",delay:250}
			,width: '100%'
			,dropdownParent: $("#cientityAddEditModal") //ถ้าไม่มีตัวนี้ จะพิมพ์เพื่อค้นหาไม่ได้  ***
			,language: "th"
		});
	});
	/**
	 * init select2 after loaded sub-entity interface
	 * @param string cientitySubEntityModalPanelId 
	 */
	function cientity_InitSelect2InputInSubmodal(cientitySubEntityModalPanelId){
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']")
		.find(".cientitySelectFromReferenceForSubModal")
		.each( //sub entity
			function(){
			//$(".cientitySubmodelPanel"+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']")
			var myUrl = $(this).attr('infoForAjaxOptions');
			$(this).select2(
					{ajax:{url:myUrl,dataType:'json',type:"POST",delay:250}
					,width: '100%'
					
					//ถ้าไม่มีตัวนี้ จะพิมพ์เพื่อค้นหาไม่ได้  ***
					,dropdownParent: $(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']") 
					
					,language: "th"
				});
			});
	}
	$(".cientitySubmodelPanel").each(function(){ //init select2 input in submodal
		var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
		cientity_InitSelect2InputInSubmodal(cientitySubEntityModalPanelId);
	});
	
	$(".cientitySubEntityConformAddButton").click(function(){
		var subModalEntityId = $(this).attr('entityOrdinal');
		var dataToPost = new Object();
		dataToPost['mainEntityInfo'] = new Object();
		$(".cientityInputField").each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost['mainEntityInfo'][cientityfieldReferenceNumber] = $(this).val();
		});
		dataToPost['mainEntityInfo']['entityOrdinal'] = $(".addEditModalSubmitButton").attr('entityOrdinal');
		dataToPost['mainEntityInfo']['operation'] = 0; //หลอกไปก่อน ไม่ได้ update จริง		
		
		dataToPost['subEntityInfo'] = new Object();
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+subModalEntityId+"']")
		.find(".cientityInputFieldForSubModal")
		.each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost['subEntityInfo'][cientityfieldReferenceNumber] = $(this).val();
			//alert($(this).val());
		});
		dataToPost['subEntityInfo']['entityOrdinal'] = subModalEntityId;
		dataToPost['subEntityInfo']['operation'] = 1;
		
		var notifications = cientitypopupNotification('info','เพิ่มข้อมูล',':::loading',0);
		$.ajax({
			url: cientity_base_url+'m/insertFromSubEntity'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'                                                
			,success:function(data){
				$("#cientityAlertDivId_"+notifications.idNum).remove();
				$(".notification-popup").addClass('hide');
				if(data.results.notifications) cientity_displayAllNotifications(data.results.notifications,'เพิ่มข้อมูล');
			}
		});		
	});
	/**
	 * perform update record in subentity
	 * @param object el
	 *      el is input or select in a row of data table. the updating will be activated every time the INPUT or SELECT is changed their value.
	 * @returns {undefined}
	 */
	function do_cientityUpdateSubEntityRecord(el){
		var cientityDataIdRow = $(el).closest('tr').attr('cientityDataIdRow');
		//alert(cientityDataIdRow+' '+$(el).val());
		var temp = cientityDataIdRow.split('_');
		var entityOrdinal = temp[1];
		
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = entityOrdinal; //entity id
		dataToPost['0'] = temp[0]; //row id
		dataToPost['1'] = $(el).attr('cientityKeyReference'); //column id
		dataToPost['2'] = $(el).val(); //updated value
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
	//$("#select2Input").select2({ dropdownParent: "#modal-container" });
	
	/**
	 * init datatable for sub-entity, and init other component after each loading
	 * @param string className
	 * className 
	 * @param string cientitySubEntityModalPanelId
	 * panelId or tabId in sub-entity selection tab(nav bar)
	 * @returns {undefined}
	 */
	function cientityInitDataTableAndOtherControl(className, cientitySubEntityModalPanelId){
		
		$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] input.datetimepicker").each(function(){
			var cientityDTFormat = $(this).attr('cientityDTFormat');
			//alert(cientityDTFormat);
			$(this).datetimepicker({
					format:cientityDTFormat
					,useCurrent: false
			})
			//.data('DateTimePicker').date(moment(new Date ()))
				.on("dp.change",function(e){
						//bug id 20180823-01 prevent onchange fire twice
					if(($(this).val()) !== ($(this).attr('cientityRollbackValue'))){                                                         
							do_cientityUpdateSubEntityRecord(this);
					}                                                
			});
		});
		
			$("."+className+"[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"'] select.cientitySubModalSelectTd").each(function(){
					var myUrl = $(this).attr('infoForAjaxOptions');
				$(this).select2({ajax:{url:myUrl,dataType:'json',type:"POST",delay:250},width: '100%',language: "th"});
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
			//"scrollY": "400px",
			"scrollCollapse": true,
			//"paging": true,
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

	function do_cientitydeleteMultipleRecord(entityId,className){
		var countChecked = $("."+className+"[cientitySubEntityModalPanelId='"+entityId+"']").find(".cientitySelectToActionSubentity:checked").length;		
		if(countChecked>0){
			//var table = $("."+className+"[cientitySubEntityModalPanelId='"+entityId+"']"); //ใช้ไม่ได้ meทำให้ notification ไม่ clear ให้
			var notifications = cientitypopupNotification('info','ลบข้อมูล',':::loading',0);
			$("."+className+"[cientitySubEntityModalPanelId='"+entityId+"']")
			.find(".cientitySelectToActionSubentity")			
			.each(function(){					
					if($(this).is(':checked')){					
						var dataId = $(this).attr('cientityDataId');					
						cientity_doDeleteMultipleRecord(notifications,entityId,dataId);
					}
				});
		}else{
			var notifications = cientitypopupNotification('danger','ลบข้อมูล','error: ยังไม่ได้เลือกว่าจะลบอะไร',0);
		}		
	}

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
		ดึงข้อมูลจากฐานข้อมูลมาแสดงหลังจากคลิกปุ่มค้น
	*/
	function cientity_getRowListByConditionsInFilterRow(){
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = $(".cientityFilterStartSearch").attr('entityOrdinal');
		$(".searchProgressBarRow").show();
		$(".searchResultsDataTableRow").hide();
		$(".cientityFilter").each(function(){
				//alert($(this).val());
				if($(this).hasClass('cientityFormDate')){				
						//dataToPost[$(this).attr('cientityFormFilterOrder')][$(this).attr('cientityFormDateTimeFilter')] = $(this).val();				
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
				if(data.searchResults) //ถ้ามีผลการค้นส่งกลับมา
				{
					$(".cientityDisplaySearchResult").html(data.searchResults.results);				
					$(".searchResultsDataTableRow").show();
					$('.cientityEditExistingEntityRecord').unbind('click').click(function(){		
						$('#cientityOperationAddOrEditDesc').html('แก้ไข');
						$('.cientityOperationForAddEditModal').val('0');
						cientityPutDataIntoAddEditModalForm(this);
						//cientityInitDataTableAndOtherControl();
					});
				}
				init_cientityDeleteExistingEntityRecord();

				//เอาไว้จัดการกับ error message บางกรณีเช่น session หลุดไปแล้ว และแจ้งเตือนให้เข้าสู่ระบบใหม่
				if((data.results) && (data.results.notifications)) cientity_displayAllNotifications(data.results.notifications,'');
			}
		});
	}
	
	$('#cientityAddNewEntityRecord').click(function(){
		$('#cientityOperationAddOrEditDesc').html('เพิ่ม');
		$(".cientityInputField").each(function(){
			$(this).val('');
			if($(this).hasClass('cientitySelectFromReference')) //ถ้าเป็น select เอาทุก option ออก
			{
				$(this).find('option').remove().end();
			}
		});
		$('.cientityOperationForAddEditModal').val('1');
		$(".cientitySubEntityRow").addClass('hide'); //ซ่อน submodal
	});
	
	$('.addEditModalSubmitButton').click(function(){
		cientityDoSubmitAddEditModalForm(this);
		
	});
	
	/**
	* ส่งข้อมูลในฟอร์ม addEditModal เพื่อไปบันทึก
	*/
	function cientityDoSubmitAddEditModalForm(el)
	{
		var dataToPost = new Object();
		dataToPost['entityOrdinal'] = $(el).attr('entityOrdinal');
		$(".cientityInputField").each(function(){
			var cientityfieldReferenceNumber = $(this).attr('cientityfieldReferenceNumber');
			dataToPost[cientityfieldReferenceNumber] = $(this).val();
		});
		var notifications = cientitypopupNotification('info','เพิ่มข้อมูล',':::loading',0);
		dataToPost['operation'] = $('.cientityOperationForAddEditModal').val();
		$.ajax({
			url: cientity_base_url+'m/saveAddEditData'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'
			,success:function(data){
				$("#cientityAlertDivId_"+notifications.idNum).remove();
				$(".notification-popup").addClass('hide');
				if(data.results.notifications) cientity_displayAllNotifications(data.results.notifications,'เพิ่มข้อมูล');
				//cientitypopupNotification('danger','เพิ่มข้อมูล','ยังไม่ได้ระบุวดป.เกิด');
			}
		});
	}
	
	/**
	* ดึงข้อมูลจากฐานข้อมูลมาแสดงใน AddEditModal เพื่อแก้ไข
	*/
	function cientityPutDataIntoAddEditModalForm(el)
	{
		$(".cientityInputField").each(function(){
			//$(this).val('');
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
						cientityLoadDataToSubModalTable();
				}
				if(data.results.references) cientityPutRefDataIntoAddEditForm(data.results.references);

				//เอาไว้จัดการกับ error message บางกรณีเช่น session หลุดไปแล้ว และแจ้งเตือนให้เข้าสู่ระบบใหม่
				if((data.results) && (data.results.notifications)) cientity_displayAllNotifications(data.results.notifications,'');
				$(".cientitySubEntityRow").removeClass('hide'); //โชว์ submodal
			}
		});
	}
	
	function cientity_displayAllNotifications(notifications,task)
	{
		$.each(notifications,function(alertType,value){
			$.each(value, function (key, alertMessage){
				cientitypopupNotification(alertType,task,alertMessage);
			});
		});
	}
	
	function cientityPutDataIntoAddEditForm(fields)
	{
		fields.forEach(function(item,index){
			//alert(item+' '+index);
			$(".cientityInputField[cientityfieldReferenceNumber='"+index+"']").val(item);
		});
	}
	function cientityPutRefDataIntoAddEditForm(references)
	{		
		references.forEach(function(item,index){
			//alert(item+' '+index);
			var tempArr = item.split('#++||||++#');
			var itemIdex = tempArr[0];
			tempArr[1] = (tempArr[1]?tempArr[1]:""); // เผื่อ ฟิลด์ references นั้น มีค่าเป็น null มา
			$(".cientityInputField[cientityfieldReferenceNumber='"+itemIdex+"']").append("<option value='"+tempArr[1]+"'>"+tempArr[2]+"</option>");
			$(".cientityInputField[cientityfieldReferenceNumber='"+itemIdex+"']").val(tempArr[1]);			
		});
	}
	
	//จัดการกับ notifications 	
	function cientitypopupNotification(alertType,taskMsg,alertMsg, timeOut)
	{
		var fsetTimeout = timeOut?timeOut:3000;
				
		$(".notification-popup").css({'opacity':.98,'padding':'1px','border-color':'#55ce63','background-color':'#55ce63'});
		
		//$(".cientityAlert").css({'margin-bottom':'0px','padding':'0px'});	
                                //alert($(".cientityAlert").html());
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
			
			// เผื่อลค.คลิก closed เอง…
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
	function cientityResetetPopupNotification(alertType,taskMsg,alertMsg, objData)
	{
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
	
	//delete data
	function init_cientityDeleteExistingEntityRecord(){
		$('.cientityDeleteExistingEntityRecord').unbind('click').click(function(){		
			cientity_DopreparePopupDelete_Confirmations(this);
		});
	}
	
	function cientity_DopreparePopupDelete_Confirmations(el)
	{
		$('#cientityEntityIdToDelete').val($(el).attr('cientityEntityReference'));
		$('#cientityDataIdToDelete').val($(el).attr('cientityDataId'));		
	}
	$('#cientityConfirmDelete').click(function()
	{	
		var notifications = cientitypopupNotification('info','ลบข้อมูล',':::loading',0);
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
				if(data.results.notifications) cientity_displayAllNotifications(data.results.notifications,'ลบข้อมูล');
				//cientitypopupNotification('danger','เพิ่มข้อมูล','ยังไม่ได้ระบุวดป.เกิด');
				if(data.results.notifications.success[0]){
					$("tr[cientityDataIdRow='"+dataId+"_"+entityOrdinal+"']").fadeOut("slow");
				}
			}
		});
	}
	
	$(".cientitySubEntityModalNavBar li").click(function(){
		cientityLoadSubModalContents(this);
		
	});
	function cientityLoadSubModalContents(el)
	{
		var cientitySubEntityModalPanelId = $(el).attr('cientitySubEntityModalPanelId');		
		$(".cientitySubEntityModalNavBar li").removeClass('active');
		$(el).addClass('active');
		$(".cientitySubmodelPanel").addClass('hide');
		$(".cientitySubmodelPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');
		cientityLoadDataToSubModalTable();
	}
	
	function cientityLoadDataToSubModalTable(){
		//วนหาอันที่ active ก่อน
		var cientitySubEntityModalPanelId = -1;
		$(".cientitySubEntityModalNavBar li").each(function(){
			if($(this).hasClass("active")){
				cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
			}		
		});
		//alert(cientitySubEntityModalPanelId);
		
		//เอา ค่าของฟิลด์ทั้งหมดส่งไปใน mainEntityInfo เพราะไม่รู้ว่าอันไหนคือ id
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
		$(".searchProgressBarRowSubModel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');	
		//ส่งข้อมูลไปยัง backend
		$.ajax({
			url: cientity_base_url+'m/loadDataToSubModalTable'
			,data:dataToPost
                                                ,type:"POST"
			,dataType:'json'
			,success:function(data){
				$(".searchProgressBarRowSubModel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").addClass('hide');	
				$(".cientityDisplaySearchResultSubEnitity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").empty();
				$(".cientityDisplaySearchResultSubEnitity[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").html(data.subModelResults.results);
				cientityInitDataTableAndOtherControl("cientitysubModalDatatable", cientitySubEntityModalPanelId);
                                                        
			}
		});
	}

	$(".cientityShowAddEditSubmodalPanel").click(function(){
		var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
		$(".cientitySubPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").removeClass('hide');
	});
	$(".cientityHidePanel").click(function(){
		var cientitySubEntityModalPanelId = $(this).attr('cientitySubEntityModalPanelId');
		$(".cientitySubPanel[cientitySubEntityModalPanelId='"+cientitySubEntityModalPanelId+"']").addClass('hide');
	});
});


//*** เอามาจาก https://stackoverflow.com/questions/18487056/select2-doesnt-work-when-embedded-in-a-bootstrap-modal/33884094#33884094