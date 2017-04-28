<div style="margin-left:10px">
	<h3 class="title">User Name : <?php echo $user[0]['user_name'];?></h3>
	<hr />
	<table>
		<tr>
			<td style="width:200px">
				<label for="full_name">Full Name:</label>
			</td>
			<td>
				<input style="width:200px" class="form-control" type="text" name="full_name" id="full_name" value="<?php echo $user[0]['full_name'];?>"/>
			</td>
		</tr>
		<tr>
			<td style="width:200px">
				<label for="email">Email:</label>
			</td>
			<td>
				<input style="width:200px" class="form-control" type="text" name="email" id="email" value="<?php echo $user[0]['email'];?>"/>
			</td>
		</tr>
		<tr>
			<td style="width:200px">
				<label for="n_password">New Password:</label>
			</td>
			<td>
				<input style="width:200px" class="form-control" type="password" name="n_password" id="n_password" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="r_password">Repeat Password:</label>
			</td>
			<td>
				<input style="width:200px" class="form-control" type="password" name="r_password" id="r_password" />
			</td>
		</tr>
		<tr>
			<td>
				<button type="button" class="btn btn-info" id="btn_edit" onclick="validateInputs()">
					<span class="glyphicon glyphicon-ok"></span> Save
				</button>
			</td>	
		</tr>
	</table>
	<div id="status_message" style="display: none;width:400px"></div>
</div>
<script>
	function validateInputs()
	{
		if($('#n_password').val() == $('#r_password').val())
		{
			$.ajax({
				type: "POST",
				url: base_url+"user/save_settings", 
				data: {id:'<?php echo $user[0]['id'];?>',full_name:$('#full_name').val(),email:$('#email').val(), password:$('#n_password').val()},
				dataType: "text",  
				cache:false,
				success: 
				function(data){
					if(data)
					{
						$('#status_message').html('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#status_message\').hide()">&times;</button>Password has been saved successfully!</div>');
						$('#status_message').slideDown();
					}
				}	
			});
		}
		else
		{
			$('#status_message').html('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$(\'#status_message\').hide()">&times;</button>Password and repeat password fields must match!</div>');
			$('#status_message').slideDown();
		}
	}
</script>