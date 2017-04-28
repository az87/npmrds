<script type="text/javascript" src="<?php echo (empty($_SERVER['HTTPS']))?"http://":"https://";?>maps.googleapis.com/maps/api/js?key=AIzaSyAlYm7tmX16rn409Si0YK-c8lE5tUhIxCI&libraries=places"></script>
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
        width: 210px;
      }

      #origin-input:focus,
      #destination-input:focus {
        border-color: #4d90fe;
      }
</style>
<div style="height: calc( 100vh - 180px );">
	<div class="container1" style="overflow:auto;" id="content">
		<input class="btn btn-success" type="number" id="num_markers" name="num_markers" value="10" min="1" max="999" title="Number of random markers between 1 and 999">
		<button id="gen_markers" type="button" class="btn btn-primary" onclick="gen_markers()">Generate Random markers</button>
		<span id="in_circle" name="in_circle"></span>
		<input id="origin-input" class="controls" type="text" placeholder="Enter an origin location">
		<input id="destination-input" class="controls" type="text" placeholder="Enter a destination location">
		<div id="map" style="width:100%;height: calc( 100vh - 170px );"></div>
	</div>	
</div>
<script>

var polylineOptionsActual = new google.maps.Polyline({
    strokeColor: '#0000FF',
    strokeOpacity: 1.0,
    strokeWeight: 3
    });
var directionsService = new google.maps.DirectionsService;
var	directionsDisplay = new google.maps.DirectionsRenderer({draggable: true,polylineOptions: polylineOptionsActual});
var originInput = document.getElementById('origin-input');
var destinationInput = document.getElementById('destination-input');

function AutocompleteDirectionsHandler(map) {
	this.map = map;
	this.originPlaceId = null;
	this.destinationPlaceId = null;
	this.travelMode = 'DRIVING';
	directionsDisplay.setMap(map);
	directionsDisplay.addListener('directions_changed', function() {
      var addresses = directionsDisplay.getDirections().routes[0].legs[0];
      document.getElementById('origin-input').value = addresses.start_address;
      document.getElementById('destination-input').value = addresses.end_address;
    });

	var originAutocomplete = new google.maps.places.Autocomplete(originInput, {placeIdOnly: true});
	var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput, {placeIdOnly: true});

	this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
	this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

	this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
	this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
}

AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
	var me = this;
	autocomplete.bindTo('bounds', this.map);
	autocomplete.addListener('place_changed', function() {
	  var place = autocomplete.getPlace();
	  if (!place.place_id) {
		window.alert("Please select an option from the dropdown list.");
		return;
	  }
	  if (mode === 'ORIG') {
		me.originPlaceId = place.place_id;
	  } else {
		me.destinationPlaceId = place.place_id;
	  }
	  me.route();
	});

};

AutocompleteDirectionsHandler.prototype.route = function() {
	if (!this.originPlaceId || !this.destinationPlaceId) {
	  return;
	}
	directionsService.route({
	  origin: {'placeId': this.originPlaceId},
	  destination: {'placeId': this.destinationPlaceId},
	  travelMode: this.travelMode
	}, function(response, status) {
	  if (status === 'OK') {
		directionsDisplay.setDirections(response);
	  } else {
		window.alert('Directions request failed due to ' + status);
	  }
	});
};

function LongPress(map, length) {
    this.length_ = length;
    var me = this;
    me.map_ = map;
    me.timeoutId_ = null;
    google.maps.event.addListener(map, 'mousedown', function (e) {
        me.onMouseDown_(e);
    });
    google.maps.event.addListener(map, 'mouseup', function (e) {
        me.onMouseUp_(e);
    });
    google.maps.event.addListener(map, 'drag', function (e) {
        me.onMapDrag_(e);
    });
};
LongPress.prototype.onMouseUp_ = function (e) {
    if (shiftPressed) {
		
	}
	else
	{
		clearTimeout(this.timeoutId_);
	}
};
LongPress.prototype.onMouseDown_ = function (e) {
	if (shiftPressed) {
		
	}
	else
	{
		clearTimeout(this.timeoutId_);
		var map = this.map_;
		var event = e;
		this.timeoutId_ = setTimeout(function () {
			google.maps.event.trigger(map, 'longpress', event);
		}, this.length_);
	}
};
LongPress.prototype.onMapDrag_ = function (e) {
	if (shiftPressed) {
		
	}
	else
	{
		clearTimeout(this.timeoutId_);
	}
};
var map;
var circle = new google.maps.Circle();
// var fusionLayer;
var selected_markers = [];

var ctrlPressed = false;
function cacheIt(event) {
    ctrlPressed = event.ctrlKey;
}
document.onkeydown = cacheIt;
document.onkeyup = cacheIt;

// Start drag rectangle to select markers !!!!!!!!!!!!!!!!
var shiftPressed = false;

$(window).keydown(function (evt) {
	if (evt.which === 16) { // shift
		shiftPressed = true;
	}
}).keyup(function (evt) {
	if (evt.which === 16) { // shift
		shiftPressed = false;
	}
});

var mouseDownPos, gribBoundingBox = null,
	mouseIsDown = 0;
	
$( document ).ready(function(){
	map = showMap(35.3097654, -98.7165585,7);
	// var fusionTableId = "1iZwhR3CRRd-oH_Jme1qNK_HSYH2HlOeyawTDug";
	// fusionLayer = new google.maps.FusionTablesLayer({
		// suppressInfoWindows:true,
		// map: map,
		// query: {
		  // from: fusionTableId,
		  // select: "geometry"
		// },
		// styles: [{
			// polygonOptions: {
				// strokeWeight: 1,
				// strokeColor: "#AAAAAA",
				// strokeOpacity: 0.5,
				// fillColor: "#FFFFFF",
				// fillOpacity: 0.01
			// }
		// }]
	// });
	// fusionLayer.setMap(map);
	
	new AutocompleteDirectionsHandler(map);
	new LongPress(map, 500);
    var t;
    google.maps.event.addListener(map, 'longpress', function (e) {
        var radius = 10000;
		circle.setMap(null);
		for(var i=0;i<selected_markers.length;i++)
		{
			selected_markers[i].setIcon('https://www.google.com/mapfiles/marker.png');
		}
		selected_markers = [];
        // Draw a circle around the radius
        circle = new google.maps.Circle({
            center: e.latLng,
            radius: radius,
			fillOpacity: 0.15,
			strokeWeight: 0.9,
			clickable: false
        });
        circle.setMap(map);
        var start = 2;
        var speedup = 4;
        var grow = function () {
            radius = radius + 100;
            circle.setRadius(radius);
            t = setTimeout(grow, start);
            start = start / speedup;
        }
        grow();
    });

    google.maps.event.addListener(map, 'mousedown', function (e) {
        if (shiftPressed) {
            mouseIsDown = 1;
            mouseDownPos = e.latLng;
            map.setOptions({
                draggable: false
            });
        }
		gribBoundingBox.setMap(null);
		gribBoundingBox = null;
		circle.setMap(null);
    });

    google.maps.event.addListener(map, 'mousemove', function (e) {
        if (mouseIsDown && shiftPressed) {
            if (gribBoundingBox !== null) // box exists
            {
                bounds.extend(e.latLng);                
                gribBoundingBox.setBounds(bounds); // If this statement is enabled, I lose mouseUp events

            } else // create bounding box
            {
                bounds = new google.maps.LatLngBounds();
                bounds.extend(e.latLng);
                gribBoundingBox = new google.maps.Rectangle({
                    map: map,
                    bounds: bounds,
                    fillOpacity: 0.15,
                    strokeWeight: 0.9,
                    clickable: false
                });
            }
        }
    });

    google.maps.event.addListener(map, 'mouseup', function (e) {
		if (mouseIsDown && shiftPressed) {
            mouseIsDown = 0;
            if (gribBoundingBox !== null) // box exists
            {
                var boundsSelectionArea = new google.maps.LatLngBounds(gribBoundingBox.getBounds().getSouthWest(), gribBoundingBox.getBounds().getNorthEast());
                
				circle.setMap(null);
				for(var i=0;i<selected_markers.length;i++)
				{
					selected_markers[i].setIcon('https://www.google.com/mapfiles/marker.png');
				}
				selected_markers = [];
                for (var key in arrMarkers) {
                    if (gribBoundingBox.getBounds().contains(arrMarkers[key].getPosition())) 
                    {
						selected_markers.push(arrMarkers[key]);
                        arrMarkers[key].setIcon('https://www.google.com/mapfiles/marker_green.png');
                    } else {
						
                    }
                }
            }

			map.setOptions({
				draggable: true
			});
			count_selected_markers();
        }
		else
		{
			clearTimeout(t);
			 if(t>0)
			 {
				for (var i = 0; i < arrMarkers.length; i++) {
					if (google.maps.geometry.spherical.computeDistanceBetween(arrMarkers[i].getPosition(), circle.getCenter()) <= circle.getRadius())
					{
						selected_markers.push(arrMarkers[i]);
						arrMarkers[i].setIcon('https://www.google.com/mapfiles/marker_green.png');
					}
				}
				count_selected_markers();
			 }
			 t=0;
		}
    });
});

var arrMarkers=new Array(0);

var bounds;

function gen_markers()
{
	if (arrMarkers) 
	{
		for (i in arrMarkers) 
		{
			arrMarkers[i].setMap(null)
		}
	}

	arrMarkers=new Array(0);

	var num=$('#num_markers').val();

	plotrandom(num);
}

function plotrandom(number)
{
	bounds = map.getBounds();

	var southWest = bounds.getSouthWest();

	var northEast = bounds.getNorthEast();

	var lngSpan = northEast.lng() - southWest.lng();

	var latSpan = northEast.lat() - southWest.lat();

	pointsrand=[];

	for(var i=0;i<number;++i)
	{
		var point = new google.maps.LatLng(southWest.lat() + latSpan * Math.random(),southWest.lng() + lngSpan * Math.random());
		pointsrand.push(point);
	}

	for(var i=0;i<number;++i)
	{
		var marker=new google.maps.Marker({position:pointsrand[i],map:map,icon:'https://www.google.com/mapfiles/marker.png',title:pointsrand[i].toString(),draggable:false});
		arrMarkers.push(marker);
		marker.setMap(map);
		google.maps.event.addListener(marker, 'click', function (event) {
			if (ctrlPressed) {				
				var new_marker=true;
				for(var i=0;i<selected_markers.length;i++)
					if(selected_markers[i].getPosition().equals( this.getPosition() ))
						new_marker = false;
				if(new_marker)
				{
					selected_markers.push(this);
					this.setIcon('https://www.google.com/mapfiles/marker_green.png'); 				
				}
				else
				{
					for(var i=0;i<selected_markers.length;i++)
						if(selected_markers[i].getPosition().equals( this.getPosition() ))
							selected_markers.splice(i, 1);
					this.setIcon('https://www.google.com/mapfiles/marker.png');
				}
				count_selected_markers();
			}
		});
	}
}
function count_selected_markers()
{
	$('#in_circle').text('Number of selected markers: '+selected_markers.length);
}
</script>
