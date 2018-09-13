<?php
require_once APPPATH."views/incPage.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/img/favicon.png">
        <title>Dashboard - HRMS admin template</title>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/assets/plugins/morris/morris.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
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
						<?php incPage::displayLeftMenus($menus);?>
					</div>
                </div>
            </div>
            <div class="page-wrapper">
                <div class="content container-fluid">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-lg-12">
							<div class="card-box project-box">								
								<h4 class="lead"><i>Introduction to CI-Entity</i></h4>
								<p style="font-size:13px;color:darkred;">
									This web site created to demonstrated CI-Entity by using Human Resources Development System(HRDS) as case study,<i>This means it is not exactly HRDS</i>. 
								</p>
								<p style="font-size:13px;text-indent: 50px;">
									CI-Entity is CodeIgniter extension that help developers automatically create the interfaces of entities, tables or views,  in their projects. The interfaces are common entity interfaces such as insertion, updating, 
									deletion and searching. Just create table in database and a few line of code, entity recipes, CI-Entity will take care of the rest.
								</p>
								<p style="font-size:13px;text-indent: 50px;">
									CI-Entity also create validations rules for each entity's attributes. For instance, a column which have decimal datatype and not null will be construct validation rules as "required|decimal" automatically. However, If the additional validations is required, CI-Entity allow to add it in additionalValidation Class, this is described in <a href="/manuals/">CI-Entity's manuals.</a>
								</p>	
								<table>
									<thead><th>Pros</th><th>Cons</th></thead>
									<tbody>
										<tr style='vertical-align: top;'><td>
												<ul>													
													<li>Entity interface is easily to construct, just create table or view and a few lines of code.</li>
													<li>Easily creating searching conditions input group, filter-row, for each entity.</li>
													<li>Easily creating select2 component if a column is foreign key.</li>
													<li>Automatically create validations rules for each attributes</li>
													<li>If a table structure changed, column was added or deleted, CI-Entity will be changed interface automatically.</li>
												</ul>
											</td>
											<td>
												<ul>
													<li>Only supports MS SQL Server.</li>								
												</ul>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="pro-deadline m-b-15">
									<div class="sub-title">
										last update:
									</div>
									<div class="text-muted">
										14 Sep 2018
									</div>
								</div>								
							</div>
						</div>
					</div>
					
				</div>							
			</div>			
			<?php incPage::pageloaderModal();?>
        </div>
		<div class="sidebar-overlay" data-reff="#sidebar"></div>
		<script type="text/javascript">
			var cientity_base_url = '<?php echo base_url();?>';
		</script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.slimscroll.js"></script>		
		<script type="text/javascript" src="<?php echo base_url();?>assets/js/app.js"></script>		
    </body>
</html>