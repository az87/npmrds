<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
        <h2>User Profile</h2>
		<ul class="nav navbar-right panel_toolbox">
		  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
		  <div class="profile_img">
			<div id="crop-avatar">
			  <!-- Current avatar -->
			  <img class="img-responsive avatar-view" src="<?php echo base_url();?>images/user.png" alt="User Image" title="User Image">
			</div>
		  </div>
		  <h3><?php echo $user['user_name'];?></h3>

		  <ul class="list-unstyled user_data">
			<li><i class="fa fa-map-marker user-profile-icon"></i> Tulsa, Oklahoma, USA
			</li>

			<li>
			  <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
			</li>
		  </ul>

		  <!-- start skills -->
		  <h4>Skills</h4>
		  <ul class="list-unstyled user_data">
			<li>
			  <p>Web Applications</p>
			  <div class="progress progress_sm">
				<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
			  </div>
			</li>
			<li>
			  <p>Website Design</p>
			  <div class="progress progress_sm">
				<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="70"></div>
			  </div>
			</li>
			<li>
			  <p>Automation & Testing</p>
			  <div class="progress progress_sm">
				<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="30"></div>
			  </div>
			</li>
			<li>
			  <p>Database Design</p>
			  <div class="progress progress_sm">
				<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
			  </div>
			</li>
		  </ul>
		  <!-- end of skills -->

		</div>
		<div class="col-md-9 col-sm-9 col-xs-12">
		  <div class="" role="tabpanel" data-example-id="togglable-tabs">
			<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
			  <li role="presentation" class="active"><a href="#settings" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Edit profile</a></li>
			  <li role="presentation"><a href="#passwords" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Change Password</a></li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="settings" role="tabpanel" aria-labelledby="home-tab">
					<form class="form-horizontal" action="<?php echo base_url()?>user/update_info" method="post">
					<div class="form-group">
						<label for="full_name" class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10">
						<input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name" value="<?php echo $user['full_name'];?>" required>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user['email'];?>" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
					</form>
				</div><!-- /.tab-pane -->
				<div class="tab-pane fade" id="passwords" role="tabpanel" aria-labelledby="profile-tab">
					<form class="form-horizontal" onsubmit="return validate_password()">
					<div class="form-group">
						<label for="old_password" class="col-sm-2 control-label">Old Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" required>
						</div>
					</div>
					<div class="form-group">
						<label for="n_password" class="col-sm-2 control-label">New Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="n_password" name="n_password" placeholder="New Password" required>
						</div>
					</div>
					<div class="form-group">
						<label for="r_password" class="col-sm-2 control-label">Repeat Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="r_password" name="r_password" placeholder="Repeat Password" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
					<div id="status_message" style="display: none;"></div>
					</form>
				</div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>

<script>
	function validate_password()
	{
		$.ajax({
			type: "POST",
			url: base_url+"user/validate_password", 
			data: {password:$('#old_password').val()},
			dataType: "text",  
			cache:false,
			success: 
			function(data){
				if(data)
				{
					if($('#n_password').val() === $('#r_password').val())
					{
						$.ajax({
							type: "POST",
							url: base_url+"user/change_password", 
							data: {password:$('#n_password').val()},
							dataType: "text",  
							cache:false,
							success: 
							function(data){
								if(data)
								{
									$('#status_message').html('<div class="alert alert-success alert-dismissable fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#status_message\').hide()" aria-label="Close"><span aria-hidden="true">&times;</span></button>New password has been saved successfully!</div>');
									$("#status_message").fadeTo(5000, 500).slideUp(500, function(){
										$("#status_message").alert('close');
									});
								}
							}	
						});
					}
					else
					{
						$('#status_message').html('<div class="alert alert-info alert-dismissable fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#status_message\').hide()" aria-label="Close"><span aria-hidden="true">&times;</span></button>Password and repeat password fields must match!</div>');
						$("#status_message").fadeTo(5000, 500).slideUp(500, function(){
							$("#status_message").alert('close');
						});
					}
				}
				else
				{
					$('#status_message').html('<div class="alert alert-danger alert-dismissable fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#status_message\').hide()" aria-label="Close"><span aria-hidden="true">&times;</span></button>The Old Password Not Correct! Please insert the correct password and try again.</div>');
					$("#status_message").fadeTo(5000, 500).slideUp(500, function(){
						$("#status_message").alert('close');
					});
				}
			}	
		});
		return false;
	}
</script>