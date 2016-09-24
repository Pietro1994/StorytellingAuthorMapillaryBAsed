<!--
***************************************************************************************************
*** Storytelling Author Mapillary BAsed (SAMBA).
*** Description: Step 4 to create a storytelling based on Mapillary: refine the storytelling
***              selecting the images and / or adding description text
***      Author: Cesare Gerbino
***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
***     License: MIT (https://opensource.org/licenses/MIT)
***************************************************************************************************
-->

<!DOCTYPE html>
<html>
	<head>
    <title>Step 4</title>
	</head>
	<body>
		<?php
		    //*** Get the images, sequences and texts array parameters ...
		    $arr_sequences = unserialize(base64_decode($_POST["arr_sequences_serialized"]));
		    $arr_images = unserialize(base64_decode($_POST["arr_images_serialized"]));

		    $storyTelling = 0;
		    if(!empty($_POST['arr_texts_serialized'])) {
		      $storyTelling = 1;
		      $arr_texts = unserialize(base64_decode($_POST["arr_texts_serialized"]));
		    }

		    $n_images = count ($arr_images);

		    if($storyTelling == 0) {
		       echo '<center>';
		       echo ' <table border=1>';
		       echo '  <tr>';
		       echo '   <td align="justify" width="320">';
		       echo '    You can select / unselect which images will be in your virtual route.<br>';
		       echo '    You can also create a storytelling adding, for each image, a litte text description that will be shown on the image itself.<br>';
		       echo '    <br>';
		       echo '    Tips<br>';
		       echo '    Could be some troubles when the virtual route start with ONE single image that belongs at ONE single Mapillary sequence. <br>';
		       echo '    In this case, try to check this situation using the "Hide / show details" button and, in case, try to change tha starting image or change the starting point of the route <br>';
		       echo '   </td>';
		       echo '  </tr>';
		       echo ' </table>';
		       echo '</center>';

		       echo ' </br>';
		       echo ' </br>';

		       echo ' <form action="step-5.php"  method="POST" target="step-5">';

		       echo '  <center>';
		       echo '   <table border=1>';

		       $current_index = 0;

		       while ($current_index < $n_images )  {
		         echo '   <tr>';
		         echo '    <td align="justify" width="320">';
		      	 echo '     <img src="http://images.mapillary.com/'.$arr_images[$current_index].'/thumb-320.jpg"></br>';
		      	 echo '     Select / unselect image <input type="checkbox" name="piace[]" value="'.$arr_images[$current_index].'§'.$arr_sequences[$current_index].'"  checked />';
		         echo '    </td>';
		         echo '   </tr>';
		         $current_index = $current_index + 1;
		       }
		       echo '   </table>';
		       echo '  </center>';
		    }
		    else {
		         echo '  <center>';
		         echo '   <table border=1>';
		         echo '    <tr>';
		         echo '     <td align="justify" width="640">';
		         echo '      You can select / unselect which images will be in your virtual route.<br>';
		         echo '      You can also create a storytelling adding, for each image, a litte description that will be shown on the image itself.<br>';
		         echo '      <br>';
		         echo '      Tips<br>';
		         echo '      Could be some troubles when tha virtual route start with ONE single image that belongs at ONE single Mapillary sequence.';
		         echo '      In this case, try to check this situation using the "Hide / show details" button and, in case, try to change tha starting images or change the starting point of the route <br>';
		         echo '     </td>';
		         echo '    </tr>';
		         echo '   </table>';
		         echo '  </center>';

		         echo '</br>';
		         echo '</br>';

		         echo '<form action="step-5.php"  method="POST" target="step-5">';

		         echo ' <center>';
		         echo '  <table border="1" id="theTable">';

		         //*** Build the preview of images ...
		         $current_index = 0;
		         while ($current_index < $n_images )  {
		           echo '   <tr>';
		           echo '    <td align="justify" width="320">';
		        	 echo '     <img src="http://images.mapillary.com/'.$arr_images[$current_index].'/thumb-320.jpg"></br>';
		           echo '     <br>';
		           echo '     <div id="details_'.$current_index.'" style="visibility:hidden">';
		           echo '	     Image id = '.$arr_images[$current_index];
		           echo '      <br>';
		           echo '	     Sequence id = '.$arr_sequences[$current_index];
		           echo '      <br>';
		           echo '      <br>';
		           echo '     </div>';
		        	 echo '     Select / unselect image <input type="checkbox" id="'.$current_index.'" name="piace[]" value="'.$arr_images[$current_index].'"  checked />';
		           echo '     <br>';
		           echo '     <br>';
		           echo '    </td>';
		           echo '    <td align="justify" width="320">';
		           echo '	    <center><textarea name="texts[]" rows="4" cols="40" maxlength="110"></textarea></center>';
		           echo '    </td>';
		           echo '   </tr>';
		           $current_index = $current_index + 1;
		         }
		         echo '  </table>';
		         echo ' </center>';
		    }

		    //*** Serialize the images and sequences arrays and put them in hidden inputs type objects ...
		    $arr_sequences_serialized = base64_encode(serialize($arr_sequences));
		    $arr_images_serialized = base64_encode(serialize($arr_images));
		    echo '   <input type="hidden" name="arr_sequences_serialized" value="'.$arr_sequences_serialized.'" />';
		    echo '   <input type="hidden" name="arr_images_serialized" value="'.$arr_images_serialized.'" />';

		    echo '    <center>';
		    echo '     <br>';
		    echo '     <br>';
		    echo '      <input type="submit" value="Check">';
		    echo '     <br>';
		    echo '     <br>';
		    echo '    </center>';
		    echo '   </form>';

		    //*** add the button to hide / show the images details ...
		    echo '   <center>';
		    echo '    <button type="button" onclick="hide_show('.$n_images.')">Hide / show details</button>';
		    echo '   </center>';

		    //*** Add an help page ...
		    echo '   <center>';
		    echo '    <br>';
		    echo '    <a href="#" onclick="window.open(\'help-step-4.html\', \'Help Step 4\', \'width=800, height=600, resizable, status, scrollbars=1, location\');">';
		    echo '     Take a look to use this page ...';
		    echo '    </a>';
		    echo '   </center>';


		    //*** Function to hide / show the images details ...
		    echo '<script  language="javascript">';
		    echo 'function hide_show(n_images)';
		    echo '  {';
		    echo '   current_index = 0;';
		    echo '   while (current_index < n_images )  {';
		    echo '     element = document.getElementById("details_" + current_index );';
		    echo '     if (element.style.visibility == \'visible\') {';
		    echo '      element.style.visibility = \'hidden\'';
		    echo '     }';
		    echo '     else {';
		    echo '      element.style.visibility = \'visible\'';
		    echo '     }';
		    echo '     current_index = current_index + 1;';
		    echo '   }';
		    echo '  }';
		    echo '</script>';
		?>
  </body>
</html>
