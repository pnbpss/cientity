<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/img/favicon.png">
        <title>Login hrds- cientity demo</title>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
        <div class="main-wrapper">
			<div class="account-page">
				<div class="container">
					<h3 class="account-title">cientity demo</h3>
					<div class="account-box">
						<div class="account-wrapper">							
							<?php echo @$loginErrorMessage;?>
							<form id="bpsInterface_loginForm" action="<?php echo base_url()."user/login";?>" method='post'>
								<div class="form-group form-focus">
									<label class="control-label">user name</label>
									<input class="form-control floating inputField" name="Username" type="text">
								</div>
								<div class="form-group form-focus">
									<label class="control-label">password</label>
									<input class="form-control floating inputField" name="Password" type="password">
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary btn-block account-btn inputField"  type="submit">login</button>
								</div>								
							</form>
							
						</div>
					</div>
				</div>
			</div>
        </div>
		<div class="sidebar-overlay" data-reff="#sidebar"></div>
		
		<script type="text/javascript">
			var cientity_baseUrl="<?php echo base_url();?>";
		</script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/app.js"></script>
		<!--script type="text/javascript" src="<?php echo base_url();?>scripts/bpsInterface_userLogin.js"></script!-->
    </body>
</html>