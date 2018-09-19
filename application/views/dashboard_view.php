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
                   <a href="<?php echo base_url();?>m/dashboard" class="logo">
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
						<div class="col-md-12 col-sm-12 col-lg-6">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-opencart" aria-hidden="true"></i></span>
								<div class="dash-widget-info">
									<h3><?php incPage::display($openingClasses);?></h3>
									<span>Opening Classes/Training/Seminars are Opening</span>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-lg-6">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-users" aria-hidden="true"></i></span>
								<div class="dash-widget-info">
									<h3><?php incPage::display($employeeEnrolled);?></h3>
									<span>Employees Enrolled</span>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-lg-6">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-money" aria-hidden="true"></i></span>
								<div class="dash-widget-info">
									<h3><?php incPage::display($classesExpense);?></h3>
									<span>Classes/Training/Seminars Expenses</span>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-lg-6">
							<div class="dash-widget clearfix card-box">
								<span class="dash-widget-icon"><i class="fa fa-question"></i></span>
								<div class="dash-widget-info">
									<h3><?php incPage::display($quizzes);?></h3>
									<span>Quizzes</span>
								</div>
							</div>
						</div>
						
					</div>
					<!----
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-sm-6 text-center">
									<div class="card-box">
										<div id="area-chart" ></div>
									</div>
								</div>
								<div class="col-sm-6 text-center">
									<div class="card-box">
										<div id="line-chart"></div>
									</div>
								</div>
								<div  class="col-md-4 col-sm-12 text-center">
									<div class="card-box">
										<div id="bar-chart" ></div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12 text-center">
									<div class="card-box">
										<div id="stacked" ></div>
									</div>
								</div>
								<div class="col-md-4 col-sm-12 text-center">
									<div class="card-box">
										<div id="pie-chart" ></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					!-->
					<!--
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Invoices</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Invoice ID</th>
													<th>Client</th>
													<th>Due Date</th>
													<th>Total</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><a href="invoice-view.html">#INV-0001</a></td>
													<td>
														<h2><a href="#">Hazel Nutt</a></h2>
													</td>
													<td>8 Aug 2017</td>
													<td>$380</td>
													<td>
														<span class="label label-warning-border">Partially Paid</span>
													</td>
												</tr>
												<tr>
													<td><a href="invoice-view.html">#INV-0002</a></td>
													<td>
														<h2><a href="#">Paige Turner</a></h2>
													</td>
													<td>17 Sep 2017</td>
													<td>$500</td>
													<td>
														<span class="label label-success-border">Paid</span>
													</td>
												</tr>
												<tr>
													<td><a href="invoice-view.html">#INV-0003</a></td>
													<td>
														<h2><a href="#">Ben Dover</a></h2>
													</td>
													<td>30 Nov 2017</td>
													<td>$60</td>
													<td>
														<span class="label label-danger-border">Unpaid</span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="invoices.html" class="text-primary">View all invoices</a>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Payments</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">	
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Invoice ID</th>
													<th>Client</th>
													<th>Payment Type</th>
													<th>Paid Date</th>
													<th>Paid Amount</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><a href="invoice-view.html">#INV-0004</a></td>
													<td>
														<h2><a href="#">Barry Cuda</a></h2>
													</td>
													<td>Paypal</td>
													<td>11 Jun 2017</td>
													<td>$380</td>
												</tr>
												<tr>
													<td><a href="invoice-view.html">#INV-0005</a></td>
													<td>
														<h2><a href="#">Tressa Wexler</a></h2>
													</td>
													<td>Paypal</td>
													<td>21 Jul 2017</td>
													<td>$500</td>
												</tr>
												<tr>
													<td><a href="invoice-view.html">#INV-0006</a></td>
													<td>
														<h2><a href="#">Ruby Bartlett</a></h2>
													</td>
													<td>Paypal</td>
													<td>28 Aug 2017</td>
													<td>$60</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="payments.html" class="text-primary">View all payments</a>
								</div>
							</div>
						</div>
					</div>
					!-->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Opening Classes/Training/Seminars</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped custom-table m-b-0">
											<?php incPage::display($listOfOpeningClasses);?>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="<?php incPage::display($viewAllClassesLink);?>" class="text-primary">View all Classes/Training/Seminars</a>
								</div>
							</div>
						</div>
						<!--
						<div class="col-md-6">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">Recent Projects</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th class="col-md-3">Project Name </th>
													<th class="col-md-3">Progress</th>
													<th class="text-right col-md-1">Action</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<h2><a href="project-view.html">Food and Drinks</a></h2>
														<small class="block text-ellipsis">
															<span class="text-xs">1</span> <span class="text-muted">open tasks, </span>
															<span class="text-xs">9</span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="65%" style="width: 65%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="#" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<h2><a href="project-view.html">School Guru</a></h2>
														<small class="block text-ellipsis">
															<span class="text-xs">2</span> <span class="text-muted">open tasks, </span>
															<span class="text-xs">5</span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="15%" style="width: 15%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="#" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<h2><a href="project-view.html">Penabook</a></h2>
														<small class="block text-ellipsis">
															<span class="text-xs">3</span> <span class="text-muted">open tasks, </span>
															<span class="text-xs">3</span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="49%" style="width: 49%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="#" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<h2><a href="project-view.html">Harvey Clinic</a></h2>
														<small class="block text-ellipsis">
															<span class="text-xs">12</span> <span class="text-muted">open tasks, </span>
															<span class="text-xs">4</span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="88%" style="width: 88%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="#" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												<tr>
													<td>
														<h2><a href="project-view.html">The Gigs</a></h2>
														<small class="block text-ellipsis">
															<span class="text-xs">7</span> <span class="text-muted">open tasks, </span>
															<span class="text-xs">14</span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="100%" style="width: 100%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="#" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="projects.html" class="text-primary">View all projects</a>
								</div>
							</div>
						</div>
						!-->
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