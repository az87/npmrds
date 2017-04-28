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
				<li><a href="#step-1">Step 1<br /><small>Select Data</small></a></li>
				<li><a href="#step-2">Step 2<br /><small>Export Data</small></a></li>
			</ul>
		 
			<div>
				<div id="step-1" class="" style="height:250px;padding:20px;">
					<h4>Please select type of data that you want to export?</h4>
					<hr>
					<table style="width:100%">
						<tr>
							<td>
								<input type="radio" name="file_type" value="S" checked>Static
							</td>
							<td></td>
							<td></td>
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
						<tr><td style="height:30px;"></td></tr>
						<tr>
							<td colspan="2">
								<input type="button" class="btn btn-info" class="form-control" value="Next" onclick="next_page()">
							</td>
						</tr>
					</table>
				</div>
				<div id="step-2" class="" style="height:250px;padding:20px;">
					<h4>Please configure which data would you want to exported?</h4>
					<hr>
					<table style="width:100%;">
						<tr>
							<td style="width:50%">File Type : <span id="type_span"></span></td>
							<td style="width:50%"></td>
						</tr>
						<tr><td style="height:10px;"></td></tr>
						<tr id="static_tr" style="display:none">
							<td style="width:50%">Year : 
								<select id="years" name="years" style="width:100px;">
								<?php
								$date = date('Y');
								for($i=$date-5;$i<$date;$i++)
									echo '<option value="'.$i.'">'.$i.'</option>';
								echo '<option value="'.$date.'" selected>'.$date.'</option>';
								?>
								</select>
							</td>
							<td style="width:50%"> Quarter : 
								<?php
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
						<tr id="highway_tr" style="display:none">
							<td style="width:50%">Highway Name : 
								<select id="highway" name="highway" style="width:150px;">
								<?php
								for($i=0;$i<count($highways);$i++)
									echo "<option value='".$highways[$i]["name"]."'>".$highways[$i]["name"]."</option>";
								?>
								</select>
							</td>
							<td style="width:50%">Date :
								>= <input type="date" id="from" name="from"> <= <input type="date" id="to" name="to">
							</td>
						</tr>
						<tr><td style="height:30px;"></td></tr>
						<tr>
							<td colspan="2">
								<input type="button" class="btn btn-info" class="form-control" value="Export" onclick="export_data()">
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

function next_page()
{
	switch ($('input[name="file_type"]:checked').val())
	{
		case 'S':$('#type_span').html('Static');
			break;
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
	if($('input[name="file_type"]:checked').val() == 'S')
	{
		$('#static_tr').css('display','table-row');
		$('#highway_tr').css('display','none');
	}
	else
	{
		$('#highway_tr').css('display','table-row');
		$('#static_tr').css('display','none');
	}
	formmodified = 1;
	$('#smartwizard').smartWizard('next');
}
function export_data()
{
	$.ajax({
		type: "POST",
		url: base_url+"data/export_data", 
		data: {file_type:$('input[name="file_type"]:checked').val(),highway:$('#highway').val(),years:$('#years').val(),quarter:$('#quarter').val(),from:$('#from').val(),to:$('#to').val()},
		dataType: "text",  
		cache:false,
		success: function(result){// console.log(result);
			window.location = base_url+result;
			formmodified = 0;
			$("#generate_waiting_loader").remove();
			$('#smartwizard li.active').removeClass("active").addClass("done");
		},
		beforeSend: function(){
			  $("#loading_map").html("<img width='150px' height='150px' id='generate_waiting_loader' src='"+base_url+"images/loading.gif'>");
		},	
		complete: function(){
		},					
		error: function(){}
	});
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

	$('#process_button').click(function(){		
		var btn = $(this);
		btn.prop('disabled', true);
		setTimeout(function(){
			btn.prop('disabled', false);
		}, 60000);
	});
});
</script>