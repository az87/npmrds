<style>
.prog
{
	display:none; 
	position:relative; 
	width:100%; 
	border: 1px solid #ddd; 
	padding: 1px; 
	border-radius: 3px;
}
.bar 
{ 
	background-color: #B4F5B4; 
	width:0%; 
	height:20px; 
	border-radius: 3px; 
}
.perc 
{ 
	position:absolute; 
	display:inline-block;
	top:3px; 
	left:45%;
}
.progress {
    display: block;
    text-align: center;
    width: 0;
    height: 3px;
    background: red;
    transition: width .3s;
}
.progress.hide {
    opacity: 0;
    transition: opacity 1.3s;
}
</style>
<div style="height: calc( 100vh - 110px );">
	<div class="container1" style="overflow:auto;" id="content">
		<div id="smartwizard">
			<ul>
				<li><a href="#step-1">Step 1<br /><small>Upload Data</small></a></li>
				<li><a href="#step-2">Step 2<br /><small>Configure Data</small></a></li>
				<li><a href="#step-3">Step 3<br /><small>Process Data</small></a></li>
			</ul>
		 
			<div>
				<div id="step-1" class="" style="height:250px;padding:20px;">
					<?php echo $error;?>
					<div>Please select the file to upload it to server before processing stage.</div><br>
					<form action="import" id="myForm" method="post" enctype="multipart/form-data">
					<input type="file" id="userfile" name="userfile" size="20" class="form-control"/>
					<input type="submit" id="upload" name='submit_image' value="Upload" onclick='upload_file();' disabled >
					<div class='prog' id="progress_div">
						<div class='bar' id='bar1'></div>
						<div class='perc' id='percent1'>0%</div>
					</div>
					<div id='output'></div>
					</form>
				</div>
				<div id="step-2" class="" style="height:250px;padding:20px;">
					<table style="width:100%;">						
						<tr>
							<td>File Name:</td>
							<td>
								<input type="text" id="name_file" name="name_file" class="form-control" style="width:450px" disabled>
							</td>
						</tr>
						<tr><td style="height:10px;"></td></tr>
						<tr>
							<td style="width:100px;vertical-align:top;">File Type:</td>
							<td>
								<table style="width:50%">
									<tr>
										<td>
											<input type="radio" name="file_type" value="S" checked>Static
										</td>
										<td>
											<select id="years" name="years" style="width:100px;">
											<?php
											$date = date('Y');
											for($i=$date-5;$i<$date;$i++)
												echo '<option value="'.$i.'">'.$i.'</option>';
											echo '<option value="'.$date.'" selected>'.$date.'</option>';
											?>
											</select>
										</td>
										<td><?php
											$month = (int) date('n');
											$Q1='';$Q2='';$Q3='';$Q4='';
											if($month>= 1 && $month <= 3)
												$Q1 = "selected";
											else if($month>= 4 && $month <= 6)
												$Q2 = "selected";
											else if($month>= 7 && $month <= 9)
												$Q3 = "selected";
											else if($month>= 10 && $month <= 12)
												$Q4 = "selected";
											?>
											<select id="quarter" name="quarter" style="width:100px;">
												<option value="1" <?php echo $Q1;?>>Quarter 1</option>
												<option value="2" <?php echo $Q2;?>>Quarter 2</option>
												<option value="3" <?php echo $Q3;?>>Quarter 3</option>
												<option value="4" <?php echo $Q4;?>>Quarter 4</option>
											</select>										
										</td>
									</tr>
									<tr>
										<td>
											<input type="radio" name="file_type" value="RF"> Freight Raw
										</td>
										<td>
											<input type="radio" name="file_type" value="RP"> Passenger Raw
										</td>
										<td>
											<input type="radio" name="file_type" value="RT"> Total Raw
										</td>
									</tr>
									<tr>
										<td>
											<input type="radio" name="file_type" value="FN"> Freight N
										</td>
										<td>
											<input type="radio" name="file_type" value="PN"> Passenger N
										</td>
										<td>
											<input type="radio" name="file_type" value="TN"> Total N
										</td>
									</tr>
									<tr>
										<td>
											<input type="radio" name="file_type" value="FP"> Freight P
										</td>
										<td>
											<input type="radio" name="file_type" value="PP"> Passenger P
										</td>
										<td>
											<input type="radio" name="file_type" value="TP"> Total P
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td style="height:10px;"></td></tr>
						<tr id="highway_tr" style="display:none">
							<td style="width:100px">Highway Name:</td>
							<td>
								<select id="highway" name="highway" class="form-control" style="width:450px;">
								<?php
								for($i=0;$i<count($highways);$i++)
									echo "<option value='".$highways[$i]["name"]."'>".$highways[$i]["name"]."</option>";
								?>
								</select>
							</td>
						</tr>
						<tr><td style="height:10px;"></td></tr>
						<tr>
							<td colspan="2">
								<input type="button" class="btn btn-info" class="form-control" value="Next" onclick="final_page()">
							</td>
						</tr>
						<tr><td style="height:20px;"></td></tr>
					</table>
				</div>
				<div id="step-3" class="" style="height:250px;padding:20px;">
					<table>
						<tr><td>File Name : <span id="file_span"></span></td></tr>
						<tr><td style="height:10px;"></td></tr>
						<tr><td>File Type : <span id="type_span"></span></td></tr>
						<tr><td style="height:10px;"></td></tr>
						<tr id="static_tr" style="display:none;"><td>Year : <span id="years_span"></span> ( <span id="quarter_span"></span> ) </td></tr>
						<tr id="other_tr" style="display:none;"><td>Highway Name : <span id="highway_span"></span></td></tr>
						<tr><td style="height:10px;"></td></tr>
					</table>
					<input type="button" id="process_button" class="btn btn-info" class="form-control" value="Process" onclick="process_start()">
					<div class='prog' id="progress_div2">
						<div class='bar' id='bar2'></div>
						<div class='perc' id='percent2'>0%</div>
					</div>
					<div id="process_info"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function upload_file() 
{
	var bar = $('#bar1');
	var perc = $('#percent1');
	$('#myForm').ajaxForm({
		beforeSubmit: function() {
			document.getElementById("progress_div").style.display="block";
			var percentVal = '0%';
			bar.width(percentVal);
			perc.html(percentVal);
		},

		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal);
			perc.html(percentVal);
		},

		success: function() {
			var percentVal = '100%';
			bar.width(percentVal);
			perc.html(percentVal);
			formmodified = 1;
		},

		complete: function(xhr) {
			if(xhr.responseText)
			{
				document.getElementById("output").innerHTML=xhr.responseText;
			}
		}
	}); 
}

function process_start()
{
	if($('#file_name').val() && $('#file_name').val() != '')
	{
		document.getElementById("progress_div2").style.display="block";
		var percentVal = 0 + '%';
		$('#bar2').width(percentVal);
		$('#percent2').html(percentVal);
		process_data(1);
	}
	else
		alert('File Name not exist!');
}
function process_data(from)
{
	$.ajax({
		type: "POST",
		url: base_url+"data/progress_data", 
		data: {file:$('#file_name').val(),file_type:$('input[name="file_type"]:checked').val(),highway:$('#highway').val(),years:$('#years').val(),quarter:$('#quarter').val(),from:from},
		dataType: "text",  
		cache:false,
		success: function(result){
			var result = JSON.parse(result);
			var percentVal = Math.round(((result.to-1)/result.total)*100) + '%';
			$('#bar2').width(percentVal);
			$('#percent2').html(percentVal);
			$('#process_info').html("The number of processed records is : <b>" + (result.to-1) +"</b> of <b>" + result.total + "</b>");
			if((result.to-1) < result.total)
			{
				process_data(result.to);
			}
			else
			{
				formmodified = 0;
				$("#generate_waiting_loader").remove();
				$('#smartwizard li.active').removeClass("active").addClass("done");
				// $(document).trigger("clear-alerts");
				// $(document).trigger("set-alert-id-alert_message", [{'message': "Process Data Finished Successfully",'priority': 'success'}]);
			}
		},
		beforeSend: function(){
			  $("#loading_map").html("<img width='150px' height='150px' id='generate_waiting_loader' src='"+base_url+"images/loading.gif'>");
		},	
		complete: function(){
		},					
		error: function(){}
	});
}

function next_page()
{
	$('#name_file').val($('#file_name').val());
	$('#smartwizard').smartWizard("next");
}

function final_page()
{
	if($('input[name="file_type"]:checked').val() == 'S')
	{
		$('#file_span').html($('#name_file').val());
		$('#type_span').html('Static');
		$('#years_span').html($('#years').val());
		
		switch ($('#quarter').val())
		{
			case '1':$('#quarter_span').html('Quarter 1');
				break;
			case '2':$('#quarter_span').html('Quarter 2');
				break;
			case '3':$('#quarter_span').html('Quarter 3');
				break;
			case '4':$('#quarter_span').html('Quarter 4');
				break;
		}
		$('#highway_span').html('');
		$('#static_tr').css('display','table-row');
		$('#other_tr').css('display','none');
		$('#smartwizard').smartWizard("next");
	}
	else if($('#highway').val() != '')
	{
		$('#file_span').html($('#name_file').val());		
		switch ($('input[name="file_type"]:checked').val())
		{
			case 'RF':$('#type_span').html('Freight Raw');
				break;
			case 'RP':$('#type_span').html('Passenger Raw');
				break;
			case 'RT':$('#type_span').html('Total Raw');
				break;
			case 'FN':$('#type_span').html('Freight N');
				break;
			case 'PN':$('#type_span').html('Passenger N');
				break;
			case 'TN':$('#type_span').html('Total N');
				break;
			case 'FP':$('#type_span').html('Freight P');
				break;
			case 'PP':$('#type_span').html('Passenger P');
				break;
			case 'TP':$('#type_span').html('Total P');
				break;
		}
		$('#years_span').html('');
		$('#quarter_span').html('');
		$('#highway_span').html($('#highway').val());
		$('#static_tr').css('display','none');
		$('#other_tr').css('display','table-row');
		$('#smartwizard').smartWizard("next");
	}
	else
		alert('Please Fill High Name');
}

$(document).ready(function() {

	formmodified=0;
	window.onbeforeunload = function (e) {
		e = e || window.event;
		if (formmodified == 1)
			return 'Sure?';
	};
	
	$('#smartwizard').smartWizard({
		theme : "arrows",
		keyNavigation:false,
		backButtonSupport: false,
		transitionEffect: 'fade',
		transitionSpeed: '1000',		
        toolbarSettings: {
            toolbarPosition: 'none',
            toolbarButtonPosition: 'none',
            showNextButton: false,
            showPreviousButton: false
        },
		anchorSettings: {
			anchorClickable: false,
			enableAllAnchors: false,
			markDoneStep: true,
			markAllPreviousStepsAsDone:true,
			enableAnchorOnDoneStep: false,
			removeDoneStepOnNavigateBack:true
		},
	});
	
	$('#userfile').change(function(){
		if ($(this).val())
			$('#upload').attr('disabled',false);
		else
			$('#upload').attr('disabled',true);
	});
	
	$('input[name="file_type"]').change(function(){
		if($('input[name="file_type"]:checked').val() == 'S')
		{
			$('#highway_tr').css('display','none');
			$('#years').css('display','table-row');
			$('#quarter').css('display','table-row');
		}
		else
		{
			$('#highway_tr').css('display','table-row');
			$('#years').css('display','none');
			$('#quarter').css('display','none');
		}
	});
	
	$('#process_button').click(function(){		
		var btn = $(this);
		btn.prop('disabled', true);
		setTimeout(function(){
			btn.prop('disabled', false);
		}, 3600000);
	});
});
</script>