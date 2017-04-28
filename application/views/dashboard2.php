<script type="text/javascript" src="<?php echo (empty($_SERVER['HTTPS']))?"http://":"https://";?>maps.googleapis.com/maps/api/js?key=AIzaSyAlYm7tmX16rn409Si0YK-c8lE5tUhIxCI&libraries=places"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/map.js"></script>

<div style="height: calc( 100vh - 180px );">
	<div class="container1" style="float: left;width:360px;margin-right:10px;">
		<div class="form-group">
			<label>Date range:</label>
			<div class="input-group">
				<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
				</div>
				<input type="text" class="form-control pull-right" id="reservationtime">
				<input type="hidden" id="start" name="start" value="">
				<input type="hidden" id="end" name="end" value="">
			</div>
		</div>
		</br>
		<div class="form-group">
			<table>
				<tr>
					<td style="text-align:center"><label><b>Source</b></label></td>
					<td style="text-align:center"><label><b>Destination</b></label></td>
				</tr>
				<tr>
					<td><input id="source1" type="text" class="form-control"><input id="s1" type="hidden"></td>
					<td><input id="destination1" type="text" class="form-control"><input id="d1" type="hidden"></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="container1" style="width:calc( 100% - 370px );overflow:auto;" id="content">
		<div id="map" style="width:100%;height: calc( 100vh - 170px );"></div>
	</div>	
</div>
<script>

var routes_color = ['#FF00FF','#00FF00','#00FFFF','#FFFF00','#0000FF',"green","blue","red","black"];

var markers = new Array();

var polylines = new Array();

var route_polylines  = [];

var infowindow = new google.maps.InfoWindow({maxWidth: 400});

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
	$(".select2_group").select2({
		placeholder: "Select NHS Road",
		allowClear: true
	}).on("change", function (e) { 
		//alert("change"); 
	});
	map = showMap(35.3097654, -98.7165585,7);
	
	var address = "State of Oklahoma";
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			map.fitBounds(results[0].geometry.viewport);
			bounds = results[0].geometry.bounds;
		} else {
			$(document).trigger("set-alert-id-alert_message", [{'message': "Geocode was not successful for the following reason: "+ status,'priority': 'info'}]);
		}
	});
	$('#start').val(moment(new Date()).subtract(4, 'hours').format("MM/DD/YYYY h:mm A"));
	$('#end').val(moment(new Date()).format("MM/DD/YYYY h:mm A"));
	
	function cb(start, end) {
        $('#reservationtime span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }
	$('#reservationtime').daterangepicker({
		locale: {
			format: 'MM/DD/YYYY h:mm A'
		},
		"dateLimit": {
			"days": 31
		},
		"startDate": $('#start').val(),
		"endDate": $('#end').val(),
		"minDate": "01/01/2000",
		"maxDate": "12/31/2020",
		"opens": "right",
		"timePicker24Hour": false,
		"showWeekNumbers": false,
		"alwaysShowCalendars": true,
		showDropdowns: true,
		timePicker: true,
		timePickerIncrement: 1, 
		ranges: {
           'Today': [moment().startOf('day'), moment().endOf('day')],
           'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
           'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
           'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
           'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
           'Last Month': [moment().subtract(1, 'month').startOf('month').startOf('day'), moment().subtract(1, 'month').endOf('month').endOf('day')]
        },
		cb
	},
	function(start, end, label) {
		$('#start').val(start.format('MM/DD/YYYY h:mm A'));
		$('#end').val(end.format('MM/DD/YYYY h:mm A'));
	});

	var nhs_roads = document.getElementById('nhs_roads');
	var source1 = document.getElementById('source1');
	var destination1 = document.getElementById('destination1');
	
	var autocomplete = new google.maps.places.Autocomplete(nhs_roads, {types: ["geocode"]});
	var autocomplete1 = new google.maps.places.Autocomplete(source1, {types: ["geocode"]});
	var autocomplete2 = new google.maps.places.Autocomplete(destination1, {types: ["geocode"]});
	
	autocomplete1.bindTo('bounds', map);
	autocomplete2.bindTo('bounds', map);
	
	google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
		var place = autocomplete.getPlace();
		console.log(place.geometry.location);
	});
	google.maps.event.addListener(autocomplete1, 'place_changed', function (event) {
		var place = autocomplete1.getPlace();
		marker1.setPosition(place.geometry.location);
		marker1.setMap(map);
		$("#s1").val(marker1.getPosition().lat()+','+marker1.getPosition().lng());		
		if($("#s1").val() != '' && $("#d1").val() != '')
			displayRoute($("#s1").val(),$("#d1").val(),'1');
	});
	google.maps.event.addListener(autocomplete2, 'place_changed', function (event) {
		var place = autocomplete2.getPlace();
		marker2.setPosition(place.geometry.location);
		marker2.setMap(map);
		$("#d1").val(marker2.getPosition().lat()+','+marker2.getPosition().lng());
		if($("#s1").val() != '' && $("#d1").val() != '')
			displayRoute($("#s1").val(),$("#d1").val(),'1');
	});
	
	$("#source1").keyup(function (e) {
		if($(this).val() == '')
		{
			marker1.setMap(null);
			$("#s1").val('');
			for (p in route_polylines[0])
			{
				route_polylines[0][p].setMap(null);
				route_polylines[0][p].setPath([]);					
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
						$("#source1").val(results[0].address_components[0].long_name);
						$("#s1").val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
					}
				}
			});
		}
	});
	$("#destination1").keyup(function (e) {
		if($("#destination1").val() == '')
		{
			marker2.setMap(null);
			$("#d1").val('');
			for (p in route_polylines[0])
			{
				route_polylines[0][p].setMap(null);
				route_polylines[0][p].setPath([]);					
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
						$("#destination1").val(results[0].address_components[0].long_name);
						$("#d1").val(results[0].geometry.location.lat()+','+results[0].geometry.location.lng());
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
					$("#source1").val(results[0].formatted_address);
					if($("#s1").val() != '' && $("#d1").val() != '')
						displayRoute($("#s1").val(),$("#d1").val(),'1');
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
					$("#destination1").val(results[0].formatted_address);
					if($("#s1").val() != '' && $("#d1").val() != '')
						displayRoute($("#s1").val(),$("#d1").val(),'1');
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
				case 'source1':					
					geocoder.geocode({'location': new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())}, function(results, status) {
						if (status === google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								marker1.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
								marker1.setMap(map);
								$("#s1").val(marker1.getPosition().lat()+','+marker1.getPosition().lng());
								$("#source1").val(results[0].formatted_address);
								if($("#s1").val() != '' && $("#d1").val() != '')
									displayRoute($("#s1").val(),$("#d1").val(),'1');
							}
						}
					});
					break;
				case 'destination1':
					geocoder.geocode({'location': new google.maps.LatLng(event.latLng.lat(), event.latLng.lng())}, function(results, status) {
						if (status === google.maps.GeocoderStatus.OK) {
							if (results[0]) {
								marker2.setPosition(new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()));
								$("#d1").val(marker2.getPosition().lat()+','+marker2.getPosition().lng());
								$("#destination1").val(results[0].formatted_address);
								if($("#s1").val() != '' && $("#d1").val() != '')
									displayRoute($("#s1").val(),$("#d1").val(),'1');
							}
						}
					});
					marker2.setMap(map);
					break;
			}
		}
	});
});
</script>