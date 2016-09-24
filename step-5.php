<!--
***************************************************************************************************
*** Storytelling Author Mapillary BAsed (SAMBA).
*** Description: Step 5 to create a storytelling based on Mapillary: preview and download
***      Author: Cesare Gerbino
***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
***     License: MIT (https://opensource.org/licenses/MIT)
***************************************************************************************************
-->

<!DOCTYPE html>
<html>
	<head>
    <title>Step 5</title>
	</head>
	<body>
		<?php
		    // *** Include ...
		   include("storyTellingDetails.php");
		   include("settings.php");

			 // *** Get Mapillary key ...
		   $MapillaryApiKey = API_MAPILLARY;

		   //*** Some initializations ...
		   $arr_original_images = array();
		   $arr_original_sequences = array();
		   $arr_original_texts = array();
		   $arr_images = array();
		   $arr_sequences = array();
		   $arr_texts = array();

		   //*** Get the images selected array parameters and unserialize ...
		   $arr_img_selected = $_POST['piace'];
		   $arr_original_sequences = unserialize(base64_decode($_POST["arr_sequences_serialized"]));
		   $arr_original_images = unserialize(base64_decode($_POST["arr_images_serialized"]));

		   $textStoryTelling = 0;
		   if(!empty($_POST['texts'])) {
		      $textStoryTelling = 1;
		      $arr_original_texts = $_POST['texts'];
		   }

		   $n_images = count ($arr_img_selected);

		   //*** Add values to images, sequences and texts arrays ...
		   for($i=0; $i < $n_images; $i++)
		   {
		     $key = array_search($arr_img_selected[$i], $arr_original_images);
		     $arr_images[$i] = $arr_original_images[$key];
		     $arr_sequences[$i] = $arr_original_sequences[$key];
		     $arr_texts[$i] = $arr_original_texts[$key];
		   }

		   // *** Build the preview of the virtual tour: in thePage variable there is the code for the download ...
		   $thePage = storyTellingDetails( $arr_sequences, $arr_images, $arr_texts, $MapillaryApiKey );

		   echo "</br>";
		   echo "</br>";

		   echo '<center>';
		   echo ' <table border="0">';

		   echo '  <tr>';
		   echo '   <td>';
		   echo '    Do you like it? Do you want put it in your HTML page ?';
		   echo '   </td>';
		   echo '   <td>';
		   echo '    <form action="step-3.php" method="POST" target="step-3">';
		   $thePage_serialized = base64_encode(serialize($thePage));
		   echo '     <input type="hidden" name="thePage_serialized" value="'.$thePage_serialized.'" />';
		   echo '     <input type="submit" value="Download">';
		   echo '    </form>';
		   echo '   </td>';
		   echo '  </tr>';
		   echo ' </table>';
		   echo '</center>';

		   echo '</br>';

		   //*** Add an help page ...
		   echo '<center>';
		   echo ' <br>';
		   echo ' <a href="#" onclick="window.open(\'help-step-5.html\', \'Help Step 5\', \'width=800, height=600, resizable, status, scrollbars=1, location\');">';
		   echo '  Take a look to use this page ...';
		   echo ' </a>';
		   echo '</center>';
		?>
  </body>
</html>
