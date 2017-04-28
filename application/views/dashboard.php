<script type="text/javascript" src="<?php echo (empty($_SERVER['HTTPS']))?"http://":"https://";?>maps.googleapis.com/maps/api/js?key=AIzaSyAlYm7tmX16rn409Si0YK-c8lE5tUhIxCI&libraries=geometry,places"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/map.js"></script>
<style>
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #origin-input,
      #destination-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 260px;
      }

      #origin-input:focus,
      #destination-input:focus {
        border-color: #4d90fe;
      }
</style>
<div style="height: calc( 100vh - 180px );">
	<div class="container1" style="float: left;width:300px;margin-right:10px;" id="content">
		<input id="state" type="hidden">
		<input id="origin-input" class="controls" type="text" placeholder="Enter an origin location">
		<input id="s1" type="hidden"></td>
		<input id="destination-input" class="controls" type="text" placeholder="Enter a destination location">
		<input id="d1" type="hidden"></td>
		<input type="button" id="search" name="search" value="Search" class="btn btn-info" onclick="search_segment()" style="margin-left:110px;margin-top:15px;margin-bottom:15px;">
		<table id="jqGrid"></table>
		<div id="jqGridPager"></div>
	</div>
	<div class="container1" style="width:calc( 100% - 310px );overflow:auto;">
		<div id="map" style="width:100%;height: calc( 100vh - 170px );"></div>
	</div>	
</div>
<script>
var map;
var polylines = new Array();
var route_polylines  = [];
var routes_color = ['#FF00FF','#00FF00','#00FFFF','#FFFF00','#0000FF',"green","blue","red","black"];
var marker1 = new google.maps.Marker({
	draggable: true,
    animation: google.maps.Animation.DROP,
	icon: "http://maps.google.com/mapfiles/markerS.png"
});
var marker2 = new google.maps.Marker({
	draggable: true,
    animation: google.maps.Animation.DROP,
	icon: "http://maps.google.com/mapfiles/markerD.png"
});

$( document ).ready(function(){
	
	map = showMap(35.3097654, -98.7165585,7);
	var geocoder = new google.maps.Geocoder;
	var source1 = document.getElementById('origin-input');
	var destination1 = document.getElementById('destination-input');
	
	var autocomplete1 = new google.maps.places.Autocomplete(source1, {types: ["geocode"]});
	var autocomplete2 = new google.maps.places.Autocomplete(destination1, {types: ["geocode"]});
	
	autocomplete1.bindTo('bounds', map);
	autocomplete2.bindTo('bounds', map);
	
	google.maps.event.addListener(autocomplete1, 'place_changed', function (event) {
		var place = autocomplete1.getPlace();
		marker1.setPosition(place.geometry.location);
		marker1.setMap(map);
		$("#s1").val(marker1.getPosition().lat()+','+marker1.getPosition().lng());		
		if($("#s1").val() != '' && $("#d1").val() != '')
			displayRoute($("#s1").val(),$("#d1").val());
		$('#state').val(state_name(place));
	});
	google.maps.event.addListener(autocomplete2, 'place_changed', function (event) {
		var place = autocomplete2.getPlace();
		marker2.setPosition(place.geometry.location);
		marker2.setMap(map);
		$("#d1").val(marker2.getPosition().lat()+','+marker2.getPosition().lng());
		if($("#s1").val() != '' && $("#d1").val() != '')
			displayRoute($("#s1").val(),$("#d1").val());
		$('#state').val(state_name(place));
	});
	$("#origin-input").keyup(function (e) {
		if($(this).val() == '')
		{
			marker1.setMap(null);
			$("#s1").val('');
			for (p in route_polylines)
			{
				route_polylines[p].setMap(null);
				route_polylines[p].setPath([]);					
			}
		}
		if (e.which == 13) {
			return false;
			var firstResult = $(".pac-container .pac-item:first").text();
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({"address": firstResult}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if(results[0])
					{
						marker1.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
						$("#origin-input").val(results[0].address_components[0].long_name);
						$("#s1").val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
						$('#state').val(state_name(results[0]));
					}
				}
			});
		}
	});
	$("#destination-input").keyup(function (e) {
		if($(this).val() == '')
		{
			marker2.setMap(null);
			$("#d1").val('');
			for (p in route_polylines)
			{
				route_polylines[p].setMap(null);
				route_polylines[p].setPath([]);					
			}
		}
		if (e.which == 13) {
			return false;
			var firstResult = $(".pac-container .pac-item:first").text();
			var geocoder = new google.maps.Geocoder();
			geocoder.geocode({"address": firstResult}, function (results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if(results[0])
					{
						marker2.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
						$("#destination-input").val(results[0].address_components[0].long_name);
						$("#d1").val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
						$('#state').val(state_name(results[0]));
					}
				}
			});
		}
	});
	google.maps.event.addListener(marker1, 'dragend', function (event) {
		var latlng = new google.maps.LatLng( marker1.getPosition().lat(), marker1.getPosition().lng());
		geocoder.geocode({'location': latlng}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$("#s1").val(marker1.getPosition().lat()+','+marker1.getPosition().lng());
					$("#origin-input").val(results[0].formatted_address);
					if($("#s1").val() != '' && $("#d1").val() != '')
						displayRoute($("#s1").val(),$("#d1").val());
					$('#state').val(state_name(results[0]));
				}
			}
		});
	});
	google.maps.event.addListener(marker2, 'dragend', function (event) {
		var latlng = new google.maps.LatLng( marker2.getPosition().lat(), marker2.getPosition().lng());
		geocoder.geocode({'location': latlng}, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$("#d1").val(marker2.getPosition().lat()+','+marker2.getPosition().lng());
					$("#destination-input").val(results[0].formatted_address);
					if($("#s1").val() != '' && $("#d1").val() != '')
						displayRoute($("#s1").val(),$("#d1").val());
					$('#state').val(state_name(results[0]));
				}
			}
		});
	});
	
	// the variable to hold the previously focused element
    var prevFocus;
    $('input').focus(function() {
        prevFocus = $(this);
    });
	google.maps.event.addListener(map,'rightclick',function(event) {
		if (typeof prevFocus  !== "undefined") {
			switch($(prevFocus)[0].id)
			{
				case 'origin-input':					
					geocoder.geocode({'location': new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())}, function(results, status) {
						if (status === google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								marker1.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
								marker1.setMap(map);
								$("#s1").val(marker1.getPosition().lat()+','+marker1.getPosition().lng());
								$("#origin-input").val(results[0].formatted_address);
								if($("#s1").val() != '' && $("#d1").val() != '')
									displayRoute($("#s1").val(),$("#d1").val());
								$('#state').val(state_name(results[0]));
							}
						}
					});
					break;
				case 'destination-input':
					geocoder.geocode({'location': new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())}, function(results, status) {
						if (status === google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								marker2.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
								marker2.setMap(map);
								$("#d1").val(marker2.getPosition().lat()+','+marker2.getPosition().lng());
								$("#destination-input").val(results[0].formatted_address);
								if($("#s1").val() != '' && $("#d1").val() != '')
									displayRoute($("#s1").val(),$("#d1").val());
								$('#state').val(state_name(results[0]));
							}
						}
					});
					break;
			}
		}
	});
	
	$("#jqGrid").jqGrid({
		colModel: [
			{
				label: 'ID',
				name: 'id',
				hidden: true,
				key : true
			},
			{
				label: 'TMC',
				name: 'tmc',
				width: 50
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
			}
		],
		altRows: true,
		editurl: 'clientArray',
		sortname: '',
		sortorder : 'asc',
		sortable: true,
		viewrecords: true,
		width: 285,
		height: 250,
		multiselect: true,
		rownumbers: true,
		rownumWidth: 20,
		rowNum: 100,
		rowList : [100,1000,10000],
		datatype: 'local',
		caption: "Segments",
		pager: "#jqGridPager"
	});
	$("#jqGrid").navGrid("#jqGridPager",{ edit: false, add: false, del :  false, search: false, refresh: false, view: false, align: "left" },
		// options for the Edit Dialog
		{},
		// options for the Add Dialog
		{},
		// options for the Delete Dailog
		{}
	);
	$('#jqGrid').navButtonAdd('#jqGridPager',
	{
		id : "display_on_map",
		buttonicon: "ui-icon-pin-s",
		title: "Display on Map",
		caption: "",
		position: "last",
		onClickButton: function(){
			var selRowIds = $('#jqGrid').jqGrid("getGridParam", "selarrrow");
			var rowData;
			if(selRowIds.length > 0)
			{
				bounds = new google.maps.LatLngBounds();
				for (p in polylines)
					polylines[p].setMap(null);
				polylines = new Array();
				for (var i = 0; i < selRowIds.length; i++) {
				
					rowData = $('#jqGrid').jqGrid("getLocalRow", selRowIds[i]);
					
					var polyline = new google.maps.Polyline({
						map : map,
						path : [new google.maps.LatLng(rowData['latitude'],rowData['longitude']),new google.maps.LatLng(rowData['latitude'],rowData['longitude'])],
						geodesic: true,
						strokeColor: "green",
						strokeOpacity: 0.8,
						strokeWeight: 5
					});
					polyListener(polyline,rowData['tmc']);
					bounds.extend(new google.maps.LatLng(rowData['latitude'],rowData['longitude']));
					map.fitBounds(bounds);
					polylines.push(polyline);
				}
			}
			else
				alert('Please select records to display!');
		}
	});
	$("#jqGrid").jqGrid('setGridHeight', $("#content").height() - $("#jqGridPager").height()-210);
});

function polyListener(poly,segment)
{
	google.maps.event.addListener(poly, 'click', function(event) {
		infowindow.setContent(segment);
		infowindow.setPosition(event.latLng);
		infowindow.open(map);
	});
	google.maps.event.addListener(poly, 'mouseover', function(event) {
		poly.setOptions({strokeOpacity:1,strokeWeight:10});
	});
	google.maps.event.addListener(poly, 'mouseout', function(event) {
		poly.setOptions({strokeOpacity:0.8,strokeWeight:5});
	});
}
function search_segment()
{
	if($('#origin').val() != '' && $('#destination').val() != '' && $('#origin-input').val() != '' && $('#destination-input').val() != '')
	{
		var gridArrayData = [];
		$("#jqGrid")[0].grid.beginReq();
		$.ajax({
			type: "POST",
			url: base_url+"main/search_segment", 
			data: {origin:$('#s1').val(),destination:$('#d1').val(),state:$('#state').val()},
			dataType: "text",  
			cache:false,
			success: function(result){
				var result = JSON.parse(result);
				if(route_polylines.length>0)
				{
					for (var i = 0; i < result.length; i++) 
					{
						var item = result[i];
						for(r=0;r<route_polylines.length;r++)
						{
							if(google.maps.geometry.poly.isLocationOnEdge(new google.maps.LatLng(item.latitude,item.longitude),route_polylines[r],0.001))
							{
								gridArrayData.push({
									id: item.id,
									tmc: item.tmc,
									latitude: item.latitude,
									longitude: item.longitude
								});
								break;
							}
						}
					}
				}
				else
				{
					for (var i = 0; i < result.length; i++) 
					{
						var item = result[i];
						gridArrayData.push({
							id: item.id,
							tmc: item.tmc,
							latitude: item.latitude,
							longitude: item.longitude
						});
					}
				}
				$("#jqGrid").jqGrid('setGridParam', { data: gridArrayData},true);
				$("#jqGrid")[0].grid.endReq();
				$("#jqGrid").trigger('reloadGrid');
			},
			beforeSend: function(){
			},	
			complete: function(){
			},					
			error: function(){}
		});
	}
	else
		alert("Please enter origin and destination locations!");
}
function state_name(result)
{
	for(a=0;a<result.address_components.length;a++)
		if( result.address_components[a].types.indexOf("administrative_area_level_1") > -1)
			return result.address_components[a].long_name;
	return "";
}
</script>
