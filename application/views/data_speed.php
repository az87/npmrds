<div style="height: calc( 100vh - 110px );">
	<div class="container1" style="overflow:hidden;" id="content">
	<table>
		<tr>
			<td>
				<table style="width:100%">
					<tr>
						<td>Highway :
							<select id="highway" name="highway" style="width:100px;">
							<option value="" selected>All</option>
							<?php
							for($i=0;$i<count($highways);$i++)
								echo "<option value='".$highways[$i]["name"]."'>".$highways[$i]["name"]."</option>";
							?>
							</select>
						</td>
						<td>Date :
							>= <input type="date" id="from" name="from"> <= <input type="date" id="to" name="to">
						</td>
						<td>Epoch :
							<select id="epoch" name="epoch" style="width:100px;">
								<option value="" selected>All</option>
								<?php
								for($i=0;$i<=287;$i++)
								{
								?>
								<option value="<?php echo $i;?>"><?php echo $i;?></option>
							 	<?php
								}?>
							</select>
						</td>
						<td>Segment :
							<input type="text" id="segment" name="segment" style="width:100px">
						</td>
					</tr>
					<tr><td style="height:10px"></td></tr>
					<tr>
						<td colspan="4">Limit Display:
							<input type="number" id="limit" name="limit" value="100" min="1" max="100000"> Between ( 1-100000 )
						</td>
					</tr>
					<tr><td style="height:10px"></td></tr>
					<tr>
						<td colspan="5">
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
				label: 'Highway',
				name: 'highway',
				width: 40
			},
			{
				label: 'DATE',
				name: 'datee',
				width: 40,
				formatter: 'date', 
				formatoptions: { srcformat: 'Y-m-d', newformat: 'm/d/Y'}
			},
			{
				label: 'EPOCH',
				name: 'epoch',
				width: 40
			},
			{
				label: 'SEGMENT',
				name: 'segment',
				width: 40
			},
			{
				label: 'Freight',
				name: 'freight',
				width: 40
			},
			{
				label: 'Passenger',
				name: 'passenger',
				width: 40
			},
			{
				label: 'Total',
				name: 'total',
				width: 40
			},
			{
				label: 'RAW Freight',
				name: 'raw_freight',
				width: 40
			},
			{
				label: 'RAW Passenger',
				name: 'raw_passenger',
				width: 40
			},
			{
				label: 'RAW Total',
				name: 'raw_total',
				width: 40
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
		caption: "Speeds",
		pager: "#jqGridPager"
	});
	$("#jqGrid").navGrid("#jqGridPager",{ edit: false, add: false, del :  <?php echo ($role)?"true":"false";?>, search: false, refresh: true, view: true, align: "left" },
		// options for the Edit Dialog
		{},
		// options for the Add Dialog
		{},
		// options for the Delete Dailog
		{
			url: base_url+"data/delete_speeds",
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
				var csvContent = '"Highway","DATE","EPOCH","SEGMENT","Freight","Passenger","Total","RAW Freight","RAW Passenger","RAW Total"\n';
				for(i=0,n=filterdData.length;i<n;i++)
					csvContent += 	'"'+filterdData[i]['highway']
									+'","'+filterdData[i]['datee']
									+'","'+filterdData[i]['epoch']
									+'","'+filterdData[i]['segment']
									+'","'+filterdData[i]['freight']
									+'","'+filterdData[i]['passenger']
									+'","'+filterdData[i]['total']
									+'","'+filterdData[i]['raw_freight']
									+'","'+filterdData[i]['raw_passenger']
									+'","'+filterdData[i]['raw_total']
									+'"\n';
				var csvData = new Blob([csvContent],{type:'text/csv'});
				var link = document.createElement("a");
				link.setAttribute("href",URL.createObjectURL(csvData));
				link.setAttribute("download","Speed_File_"+filterdData[0]['highway']+".csv");
				link.click();
			}
			else
				alert('No Data to export!');
		}
	});
	$("#jqGrid").jqGrid('gridResize',{minWidth:500,minHeight:290});
	$("#jqGrid").jqGrid('setGridHeight', $("#content").height() - $("#jqGridPager").height()-150); // -70
	$( window ).resize(function() {$("#jqGrid").jqGrid('setGridWidth', $('#content').width());});
	$("#menu_toggle").on('click', function (e) {
		setTimeout(function(){$("#jqGrid").jqGrid('setGridWidth', $('#content').width());},500);
	});
});
function total_number()
{
	$.ajax({
		type: "POST",
		url: base_url+"data/speed_count", 
		data: {highway:$('#highway').val(),epoch:$('#epoch').val(),from:$('#from').val(),to:$('#to').val(),segment:$('#segment').val()},
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
		url: base_url+"data/speed_json", 
		data: {highway:$('#highway').val(),epoch:$('#epoch').val(),from:$('#from').val(),to:$('#to').val(),segment:$('#segment').val(),limit:$('#limit').val()},
		dataType: "text",  
		cache:false,
		success: function (result) {
			var result = JSON.parse(result);
			for (var i = 0; i < result.length; i++) {
				var item = result[i];
				gridArrayData.push({
					id: item.id,
					highway: item.highway,
					datee: item.datee,
					epoch: item.epoch,
					segment: item.segment,
					freight: item.freight,
					passenger: item.passenger,
					total: item.total,
					raw_freight: item.raw_freight,
					raw_passenger: item.raw_passenger,
					raw_total: item.raw_total
				});                            
			}
			$("#jqGrid").jqGrid('setGridParam', { data: gridArrayData},true);
			$("#jqGrid")[0].grid.endReq();
			$("#jqGrid").trigger('reloadGrid');
		}
	});
}
</script>