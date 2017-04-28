<!DOCTYPE html>
<html lang="en">
<head>
    <title>NPMRDS</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo base_url();?>images/logo.png" type="image/png">

	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/ui.jqgrid.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/nprogress.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/green.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/jstree.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/daterangepicker.css">	
	<link rel="stylesheet" href="<?php echo base_url();?>css/smart_wizard.css">	
	<link rel="stylesheet" href="<?php echo base_url();?>css/smart_wizard_theme_arrows.css">	
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css">
	
	<script src="<?php echo base_url();?>js/jquery.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.form.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.jqGrid.min.js"></script>
	<script src="<?php echo base_url();?>js/grid.locale-en.js"></script>
	<script src="<?php echo base_url();?>js/bsAlerts.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.smartWizard.min.js"></script>

</head>
  
	
<script type="text/javascript">
	var base_url = '<?php echo base_url();?>';
</script>

<body class="nav-md footer_fixed">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col menu_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo base_url();?>" class="site_title"><i class="fa fa-paw"></i> <span>NPMRDS Tools</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <img src="<?php echo base_url();?>images/user.png" alt="User Image" class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $user['user_name'];?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url();?>main">Dashboard</a></li>
                      <li><a href="<?php echo base_url();?>main/dashboard1">Dashboard 1</a></li>
                      <li><a href="<?php echo base_url();?>main/dashboard2">Dashboard 2</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-users"></i> Manage User <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url();?>user">Users</a></li>
                      <li><a href="<?php echo base_url();?>user/profile">Profile</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-bar-chart-o"></i> Data Manipulation <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
						<li><a href="<?php echo base_url();?>data/data_import">Data import</a></li>
						<li><a href="<?php echo base_url();?>data/data_export">Data export</a></li>
						<li><a> Data view <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">
								<li><a href="<?php echo base_url();?>data/segment"> Segments </a></li>
								<li><a href="<?php echo base_url();?>data/speed"> Speeds </a></li>
							</ul>
						</li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url();?>highway">Highways</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true" onclick="openNav();"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url()?>login/logout">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
			  <div id="alert_message" data-alerts="alerts" data-ids="alert_message" style="z-index:100;position:fixed;top: 0%;left: 35%;width:30%;margin-top: 2px;"></div>
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo base_url();?>images/user.png" alt="User Image"><?php echo $user['user_name'];?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="<?php echo base_url()?>user/profile"> Profile</a></li>
                    <li><a href="<?php echo base_url()?>login/logout"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">0</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
		
		<!-- The overlay -->
		<div id="myNav" class="overlay">

		  <!-- Overlay content -->
		  <div class="overlay-content">
		  <!-- Automatic element centering -->
			<div class="lockscreen-wrapper">
			  <div class="lockscreen-logo">Lock Screen!</div>
			  <!-- User name -->
			  <div class="lockscreen-name"><?php echo $user['user_name'];?></div>

			  <!-- START LOCK SCREEN ITEM -->
			  <div class="lockscreen-item">
				<!-- lockscreen image -->
				<div class="lockscreen-image">
				  <img src="<?php echo base_url();?>images/user.png" alt="User Image">
				</div>
				<!-- /.lockscreen-image -->

				<!-- lockscreen credentials (contains the form) -->
				<form id="lockscreen" class="login-form lockscreen-credentials">
				  <div class="input-group" style="margin:0px;">
					<input type="hidden" id="user_name" name="user_name" class="form-control" value="<?php echo $user['user_name'];?>">
					<input type="password" id="password" name="password" class="form-control" placeholder="password" value="<?php echo $this->input->post('password')?>" required="required">
					<div class="input-group-btn">
					  <button type="button" class="btn" style="margin:0px;" onclick="closeNav()"><i class="fa fa-arrow-right text-muted"></i></button>
					</div>
				  </div>
				</form>

			  </div><!-- /.lockscreen-item -->
    		  <div style="font-size:16px;color:#AA0000;" id="error_message"></div>
			  <div class="help-block text-center">
				Enter your password to retrieve your session
			  </div>
			  <div class="text-center">
				<a href="<?php echo base_url();?>login/logout">Or sign in as a different user</a>
			  </div>
			</div><!-- /.center -->
		  </div>

		</div>

<script>
/* Open when someone clicks on the span element */
function openNav() {
	$.ajax({
		type: "POST",
		url:  base_url+"login/lock_screen",
		cache: false,
		data:{},		  				  		
		success: function(result){			
			document.getElementById("myNav").style.height = "100%";
		}
	});
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav() {
	$('#error_message').val('');
	$.ajax({
		type: "POST",
		url:  base_url+"login/open_screen",
		cache: false,
		data:{user_name: $('#user_name').val(),password: $('#password').val()},		  				  		
		success: function(result){
			if(result == 'done')
				document.getElementById("myNav").style.height = "0%";
			else
				$('#error_message').html(result);
		}
	});
}
</script>

<div id="loading_map" style="position: absolute;left: 50%;top: 50%;margin-top: -75px;margin-left: -75px;z-index:101;"></div>

        <!-- page content -->
        <div class="right_col" role="main">