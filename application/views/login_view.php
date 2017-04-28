<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NPMRDS Login! </title>

	<link rel="icon" href="<?php echo base_url();?>images/logo.png" type="image/png">

	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/nprogress.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/animate.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/custom.min.css">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
			<?php echo form_open('login/login_validation',array('class' => 'login-form', 'role' => 'form', 'id' => 'signin_form', 'method' => 'post'));?>
			<h1>Login Form</h1>
			<div>
			<input type="text" name="user_name" class="form-control" placeholder="Username" required="required" value="<?php echo $this->input->post('user_name')?>" />
			</div>
			<div>
			<input type="password" name="password" class="form-control" placeholder="Password" required="required" value="<?php echo $this->input->post('password')?>" />
			</div>
			<div>
			<button type="submit" class="btn">Sign in!</button>
			</div>
			<div style="font-size:16px;color:#AA0000;"><?php echo validation_errors();?></div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> NPMRDS Tools!</h1>
                  <p>Copyright &copy; <?php echo date('Y');?> ODOT. All rights reserved.</p>
                </div>
              </div>
			<?php echo form_close();?>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
			<?php echo form_open('login/register_validation',array('class' => 'login-form', 'role' => 'form', 'id' => 'signin_form', 'method' => 'post'));?>
              <h1>Create Account</h1>
              <div>
                <input type="text" name="full_name" class="form-control" placeholder="Fullname" required="required" />
              </div>
              <div>
                <input type="text" name="user_name" class="form-control" placeholder="Username" required="required" />
              </div>
              <div>
                <input type="email" name="email" class="form-control" placeholder="Email" required="required" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="required" />
              </div>
              <div>
				 <button type="submit" class="btn">Sign up!</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Sign in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> NPMRDS Tools!</h1>
                  <p>Copyright &copy; <?php echo date('Y');?> ODOT. All rights reserved.</p>
                </div>
              </div>
			<?php echo form_close();?>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
