<?php
require_once APPPATH."views/incPage.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/img/favicon.png">
        <title>HRD - <?php incPage::display($entityThDescription);?></title>
		<link href="https://fonts.googleapis.com/css?family=Tahoma:300,400,500,600,700" rel="stylesheet">
		 <?php incPage::header_JS_CSS($header_JS_CSS); ?>
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/cientityStyle.css">
    </head>
    <body>
        <div class="main-wrapper">
            <div class="header">
                <div class="header-left">
                    <a href="index.html" class="logo">
						<img src="<?php echo base_url();?>assets/img/logo.png" width="40" height="40" alt="">
					</a>
                </div>
                <div class="page-title-box pull-left">
					<h3>Human Resources Development System (CI-Entity Demo)</h3>
                </div>
				<a id="mobile_btn" class="mobile_btn pull-left" href="#sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
				<ul class="nav navbar-nav navbar-right user-menu pull-right">									
					<li class="dropdown">
						<a href="profile.html" class="dropdown-toggle user-link" data-toggle="dropdown" title="<?php incPage::display($userInfo['Fname']);?>">
							<span class="user-img"><img class="img-circle" src="<?php echo base_url();?>assets/img/user.jpg" width="40" alt="<?php incPage::display($userInfo['Fname']);?>">
							<span class="status online"></span></span>
							<span><?php incPage::display($userInfo['Fname']);?></span>
							<i class="caret"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url()."user/logout";?>">Logout</a></li>
						</ul>
					</li>
				</ul>
				<div class="dropdown mobile-user-menu pull-right">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<ul class="dropdown-menu pull-right">
						<li><a href="<?php echo base_url()."user/logout";?>">Logout</a></li>
					</ul>
				</div>
            </div>
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">						
						<?php incPage::displayLeftMenus($menus,$activeMenuItem);?>
					</div>
                </div>
            </div>	
			
			
            <div class="page-wrapper">
				<div class="notification-popup hide">
					<div class='row'>
						<div class="col-md-12" id="cientityAlertDiv"></div>
					</div>					
				</div>
				
				<!-- content start!-->
                <div class="content container-fluid"> 
					<div class="row">
						<div class="col-sm-4 col-xs-3">
							<h4 class="page-title"><?php incPage::display($entityThDescription);?><?php incPage::display($entityMoreDetailDesc);?></h4>							
						</div>
						<?php
							if($customizedEntity===false){
						?>
							<div id='cientityAddNewEntityRecord' class="col-sm-8 col-xs-9 text-right m-b-20">
								<a href="#" class="btn btn-primary rounded pull-right" data-toggle="modal" data-target="#cientityAddEditModal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add <small><?php incPage::display($entityThDescription);?></small></a>
								<!--div class="view-icons">
									<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
									<a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
								</div!-->
							</div>
						<?php } 
						?>
					</div>
					<!-- filter row start!-->
					<?php incPage::display($filterRow);?>
					<!-- filter row end!-->
					<div class="row searchProgressBarRow" style="display:none;">
						<div class="col-md-12">
								<div class="progress" style="margin-top:10px;">
								  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
									<span class="sr-only">100% Complete</span>
								  </div>
								</div>
						</div>
					</div>
					
					<!--data table start!-->					
					<div class="row searchResultsDataTableRow">
						<div class="col-md-12">
							<div class="table-responsive cientityDisplaySearchResult"  style="margin-top:10px;"></div>
						</div>
					</div>
					<!--data table end!-->					
				</div> 
				<!-- content end!-->				
			</div>
			<?php incPage::pageloaderModal();?>
			<div id="cientityAddEditModal" class="modal custom-modal" role="dialog">
				<?php incPage::displayAddEditModal($addEditModal);?>
				<!--page-wrapper start col-md-12 สำหรับแสดง subEentity!-->
				
				<!--page-wrapper end สำหรับแสดง subEentity!-->
			</div>
			
			
	
			<div id="cientityDeleteModal" class="modal custom-modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Delete selected <?php incPage::display($entityThDescription);?> Record.</h4>
						</div>
						<div class="modal-body card-box">
							<p>Are you sure?</p>
							<div class="m-t-20"> 
								<a href="#" class="btn btn-default" data-dismiss="modal">No, close</a>
								<input type='hidden' id='cientityEntityIdToDelete'>
								<input type='hidden' id='cientityDataIdToDelete'>
								<button id='cientityConfirmDelete' type="submit" class="btn btn-danger" data-dismiss="modal">YES!</button>
							</div>
						</div>
					</div>
				</div>
			</div>	
		
        </div> <!--main-wrapper end!-->
		<div class="sidebar-overlay" data-reff="#sidebar"></div>
		<script type="text/javascript" >
			var cientity_base_url = '<?php echo base_url();?>';
		</script>
        <?php incPage::footer_JS_CSS($footer_JS_CSS);?>		
    </body>
</html>