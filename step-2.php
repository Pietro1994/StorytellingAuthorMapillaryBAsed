<!--
***************************************************************************************************
*** Storytelling Author Mapillary BAsed (SAMBA).
*** Description: Step 2 to create a storytelling based on Mapillary: preview and access to refine or
***              download the code
***      Author: Cesare Gerbino
***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
***     License: MIT (https://opensource.org/licenses/MIT)
***************************************************************************************************
-->

<!DOCTYPE html>
<html>
	<head>
    <title>Step 2</title>
	</head>
	<body>
		<!-- *** Progress bar holder -->
		<div id="progress" style="margin:0 auto;width:500px;border:1px solid #080808;"></div>
		<!-- *** Progress information -->
		<div id="information" style="width"></div>

  <?php
    // *** Include ...
		include("storyTellingDetails.php");
		include("settings.php");

		// *** Get MapBox and Mapillary keys ...
		$MapBoxApiKey = API_MAPBOX;
		$MapillaryApiKey = API_MAPILLARY;

    date_default_timezone_set('Europe/Rome');

		// *** Get the waypoints parameter and convert in JSON ...
		$wayPoints = $_POST['wayPoints'];
    $wayPoints_json = json_decode($wayPoints);

		// *** Get the route mode parameter and convert in JSON ...
		$route_mode = $_POST['route_mode'];
    $route_mode_json = json_decode($route_mode);

		// *** Get the coordinates to use them in the request url ...
    $coords = "";
    $waypointsNumber = count($wayPoints_json);
    for ($i = 0; $i < $waypointsNumber; $i++) {
      if ($coords == "") {
        $coords .= $wayPoints_json[$i][1].",".$wayPoints_json[$i][2];
      }
      else {
        $coords .= ";".$wayPoints_json[$i][1].",".$wayPoints_json[$i][2];
      }
    }

		// *** Prepare the request to MapBox direction using the route mode and the waypoints coordinates ...
		$urlRequestRouter = "https://api.mapbox.com/directions/v5/mapbox/".$route_mode_json."/".$coords.".json?geometries=geojson&steps=false&access_token=".$MapBoxApiKey;

		// *** Set CURL parameters: pay attention to the PROXY config !!!!
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $urlRequestRouter);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, '');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 'false'); //!!!!!!********** http://stackoverflow.com/questions/4372710/php-curl-https !!!!!!!!
		$theRoute = curl_exec($ch);
		curl_close($ch);

		// *** Convert to JSON the route ...
		$theRoute_json = json_decode($theRoute);

		// *** Some initializations ...
		set_time_limit(0);
		$lat_prev = 0;
		$lon_prev = 0;
		$arr_sequences = array();
		$arr_images = array();
		$arr_texts = array();
		$array_index = 0;
    $start_path = 0;
    $currentImage = '';
    $current_sequence = '';
    $current_path = '';
		$points_parsed = 0;

		// *** The progress bar ...
		$points_number = count($theRoute_json->routes[0]->geometry->coordinates);
    foreach ($theRoute_json->routes[0]->geometry->coordinates as $value) {
			  $points_parsed = $points_parsed + 1;
				$percent = intval($points_parsed/$points_number * 100)."%";

				// *** Javascript for updating the progress bar and information ...
				echo '<script language="javascript">
				document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#0000ff;\">&nbsp;</div>";
				document.getElementById("information").innerHTML="<center><b><font color=\"red\"><br>Please, wait: '.$points_parsed.' point(s) processed until now ('.$percent.') ....</font></b></center><br>";
				</script>';

				// *** This is for the buffer achieve the minimum size in order to flush data
				    echo str_repeat(' ',1024*64);
				// *** Send output to browser immediately
				    flush();

				// *** Preparing variables for to calculate the directions ...
				$lon_cur = $value[0];
				$lat_cur = $value[1];
				if (($lat_prev != 0) and ($lon_prev != 0)) {
          // *** Calculate the angle directions ...
          $angle_direction = bearing($lat_prev, $lon_prev, $lat_cur, $lon_cur);

          // *** Get the current images ...
          $currentImages = getImage($lat_prev, $lon_prev, $lat_cur, $lon_cur, $angle_direction);

          if (count ($currentImages) > 0) {
            for($i=0; $i < count ($currentImages); $i++)
            {
              $ImageSequenceText = addImageAndSequence($currentImages[$i]);

              //*** Add values to images, sequences and texts arrays (this one at the moment empty) ...
              $arr_images[$array_index] = $ImageSequenceText[0];
              $arr_sequences[$array_index] = $ImageSequenceText[1];
              $arr_texts[$array_index] = $ImageSequenceText[2];

							//*** Prepare the values for the next cycle ...
              $lat_prev = $lat_cur;
              $lon_prev = $lon_cur;
              $array_index = $array_index + 1;
            }
          }
          else {
    	      //*** No images around the current point: moving on the next point and NOT move the array index ...
    				$lat_prev = $lat_cur;
    				$lon_prev = $lon_cur;
    			}
			}
			else {
	      //*** At the first cycle, so go ahead along the route ...
				$lat_prev = $lat_cur;
				$lon_prev = $lon_cur;
			}

		}

		// *** Build the preview of the virtual tour: in thePage variable there is the code for the download ...
    $thePage = storyTellingDetails( $arr_sequences, $arr_images, $arr_texts, $MapillaryApiKey );

		// *** Tell user that the process is completed and hide the progress bar div ...
		echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>';
		echo '<script language="javascript">document.getElementById("progress").style="visibility:hidden"</script>';
		echo '<script language="javascript">document.getElementById("information").style="visibility:hidden"</script>';

    echo "</br>";
    echo "</br>";

		// *** Complete the page ...
		echo '<center>';
    echo '<table border="0">';

    echo ' <tr>';
		echo '  <td>';
		echo '    Do you like it? Do you want put it in your HTML page ?';
		echo '  </td>';
    echo '  <td>';
    echo '   <form action="step-3.php" method="POST" target="step-3">';
		$thePage_serialized = base64_encode(serialize($thePage));
		echo '   <input type="hidden" name="thePage_serialized" value="'.$thePage_serialized.'" />';
    echo '    <input type="submit" value="    Download    ">';
    echo '   </form>';
    echo '  </td>';
    echo ' </tr>';

    echo ' <tr>';
		echo '  <td>';
		echo '    Do you want refine or create a storytelling based on this?';
		echo '  </td>';
    echo '  <td>';
    echo '   <form action="step-4.php" method="POST" target="step-4">';
    $arr_sequences_serialized = base64_encode(serialize($arr_sequences));
    $arr_images_serialized = base64_encode(serialize($arr_images));
    $arr_texts_serialized = base64_encode(serialize($arr_texts));
    echo '   <input type="hidden" name="arr_sequences_serialized" value="'.$arr_sequences_serialized.'" />';
    echo '   <input type="hidden" name="arr_images_serialized" value="'.$arr_images_serialized.'" />';
    echo '   <input type="hidden" name="arr_texts_serialized" value="'.$arr_texts_serialized.'" />';
    echo '    <input type="submit" value="Refine / Create">';
    echo '   </form>';
    echo '  </td>';
    echo ' </tr>';

    echo '</table>';

		// *** Add a help page ...
		echo '<br>';
		echo '<a href="#" onclick="window.open(\'help-step-2.html\', \'Help Step 2\', \'width=800, height=600, resizable, status, scrollbars=1, location\');">';
		echo ' Take a look to use this page ...';
		echo '</a>';

		echo '</center>';

    echo '</br>';


    // *** Calculates the direction angle between two point ...
		function bearing( $lat1_d, $lon1_d, $lat2_d, $lon2_d )
		{
			 $lat1 = deg2rad($lat1_d);
			 $long1 = deg2rad($lon1_d);
			 $lat2 = deg2rad($lat2_d);
			 $long2 = deg2rad($lon2_d);

			 $bearingradians = atan2(asin($long2-$long1)*cos($lat2),cos($lat1)*sin($lat2) - sin($lat1)*cos($lat2)*cos($long2-$long1));
			 $bearingdegrees = rad2deg($bearingradians);
			 $bearingdegrees = $bearingdegrees < 0? 360 + $bearingdegrees : $bearingdegrees;

			 return $bearingdegrees;
		};


		// *** Format the current time ...
    function udate($format, $utimestamp = null) {
      if (is_null($utimestamp))
        $utimestamp = microtime(true);

      $timestamp = floor($utimestamp);
      $milliseconds = round(($utimestamp - $timestamp) * 1000000);

      return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }


		// *** Get the images around the current point and considering the angle direction of the path ...
    function getImage($lat_prev, $lon_prev, $lat_cur, $lon_cur, $angle_direction) {
      global $start_path;
			global $MapillaryApiKey;
      $num_img = 0;

			// *** The Mapillary API base url ...
      $urlRequestMapillaryBase = "https://a.mapillary.com/v2/search/im/geojson/close?client_id=".$MapillaryApiKey;
      $urlRequestMapillary = '';

      if ($start_path == 0) {
        $num_img = 2;
        }
      else {
        $num_img = 1;
        }

			// *** Complete the Mapillary APi request url ...
      switch ($angle_direction) {
        case ($angle_direction > 45) AND ($angle_direction <= 135):
          $urlRequestMapillary = $urlRequestMapillaryBase."&lat=".$lat_prev."&lon=".$lon_prev."&limit=".$num_img."&min_ca=45&max_ca=135&distance=100";
          break;
        case ($angle_direction > 135) AND ($angle_direction <= 225):
          $urlRequestMapillary = $urlRequestMapillaryBase."&lat=".$lat_prev."&lon=".$lon_prev."&limit=".$num_img."&min_ca=135&max_ca=225&distance=100";
          break;
        case ($angle_direction > 225) AND ($angle_direction <= 315):
          $urlRequestMapillary = $urlRequestMapillaryBase."&lat=".$lat_prev."&lon=".$lon_prev."&limit=".$num_img."&min_ca=225&max_ca=315&distance=100";
          break;
        case ($angle_direction > 315) OR ($angle_direction <= 45):
          $urlRequestMapillary = $urlRequestMapillaryBase."&lat=".$lat_prev."&lon=".$lon_prev."&limit=".$num_img."&min_ca=-45&max_ca=45&distance=100";
          break;
        default:
          echo "Error!!!";
      }
      $start_path = $start_path + 1;

			// *** Request to Mapillary API ...
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $urlRequestMapillary);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_PROXY, '');
//      curl_setopt($ch, CURLOPT_PROXY, 'proxy.csi.it:3128');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 'false'); //!!!!!!********** http://stackoverflow.com/questions/4372710/php-curl-https !!!!!!!!
      $mapillary_image_details = curl_exec($ch);
      curl_close($ch);

			// *** Convert the response to json ...
      $mapillary_image_details_json = json_decode($mapillary_image_details,true);

			// *** Get the image properties loading them in an array ...
      $i = 0;
      $currentImages = [];
      foreach ($mapillary_image_details_json['features'] as $f) {
            $currentImages[$i] = $f['properties']['key'];
            $i = $i + 1;
      }

			// *** Return the images array ...
      return $currentImages;
    }


		// *** Get the image sequence Id ...
    function addImageAndSequence($currentImage) {
			  global $MapillaryApiKey;

        //*** Prepare the Mapillary API request ...
				//*** NOTE: this is ad UNDOCUMENTED Mapillary API request ...
        $urlRequestMapillaryNav = "https://a.mapillary.com/v2/nav/im/".$currentImage."?client_id=".$MapillaryApiKey;

        //*** Execute the Mapillary API request ...
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $urlRequestMapillaryNav);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_PROXY, '');
        //      curl_setopt($ch, CURLOPT_PROXY, 'proxy.csi.it:3128');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 'false'); //!!!!!!********** http://stackoverflow.com/questions/4372710/php-curl-https !!!!!!!!
        $mapillary_around_image_details = curl_exec($ch);
        curl_close($ch);

        //*** Get the image sequence Id and its coordinates path ...
        $mapillary_around_image_details_json = json_decode($mapillary_around_image_details,true);
        foreach ($mapillary_around_image_details_json['ss'] as $s) {
              $imagesArray = $s['keys'];
              if (in_array($currentImage, $s['keys']))
                {
                    $current_sequence = $s['key'];
                    $current_path = $s['path']['coordinates'];
                }

        }

        //*** Add image Id, sequence Id, end text (at the moment empty ...) to a multidimensional array ...
        $ImageSequenceText[0] = $currentImage;
        $ImageSequenceText[1] = $current_sequence;
        $ImageSequenceText[2] = '';

				//*** Return the multidimensional array with image Id, sequence Id, end text ...
        return $ImageSequenceText;
    }

  ?>

</body>
</html>
