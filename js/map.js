function showMap(latitude, longitude,zoom)
{
	infowindow = new google.maps.InfoWindow({maxWidth: 400});
	bounds = new google.maps.LatLngBounds();
	var mapProp = {
		  center:new google.maps.LatLng(latitude,longitude),
		  zoomControl: true,
		  zoom:zoom,
		  mapTypeControl: false,
		  mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	
	map = new google.maps.Map(document.getElementById("map"),mapProp);
	google.maps.event.addListener(map,'click',function(event) {
		infowindow.close();
	});
	return map;
}
function displayRoute(origin,destination) 
{
	var request = {
		origin : origin,
		destination : destination,
		provideRouteAlternatives : true,
		travelMode : google.maps.TravelMode.DRIVING
	};
	var directionsService = new google.maps.DirectionsService();
	directionsService.route(request, function(response, _status) {
		if (_status == google.maps.DirectionsStatus.OK) {
			
			for (p in route_polylines)
				route_polylines[p].setMap(null);
			route_polylines = [];
			for(var i=0;i<response.routes.length;i++)
			{
				var polyline = new google.maps.Polyline({
					map : map,
					path : google.maps.geometry.encoding.decodePath(response.routes[i].overview_polyline),
					geodesic: true,
					strokeColor: routes_color[i],
					strokeOpacity: 0.5,
					strokeWeight: 2
				});
				route_polylines.push(polyline);
			}
		}
		else if (_status == google.maps.DirectionsStatus.NOT_FOUND) {
			alert("At least one of the locations specified in the request's origin, destination, or way points could not be geocoded.");
			return false;
		}
		else if (_status == google.maps.DirectionsStatus.ZERO_RESULTS) {
			alert("No route could be found between the origin and destination.");
			return false;
		}
		else if (_status == google.maps.DirectionsStatus.MAX_WAYPOINTS_EXCEEDED) {
			alert("The maximum allowed way points is 8, plus the origin, and destination.");
			return false;
		}
		else if (_status == google.maps.DirectionsStatus.INVALID_REQUEST) {
			alert("The provided DirectionsRequest was invalid. The most common causes of this error code are requests that are missing either an origin or destination, or a transit request that includes way points.");		
			return false;	
		}
		else if (_status == google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
			alert("The web page has sent too many requests within the allowed time period.");
			return false;
		}
		else if (_status == google.maps.DirectionsStatus.REQUEST_DENIED) {
			alert("The web page is not allowed to use the directions service.");
			return false;
		}
		else if (_status == google.maps.DirectionsStatus.UNKNOWN_ERROR) {
			alert("Directions request could not be processed due to a server error. The request may succeed if you try again.");
		}
	});
}
function show_route()
{
	if($('#route').is(':checked'))
	{
		for (p in polylines)
			polylines[p].setMap(map);
	}
	else
	{
		for (p in polylines)
			polylines[p].setMap(null);
	}
}
function show_marker()
{
	if($('#marker').is(':checked'))
	{
		for (p in markers)
			markers[p].setMap(map);
	}
	else
	{
		for (p in markers)
			markers[p].setMap(null);
	}
}