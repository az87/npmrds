<div style="height: calc( 100vh - 110px );">
	<div class="container1" style="overflow:hidden;" id="content">
	<table>
		<tr>
			<td>
				<table style="width:100%">
					<tr>
						<td>Year :
							<select id="years" name="years" style="width:100px;">
								<option value="" selected>All</option>
								<?php
								$date = date('Y');
								for($i=$date-5;$i<=$date;$i++)
									echo '<option value="'.$i.'">'.$i.'</option>';
								?>
							</select>
						</td>
						<td>Quarter :
							<select id="quarter" name="quarter" style="width:100px;">
								<option value="" selected>All</option>
								<option value="1">Quarter 1</option>
								<option value="2">Quarter 2</option>
								<option value="3">Quarter 3</option>
								<option value="4">Quarter 4</option>
							</select>
						</td>
						<td>Limit Display:
							<input type="number" id="limit" name="limit" value="100" min="1" max="100000"> Between ( 1-100000 )
						</td>
					</tr>
					<tr><td style="height:10px"></td></tr>
					<tr>
						<td colspan="4">
							<input type="button" onclick="fetchGridData();total_number();" value="Search"> <span id="total_result"></span>
						</td>
					</tr>
					<tr><td style="height:10px"></td></tr>
				</table> 
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top;">
				<table id="jqGrid"></table>
				<div id="jqGridPager"></div>
			</td>
		</tr>
	</table>
	</div>	
</div>
<script>
$(function () {
	$("#jqGrid").jqGrid({
		colModel: [
			{
				label: 'ID',
				name: 'id',
				width: 50,
				hidden: true,
				key : true
			},
			{
				label: 'Year',
				name: 'years',
				width: 40
			},
			{
				label: 'Quarter',
				name: 'quarter',
				width: 40
			},
			{
				label: 'TMC',
				name: 'tmc',
				width: 50
			},
			{
				label: 'ADMIN LEVEL 1',
				name: 'admin_level_1',
				width: 75
			},
			{
				label: 'ADMIN LEVEL 2',
				name: 'admin_level_2',
				width: 75
			},
			{
				label: 'ADMIN LEVEL 3',
				name: 'admin_level_3',
				width: 75
			},
			{
				label: 'DISTANCE',
				name: 'distance',
				width: 50
			},
			{
				label: 'ROAD NUMBER',
				name: 'road_number',
				width: 75
			},
			{
				label: 'ROAD NAME',
				name: 'road_name',
				width: 75
			},
			{
				label: 'LATITUDE',
				name: 'latitude',
				width: 50
			},
			{
				label: 'LONGITUDE',
				name: 'longitude',
				width: 50
			},
			{
				label: 'ROAD DIRECTION',
				name: 'road_direction',
				width: 75
			}
		],
		altRows: true,
		editurl: 'clientArray',
		sortname: '',
		sortorder : 'asc',
		sortable: true,
		viewrecords: true,
		width: $('#content').width(),
		rowNum: 100,
		rowList : [100,1000,10000],
		rownumbers: true,
		rownumWidth: 40,
		multiselect: true,
		datatype: 'local',
		caption: "Segments",
		pager: "#jqGridPager"
	});
	$("#jqGrid").navGrid("#jqGridPager",{ edit: false, add: false, del :  <?php echo ($role)?"true":"false";?>, search: false, refresh: true, view: true, align: "left" },
		// options for the Edit Dialog
		{},
		// options for the Add Dialog
		{},
		// options for the Delete Dailog
		{
			url: base_url+"data/delete_segments",
			onclickSubmit: function (options, ids) {
				return {ids: ids}
			},
			errorTextFormat: function (data) {
				return 'Error: ' + data.responseText
			}
		}
	);
	$('#jqGrid').navButtonAdd('#jqGridPager',
	{
		id : "export_csv_segment",
		buttonicon: "ui-icon-arrowthickstop-1-s",
		title: "Export CSV File",
		caption: "CSV",
		position: "last",
		onClickButton: function(){
			var filterdData = $("#jqGrid")[0].p.data;
			if(filterdData.length>0)
			{
				var csvContent = '"YEAR","Quarter","TMC","ADMIN_LEVEL_1","ADMIN_LEVEL_2","ADMIN_LEVEL_3","DISTANCE","ROAD_NUMBER","ROAD_NAME","LATITUDE","LONGITUDE","ROAD_DIRECTION"\n';
				for(i=0,n=filterdData.length;i<n;i++)
					csvContent += 	'"'+filterdData[i]['years']
									+'","'+filterdData[i]['quarter']
									+'","'+filterdData[i]['tmc']
									+'","'+filterdData[i]['admin_level_1']
									+'","'+filterdData[i]['admin_level_2']
									+'","'+filterdData[i]['admin_level_3']
									+'","'+filterdData[i]['distance']
									+'","'+filterdData[i]['road_number']
									+'","'+filterdData[i]['road_name']
									+'","'+filterdData[i]['latitude']
									+'","'+filterdData[i]['longitude']
									+'","'+filterdData[i]['road_direction']
									+'"\n';
				var csvData = new Blob([csvContent],{type:'text/csv'});
				var link = document.createElement("a");
				link.setAttribute("href",URL.createObjectURL(csvData));
				link.setAttribute("download","FHWA_Monthly_Static_File_"+filterdData[0]['years']+"Q"+filterdData[0]['quarter']+".csv");
				link.click();
			}
			else
				alert('No Data to export!');
		}
	});
	// $('#jqGrid').navButtonAdd('#jqGridPager',
	// {
		// id : "export_csv_segment_server",
		// buttonicon: "ui-icon-arrowthickstop-1-s",
		// title: "Export CSV File",
		// caption: "CSV",
		// position: "last",
		// onClickButton: function(){
			// $.ajax({
				// type: "POST",
				// url: base_url+"data/export_segment", 
				// data: {years:$('#years').val(),quarter:$('#quarter').val()},
				// dataType: "text",  
				// cache:false,
				// success: function (result) {
					// window.location = base_url+result;
				// },
				// beforeSend: function(){
					  // $("#loading_map").html("<img width='150px' height='150px' id='generate_waiting_loader' src='"+base_url+"images/loading.gif'>");
				// },	
				// complete: function(){
					// $("#generate_waiting_loader").remove();
				// },					
				// error: function(){}
			// });
		// }
	// });
	$("#jqGrid").jqGrid('gridResize',{minWidth:500,minHeight:290});
	$("#jqGrid").jqGrid('setGridHeight', $("#content").height() - $("#jqGridPager").height()-120); // -70
	$( window ).resize(function() {$("#jqGrid").jqGrid('setGridWidth', $('#content').width());});
	$("#menu_toggle").on('click', function (e) {
		setTimeout(function(){$("#jqGrid").jqGrid('setGridWidth', $('#content').width());},500);
	});
});
function total_number()
{
	$.ajax({
		type: "POST",
		url: base_url+"data/segment_count", 
		data: {years:$('#years').val(),quarter:$('#quarter').val()},
		dataType: "text",
		cache:false,
		success: function (result) {
			$('#total_result').html(result);
		}
	});
}
function fetchGridData() 
{
	var gridArrayData = [];
	$("#jqGrid")[0].grid.beginReq();
	$.ajax({
		type: "POST",
		url: base_url+"data/segment_json", 
		data: {years:$('#years').val(),quarter:$('#quarter').val(),limit:$('#limit').val()},
		dataType: "text",  
		cache:false,
		success: function (result) {
			var result = JSON.parse(result);
			for (var i = 0; i < result.length; i++) {
				var item = result[i];
				gridArrayData.push({
					id: item.id,
					years: item.years,
					quarter: item.quarter,
					tmc: item.tmc,
					admin_level_1: item.admin_level_1,
					admin_level_2: item.admin_level_2,
					admin_level_3: item.admin_level_3,
					road_number: item.road_number,
					road_name: item.road_name,
					road_direction: item.road_direction,
					latitude: item.latitude,
					longitude: item.longitude,
					distance: item.distance
				});                            
			}
			$("#jqGrid").jqGrid('setGridParam', { data: gridArrayData},true);
			$("#jqGrid")[0].grid.endReq();
			$("#jqGrid").trigger('reloadGrid');
		}
	});
}
</script>