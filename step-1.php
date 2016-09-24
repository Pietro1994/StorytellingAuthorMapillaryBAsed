<!--
 ***************************************************************************************************
 *** Storytelling Author Mapillary BAsed (SAMBA).
 *** Description: Step 1 to create a storytelling based on Mapillary: search for a city or address,
 ***              select the route mode (vehicle, foot. bike), draw the points on the map
 ***      Author: Cesare Gerbino
 ***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
 ***     License: MIT (https://opensource.org/licenses/MIT)
 ***************************************************************************************************
-->

<!doctype html>
<html>
	<head>
		<meta charset='utf-8' />
    <title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- References for the page css ... -->
		<link rel="stylesheet" media="screen" href="css/rightFluid.css">

    <!-- *** References for MapBox GL JS ... -->
		<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.18.0/mapbox-gl.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.18.0/mapbox-gl.css' rel='stylesheet' />

		<!-- *** References for MapBox GL Draw ... -->
    <script src="MapBoxGL-Draw/mapbox-gl-draw.js"></script>
    <link href='MapBoxGL-Draw/mapbox-gl-draw.css' rel='stylesheet' />

		<!-- *** References for MapBox GL Geocoder ... -->
    <script src='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v1.3.0/mapbox-gl-geocoder.js'></script>
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v1.3.0/mapbox-gl-geocoder.css' type='text/css' />

		<!-- *** References for Turf ... -->
		<script src='https://api.tiles.mapbox.com/mapbox.js/plugins/turf/v3.0.11/turf.min.js'></script>

		<!-- *** Define the styles used in the page ... -->
    <style>
        #menu {
            background: #fff;
            position: absolute;
            z-index: 1;
            top: 70px;
            right: 10px;
            border-radius: 3px;
            width: 240px;
            border: 1px solid rgba(0,0,0,0.4);
            font-family: 'Open Sans', sans-serif;
        }

        #menu a {
            font-size: 13px;
            color: #404040;
            display: block;
            margin: 0;
            padding: 0;
            padding: 10px;
            text-decoration: none;
            border-bottom: 1px solid rgba(0,0,0,0.25);
            text-align: center;
        }

        #menu a:last-child {
            border: none;
        }

        #menu a:hover {
            background-color: #f8f8f8;
            color: #404040;
        }

        #menu a.active {
            background-color: #3887be;
            color: #ffffff;
        }

        #menu a.active:hover {
            background: #3074a4;
        }

        #menu1 {
          background: #fff;
          position: absolute;
          z-index: 1;
          top: 120px;
          right: 10px;
          border-radius: 3px;
          width: 240px;
          height: 35px;
          border: 1px solid rgba(0,0,0,0.4);
          font-family: 'Open Sans', sans-serif;
        }

        #menu1 a {
          font-size: 13px;
          color: #404040;
          display: block;
          margin: 0;
          padding: 0;
          padding: 10px;
          text-decoration: none;
          border-bottom: 1px solid rgba(0,0,0,0.25);
          text-align: center;
        }

        #menu1 a.active {
            background-color: #3887be;
            color: #ffffff;
        }

        #menu1 a.active:hover {
            background: #3074a4;
        }

				#menu2 {
					position: absolute;
					z-index: 1;
					top: 80px;
					left: 8px;
					border: 1px solid rgba(0,0,0,0.4);
					border-radius: 5px;
	        background: #fff;
	        padding: 5px;
	        font-family: 'Open Sans', sans-serif;
		    }
    </style>
  </head>
  <body>
  	<h1 style="padding-left: 20px">Storytelling Author Mapillary BAsed (SAMBA)</h1>

		<!-- *** Get the MapBox API key ... -->
		<?php
			include("settings.php");
			$MapBoxApiKey = API_MAPBOX;
		?>;

		<!-- *** Create the legend ... -->
  	<div class="columnsContainer">
    	<div class="leftColumn">
				<p><b><center>Help and suggestions</center></b></p>
				<table border=0 cellpadding="5">
					<tr>
						<td>
							<div class="mapboxgl-ctrl-group mapboxgl-ctrl" width="50%">
								<button class="mapboxgl-ctrl-icon mapboxgl-ctrl-zoom-in" width="50%"></button>
							</div>
						</td>
						<td>
							Zoom in
						</td>
					</tr>
					<tr>
						<td>
							<div class="mapboxgl-ctrl-group mapboxgl-ctrl">
								<button class="mapboxgl-ctrl-icon mapboxgl-ctrl-zoom-out"></button>
							</div>
						</td>
						<td>
							Zoom out
						</td>
					</tr>
					<tr>
						<td>
             <div class="mapboxgl-ctrl-group mapboxgl-ctrl">
							 <button id="edit_legend" class="mapbox-gl-draw_ctrl-draw-btn mapbox-gl-draw_point" title="Marker tool (m)"></button>
						 </div>
						</td>
						<td>
							Edit point
						</td>
					</tr>
					<tr>
						<td>
							<div class="mapboxgl-ctrl-group mapboxgl-ctrl">
 							 <button id="delete_legend" class="mapbox-gl-draw_ctrl-draw-btn mapbox-gl-draw_trash" title="delete"></button>
 						 </div>
						</td>
						<td>
							Delete point
						</td>
					</tr>
				</table>
				<br>
				<br>
				<!-- *** Add a help page ... -->
				<center>
					<a href="#" onclick="window.open('help-step-1.html', 'Help Step 1', 'width=800, height=600, resizable, status, scrollbars=1, location');">
            Take a look to use this page ...
          </a>
				</center>
			</div>

 	  	<div id="map" class="rightColumn">
				<nav id="menu"></nav>
			  <div id="menu1">
					<a href=# class="active" onclick=goStep2()>Go to the next step ... </a>
					<script>
						function goStep2(){
							if (coordsArray.length > 0) {
                // *** Prepare a form to send, in POST mode, data to step-2 ...
								var myForm = document.createElement("form");
								myForm.name = "MyForm";
								myForm.method = "post";
								myForm.action = "step-2.php";
								myForm.target = "step-2";

								// *** Put in myTextArea1 the route points edited  ...
								var myTextArea1 = document.createElement("textarea");
								myTextArea1.name = "wayPoints";
								myTextArea1.type = "textarea";
								myTextArea1.value = JSON.stringify(coordsArray);
								myForm.appendChild(myTextArea1);

								// *** Put in myTextArea2 the route mode  ...
								var myTextArea2 = document.createElement("textarea");
								myTextArea2.name = "route_mode";
								myTextArea2.type = "textarea";
								myTextArea2.value = JSON.stringify(route_mode);
								myForm.appendChild(myTextArea2);

								document.body.appendChild(myForm);
								myForm.submit();

								document.body.removeChild(myForm);
              }
							else {
								alert("No route calculated !!");
              }
						}
					</script>
			  </div>

				<!-- *** The route mode options ... -->
				<div id="menu2">
					<input id='driving' type='radio' name='route_mode' value='driving' onclick=getTheRoute(coordsArray) checked='checked'>
			    <label for='Vehicle'>Vehicle</label>
					<br>
			    <input id='walking' type='radio' name='route_mode' value='walking' onclick=getTheRoute(coordsArray)>
			    <label for='Foot'>Foot</label>
			    <br>
					<input id='cycling' type='radio' name='route_mode' value='cycling' onclick=getTheRoute(coordsArray)>
			    <label for='Bike'>Bike</label>
				</div>

			  <script>
					var coordsArray = [];
			    var theRouteJsonText = "";
			    var theCoords;
			    var isDraggable = false;
			    var idFeatureDragged = 0;
			    var startEdit = false;
			    var stopEdit = false;
			    var stopDrag = false;
			    var featureSelected = false;
			    var indexElementToDelete = -1;
					var MapBoxAccessKey = "";

					// *** !!!! NOTABLE !!!: not the best solution but it's working. Share the PHP GraphHopper API key with Javascript !!!!! ...
					// *** Set the MapBox access key ...
				  MapBoxAccessKey = <?php echo json_encode($MapBoxApiKey); ?>; //Don't forget the extra semicolon!

					mapboxgl.accessToken = MapBoxAccessKey;

					// *** Create and configure the map ...
			    var map = new mapboxgl.Map({
			        container: 'map', // container id
			        style: 'mapbox://styles/mapbox/bright-v9', //stylesheet location
			        center: [7.828,44.836], // starting position
			        zoom: 7 // starting zoom
			    });

					// *** Add zoom and rotation controls to the map ...
					map.addControl(new mapboxgl.Navigation({position: 'bottom-left'}));

					// *** Add the geocoder to the map ...
					var geocoder = new mapboxgl.Geocoder({
			        position: "top-right"
			    });
			    map.addControl(geocoder);

					// *** Add the draw control to the map ...
					var Draw = mapboxgl.Draw({
			      displayControlsDefault: false,
			      controls: {
			        'point': true,
			        'trash': true
			      }
			    });
			    map.addControl(Draw);

					// *** Get the edit button...
					// *** NOTE: there are TWO edit button in the page, I've to exclude the edit button in the legend ...
					var editButton = document.getElementsByClassName('mapbox-gl-draw_ctrl-draw-btn mapbox-gl-draw_point'); // grab a reference to your element
          for(i=0; i<editButton.length; i++) {
            if (editButton[i].id != "edit_legend") {
              // *** setting variables when start edit session ...
							editButton[i].onclick = function(){
					              startEdit = true;
					              stopEdit = false;
					              stopDrag = false;
					              isDraggable = false;
												idFeatureDragged = 0;
					              featureSelected = false;
					        };
            }
				  }

					// *** Get the delete button...
					// *** NOTE: there are TWO delete button in the page, I've to exclude the delete button in the legend ...
			    var deleteButton = document.getElementsByClassName('mapbox-gl-draw_ctrl-draw-btn mapbox-gl-draw_trash'); // grab a reference to your element
					for(i=0; i<deleteButton.length; i++) {
						if (deleteButton[i].id != "delete_legend") {
							// *** delete point selected ...
					    deleteButton[i].onclick = function(){
					              if (coordsArray.length > 0) {
                           // *** Search the point selected using id ...
					                 indexElementToDelete = -1;
					                 for (i = 0; i < coordsArray.length; i++ ) {
					                      if (coordsArray[i][0] == idFeatureDragged) {
					                             indexElementToDelete = i
					                     }
					                 }

													 // *** Remove the point from the array ...
					                 if (indexElementToDelete >= 0) {
					                    coordsArray.splice(indexElementToDelete, 1);
					                 }

													 // *** Recalculate the route (only if the array ha more then ONE point) ...
					                 if (coordsArray.length > 1) {
					                     getTheRoute(coordsArray);
					                 }
													 else {
														 // *** Remove the layer and the source from the map...
														 try {
							 			          map.removeSource("route");
							 			          map.removeLayer("route");
							 			         }
							 			         catch(err) {
							 			         }
													 }

													 // *** Setting the variables to default values ...
					                 startEdit = false;
					                 stopEdit = false;
					                 stopDrag = false;
					                 isDraggable = false;
													 idFeatureDragged = 0;
					                 featureSelected = false;
					              }
					              else {
					                 //console.log("Nothing to delete !!")
					              }
					    };
						}
				  }

					// *** ON/OFF Mapillary sequences ...
					var toggleableLayerIds = [ 'ON/OFF Mapillary sequences' ];
	        for (var i = 0; i < toggleableLayerIds.length; i++) {
	            var id = toggleableLayerIds[i];

	            var link = document.createElement('a');
	            link.href = '#';
	            link.className = 'active';
	            link.textContent = id;

	            link.onclick = function (e) {
	                var clickedLayer = this.textContent;
	                e.preventDefault();
	                e.stopPropagation();

	                var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

	                if (visibility === 'visible') {
	                    map.setLayoutProperty(clickedLayer, 'visibility', 'none');
	                    this.className = '';
	                } else {
	                    this.className = 'active';
	                    map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
	                }
	            };

	            var layers = document.getElementById('menu');
	            layers.appendChild(link);
	        }

					// *** The Mapillary sequences reference ...
	        var mapillarySource = {
	            type: 'vector',
	            tiles: ['http://d25uarhxywzl1j.cloudfront.net/v0.1/{z}/{x}/{y}.mvt'],
	            minzoom: 0,
	            maxzoom: 16
	        }

					// *** Load Mapillary sequences layer to the map ...
	        map.on('style.load', function () {
	                map.addSource('mapillarySequences', mapillarySource)
	                map.addLayer({
	                    'id': 'ON/OFF Mapillary sequences',
	                    'type': 'line',
	                    'source': 'mapillarySequences',
	                    'source-layer': 'mapillary-sequences',
	                    'layout': {
	                        'line-cap': 'round',
	                        'line-join': 'round'
	                    },
	                    'paint': {
	                        'line-opacity': 0.6,
	                        'line-color':   'rgb(53, 175, 109)',
	                        'line-width':   2
	                    }
	                }, 'markers')
	        })

					// *** Manage the mouse down event ...
					map.on('mousedown', function (e) {

			      // *** Get the point pixel and geo points  ...
						pixelCoords = e.point;
			      geoCoords = e.lngLat;

						// *** Get the point pixel coordinates  ...
			      x = pixelCoords.x;
			      y = pixelCoords.y;

						// *** Get the point geo coordinates  ...
			      lng = Number(geoCoords.lng.toFixed(5));
			      lat = Number(geoCoords.lat.toFixed(5));

			      if (startEdit == true) {
							 // *** Define the point feature to add on the map ...
			         var feature = { type: 'Point', coordinates: [lat, lng] };
			         var featureId = Draw.add(feature);

							 // *** Add the new point feature to the array ...
			         coordsArray.push([featureId, lng, lat]);

			         currentPoint = {
			           "type": "Feature",
			           "properties": {},
			           "geometry": {
			             "type": "Point",
			             "coordinates": [Number(lng), Number(lat)]
			           }
			         };

							 // *** Set the buffer distance ...
							 bufferDistance = 0;
				       if ((map.getZoom() > 0) && (map.getZoom() <= 1)) bufferDistance = 655360;
				       if ((map.getZoom() > 1) && (map.getZoom() <= 2)) bufferDistance = 327680;
				       if ((map.getZoom() > 2) && (map.getZoom() <= 3)) bufferDistance = 163840;
				       if ((map.getZoom() > 3) && (map.getZoom() <= 4)) bufferDistance = 81920;
				       if ((map.getZoom() > 4) && (map.getZoom() <= 5)) bufferDistance = 40960;
				       if ((map.getZoom() > 5) && (map.getZoom() <= 6)) bufferDistance = 20480;
				       if ((map.getZoom() > 6) && (map.getZoom() <= 7)) bufferDistance = 10240;
				       if ((map.getZoom() > 7) && (map.getZoom() <= 8)) bufferDistance = 5120;
				       if ((map.getZoom() > 8) && (map.getZoom() <= 9)) bufferDistance = 2560;
				       if ((map.getZoom() > 9) && (map.getZoom() <= 10)) bufferDistance = 1280;
				       if ((map.getZoom() > 10) && (map.getZoom() <= 11)) bufferDistance = 640;
				       if ((map.getZoom() > 11) && (map.getZoom() <= 12)) bufferDistance = 320;
				       if ((map.getZoom() > 12) && (map.getZoom() <= 13)) bufferDistance = 160;
				       if ((map.getZoom() > 13) && (map.getZoom() <= 14)) bufferDistance = 80;
				       if ((map.getZoom() > 14) && (map.getZoom() <= 15)) bufferDistance = 40;
				       if ((map.getZoom() > 15) && (map.getZoom() <= 16)) bufferDistance = 20;
				       if ((map.getZoom() > 16) && (map.getZoom() <= 17)) bufferDistance = 10;
				       if ((map.getZoom() > 17) && (map.getZoom() <= 18)) bufferDistance = 5;
				       if ((map.getZoom() > 18) && (map.getZoom() <= 19)) bufferDistance = 3;
				       if ((map.getZoom() > 19) && (map.getZoom() <= 20)) bufferDistance = 1;

							 // *** Calculate a buffer area arount the current point ...
				       bufferCurrentPoint = turf.buffer(currentPoint, bufferDistance, 'meters');

							 // *** Search for point that is inside the buffer area  ...
			         for (i = 0; i < coordsArray.length; i++ ) {
			             testPoint = {
			               "type": "Feature",
			               "properties": {},
			               "geometry": {
			                 "type": "Point",
			                 "coordinates": [Number(coordsArray[i][1]), Number(coordsArray[i][2])]
			               }
			             };

									 // *** If the current point is inside the buffer area mark it as "draggable"  ...
			             if (turf.inside(testPoint, bufferCurrentPoint)) {
			               isDraggable = true;
			               idFeatureDragged = coordsArray[i][0];
			             }
			        }

							// *** Recalculate the route (only if the array ha more then ONE point) ...
			        if (coordsArray.length > 1) {
			           getTheRoute(coordsArray);
			        }

							// *** Set variables at new values ...
			        startEdit = false;
			        stopEdit = true;
			        }
			      else {
			           currentPoint = {
			             "type": "Feature",
			             "properties": {},
			             "geometry": {
			               "type": "Point",
			               "coordinates": [Number(lng), Number(lat)]
			             }
			           };

								 // *** Set the buffer distance ...
								 bufferDistance = 0;
					       if ((map.getZoom() > 0) && (map.getZoom() <= 1)) bufferDistance = 655360;
					       if ((map.getZoom() > 1) && (map.getZoom() <= 2)) bufferDistance = 327680;
					       if ((map.getZoom() > 2) && (map.getZoom() <= 3)) bufferDistance = 163840;
					       if ((map.getZoom() > 3) && (map.getZoom() <= 4)) bufferDistance = 81920;
					       if ((map.getZoom() > 4) && (map.getZoom() <= 5)) bufferDistance = 40960;
					       if ((map.getZoom() > 5) && (map.getZoom() <= 6)) bufferDistance = 20480;
					       if ((map.getZoom() > 6) && (map.getZoom() <= 7)) bufferDistance = 10240;
					       if ((map.getZoom() > 7) && (map.getZoom() <= 8)) bufferDistance = 5120;
					       if ((map.getZoom() > 8) && (map.getZoom() <= 9)) bufferDistance = 2560;
					       if ((map.getZoom() > 9) && (map.getZoom() <= 10)) bufferDistance = 1280;
					       if ((map.getZoom() > 10) && (map.getZoom() <= 11)) bufferDistance = 640;
					       if ((map.getZoom() > 11) && (map.getZoom() <= 12)) bufferDistance = 320;
					       if ((map.getZoom() > 12) && (map.getZoom() <= 13)) bufferDistance = 160;
					       if ((map.getZoom() > 13) && (map.getZoom() <= 14)) bufferDistance = 80;
					       if ((map.getZoom() > 14) && (map.getZoom() <= 15)) bufferDistance = 40;
					       if ((map.getZoom() > 15) && (map.getZoom() <= 16)) bufferDistance = 20;
					       if ((map.getZoom() > 16) && (map.getZoom() <= 17)) bufferDistance = 10;
					       if ((map.getZoom() > 17) && (map.getZoom() <= 18)) bufferDistance = 5;
					       if ((map.getZoom() > 18) && (map.getZoom() <= 19)) bufferDistance = 3;
					       if ((map.getZoom() > 19) && (map.getZoom() <= 20)) bufferDistance = 1;

								 // *** Calculate a buffer area arount the current point ...
					       bufferCurrentPoint = turf.buffer(currentPoint, bufferDistance, 'meters');

								 // *** Search for point that is inside the buffer area  ...
			           featureSelected = false;
			           for (i = 0; i < coordsArray.length; i++ ) {
			               testPoint = {
			                 "type": "Feature",
			                 "properties": {},
			                 "geometry": {
			                   "type": "Point",
			                   "coordinates": [Number(coordsArray[i][1]), Number(coordsArray[i][2])]
			                 }
			               };

										 // *** If the current point is inside the buffer area mark it as "draggable"  ...
			               if (turf.inside(testPoint, bufferCurrentPoint)) {
			                 idFeatureDragged = coordsArray[i][0];
			                 isDraggable = true;
			                 featureSelected = true;
			               }
			           }

								 // *** Set variables at new values ...
			           if (featureSelected == false) {
			             isDraggable = false;
									 idFeatureDragged = 0;
			           }
								 else {
			           }
			           stopEdit = false
			      }
			    });

					// *** Manage the mouse up event ...
					map.on('mouseup', function (e) {
			      if ((isDraggable == true)) {
							// *** Get the point pixel and geo points  ...
			        pixelCoords = e.point;
			        geoCoords = e.lngLat;

							// *** Get the point pixel coordinates  ...
			        x = pixelCoords.x;
			        y = pixelCoords.y;

							// *** Get the point geo coordinates  ...
			        lng = Number(geoCoords.lng.toFixed(5));
			        lat = Number(geoCoords.lat.toFixed(5));

							// *** Update the coordinates for the point = idFeatureDragged in the array ...
			        for (i = 0; i < coordsArray.length; i++ ) {
			             if (coordsArray[i][0] == idFeatureDragged) {
			                    coordsArray[i][1] = lng;
			                    coordsArray[i][2] = lat
			            }
			        }

							// *** Recalculate the route (only if the array ha more then ONE point) ...
			        if (coordsArray.length > 1) {
			            getTheRoute(coordsArray);
			          }

/*
		          currentPoint = {
		            "type": "Feature",
		            "properties": {},
		            "geometry": {
		              "type": "Point",
		              "coordinates": [Number(lng), Number(lat)]
		            }
		          };

							// *** Set the buffer distance ...
							bufferDistance = 0;
							if ((map.getZoom() > 0) && (map.getZoom() <= 1)) bufferDistance = 655360;
							if ((map.getZoom() > 1) && (map.getZoom() <= 2)) bufferDistance = 327680;
							if ((map.getZoom() > 2) && (map.getZoom() <= 3)) bufferDistance = 163840;
							if ((map.getZoom() > 3) && (map.getZoom() <= 4)) bufferDistance = 81920;
							if ((map.getZoom() > 4) && (map.getZoom() <= 5)) bufferDistance = 40960;
							if ((map.getZoom() > 5) && (map.getZoom() <= 6)) bufferDistance = 20480;
							if ((map.getZoom() > 6) && (map.getZoom() <= 7)) bufferDistance = 10240;
							if ((map.getZoom() > 7) && (map.getZoom() <= 8)) bufferDistance = 5120;
							if ((map.getZoom() > 8) && (map.getZoom() <= 9)) bufferDistance = 2560;
							if ((map.getZoom() > 9) && (map.getZoom() <= 10)) bufferDistance = 1280;
							if ((map.getZoom() > 10) && (map.getZoom() <= 11)) bufferDistance = 640;
							if ((map.getZoom() > 11) && (map.getZoom() <= 12)) bufferDistance = 320;
							if ((map.getZoom() > 12) && (map.getZoom() <= 13)) bufferDistance = 160;
							if ((map.getZoom() > 13) && (map.getZoom() <= 14)) bufferDistance = 80;
							if ((map.getZoom() > 14) && (map.getZoom() <= 15)) bufferDistance = 40;
							if ((map.getZoom() > 15) && (map.getZoom() <= 16)) bufferDistance = 20;
							if ((map.getZoom() > 16) && (map.getZoom() <= 17)) bufferDistance = 10;
							if ((map.getZoom() > 17) && (map.getZoom() <= 18)) bufferDistance = 5;
							if ((map.getZoom() > 18) && (map.getZoom() <= 19)) bufferDistance = 3;
							if ((map.getZoom() > 19) && (map.getZoom() <= 20)) bufferDistance = 1;

							// *** Calculate a buffer area arount the current point ...
							bufferCurrentPoint = turf.buffer(currentPoint, bufferDistance, 'meters');

		          for (i = 0; i < coordsArray.length; i++ ) {
		            testPoint = {
		              "type": "Feature",
		              "properties": {},
		              "geometry": {
		                "type": "Point",
		                "coordinates": [Number(coordsArray[i][1]), Number(coordsArray[i][2])]
		              }
		            };

		            if (turf.inside(testPoint, bufferCurrentPoint)) {
		            }
		          };
							//console.log("Generate a MouseUp on the map !!!  - startEdit = " + startEdit + " - stopEdit = " + stopEdit + " - isDraggable = " + isDraggable  + " - featureSelected = " + featureSelected);
*/
              // *** There is a bug in MapBox GL Draw, so it's needed to update the point to show it again on the map  ...
							obj = Draw.get(idFeatureDragged);
							Draw.add(obj);
			      }
			    });

					function getTheRoute(coordsArray) {
            if (coordsArray.length > 0) {
							 // *** Prepare the route mode ...
							 route_mode = "";
							 if (document.getElementById('driving').checked) {
							   route_mode = document.getElementById('driving').value;
							 };
							 if (document.getElementById('walking').checked) {
							   route_mode = document.getElementById('walking').value;
							 };
							 if (document.getElementById('cycling').checked) {
							   route_mode = document.getElementById('cycling').value;
							 };

							 // *** Prepare the request url ...
				       url = "https://api.mapbox.com/directions/v5/mapbox/";
							 url = url + route_mode + "/";

							 // *** Add the points coordinates to the request url ...
				       for (i = 0; i < coordsArray.length; i++ ) {
				        url = url + coordsArray[i][1] + "," + coordsArray[i][2];
				        if (i != coordsArray.length - 1) url = url + ";";
				       }

							 // *** Complete the request url ...
				       url = url + "?geometries=geojson&access_token=" + MapBoxAccessKey;

				       var xmlhttp = new XMLHttpRequest();

							 // *** Send the request ...
				       xmlhttp.onreadystatechange = function() {
				           if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
										   // *** The response ...
				               var myArr = JSON.parse(xmlhttp.responseText);
											 // *** Get the coordinates of the calculated route ...
				               theCoords = myArr.routes[0].geometry;
											 // *** Add the route toi the map ...
				               addRouteToMap(theCoords);
				           }
				       };
				       xmlhttp.open("GET", url, true);
				       xmlhttp.send();
						}
			    }

					function addRouteToMap(theCoords) {
						  // *** Remove source and layer if defined ...
			        try {
			          map.removeSource("route");
			          map.removeLayer("route");
			        }
			        catch(err) {
			          console.log("The source and layer \"route\" are not defined");
			        }

							// *** Add source ...
			        map.addSource("route", {
			            "type": "geojson",
			            "data": {
			                "type": "Feature",
			                "properties": {},
			                "geometry": theCoords
			            }
			        });

							// *** Add layer ...
			        map.addLayer({
			            "id": "route",
			            "type": "line",
			            "source": "route",
			            "layout": {
			                "line-join": "round",
			                "line-cap": "round"
			            },
			            "paint": {
			                "line-color": "#ff0000",
			                "line-width": 4
			            }
			        });
			    }
				</script>
	  	</div>
  	</div>

    <footer>
      <p>Reference and details: created by Cesare Gerbino (cesare.gerbino@gmail.com). <a href="https://cesaregerbino.wordpress.com/" target="details_blog">Details</a> (in Italian). The <a href="https://github.com/cesaregerbino" target="gitHub">code</a> is available under MIT licence</p>
    </footer>
  </body>
</html>
