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
					<table style="width:100%;">						
						<tr>
							<td>File Name:</td>
							<td>
								<input type="text" id="file_name" name="file_name" class="form-control" style="width:450px" value="FHWA_Monthly_Static_File_2015Q4.csv">
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
function process_start()
{
	if($('#file_name').val() && $('#file_name').val() != '')
	{
		document.getElementById("progress_div2").style.display="block";
		var percentVal = 0 + '%';
		$('#bar2').width(percentVal);
		$('#percent2').html(percentVal);
		$('#process_info').html('');
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
				$("#generate_waiting_loader").remove();
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

$(document).ready(function() {
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
});
</script>