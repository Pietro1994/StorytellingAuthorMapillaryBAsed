<!--
***************************************************************************************************
*** Storytelling Author Mapillary BAsed (SAMBA).
*** Description: StorytellingDetails: refine the storytelling
***              selecting the images and / or adding description text
***      Author: Cesare Gerbino
***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
***     License: MIT (https://opensource.org/licenses/MIT)
***************************************************************************************************
-->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Preview</title>
    <!-- *** References for Mapillary JS ... -->
    <script src="https://npmcdn.com/mapillary-js@1.0.1/dist/mapillary-js.min.js"></script>
    <link rel="stylesheet" href="https://npmcdn.com/mapillary-js@1.0.1/dist/mapillary-js.min.css"/>

    <style>
      body { background-color: white; }
      .mly-wrapper { margin: 0 auto; }

      .mly-wrapper {
        position: relative;
        background-color: grey;
        width: 640px;
        height: 480px;
      }
      #mly, .mapillary-js {
        position: relative;
        height: 100%;
        width: 100%;
      }
   </style>
  </head>
  <body>
    <div class="mly-wrapper"><div class="mly" id="mly-route"></div></div>
    <br>
    <center><button onclick="play()">Play</button><button onclick="stop()">Stop</button></center>

    <?php
      //*** Build the virtual route preview page and prepare the its code for the download ...
      function storyTellingDetails( $arr_sequences, $arr_images, $arr_texts, $MapillaryApiKey ) {
        //*** The static portion of the page ...
        $thePage = "
        <!DOCTYPE html>
         <html>
          <head>
           <meta charset=\"utf-8\">
           <meta name=\"viewport\" content=\"width=device-width\">
           <title>Preview</title>
           <script src=\"https://npmcdn.com/mapillary-js@1.0.1/dist/mapillary-js.min.js\"></script>
           <link rel=\"stylesheet\" href=\"https://npmcdn.com/mapillary-js@1.0.1/dist/mapillary-js.min.css\"/>
           <style>
             body { background-color: white; }
             .mly-wrapper { margin: 0 auto; }

             .mly-wrapper {
               position: relative;
               background-color: grey;
               width: 640px;
               height: 480px;
             }
             #mly, .mapillary-js {
               position: relative;
               height: 100%;
               width: 100%;
             }
          </style>
         </head>
         <body>
          <div class=\"mly-wrapper\">
           <div class=\"mly\" id=\"mly-route\"></div>
          </div>
          <br>
          <center>
           <button onclick=\"play()\">Play</button><button onclick=\"stop()\">Stop</button>
          </center>
         ";

        // *** Number of images in the array ...
        $n_images = count ($arr_images); //Utilizzo count per contare il numero di valori contenuti nell'array

        $current_index = 0;
        $saved_index = 0;

        //*** Build the page and, in parallel, save the code in thePage variable for the page download ...
        echo "<script>\n";
        $thePage .= "<script>\n";

        echo "var play = undefined;\n";
        $thePage .= "var play = undefined;\n";

        echo "var stop = undefined;\n";
        $thePage .= "var stop = undefined;\n";

        echo "document.addEventListener(\"DOMContentLoaded\", function(event) {\n";
        $thePage .= "document.addEventListener(\"DOMContentLoaded\", function(event) {\n";

        echo "  var mlyRoute = new Mapillary.Viewer('mly-route', '".$MapillaryApiKey."', '".$arr_images[$current_index]."', {cover: true, cache: false, direction: false});\n";
        $thePage .= "  var mlyRoute = new Mapillary.Viewer('mly-route', '".$MapillaryApiKey."', '".$arr_images[$current_index]."', {cover: true, cache: false, direction: false});\n";

        echo "  var route = mlyRoute.getComponent(\"route\");\n";
        $thePage .= "  var route = mlyRoute.getComponent(\"route\");\n";

        echo "  route.configure({paths: [\n";
        $thePage .= "  route.configure({paths: [\n";

        while ($current_index < $n_images )  {
          if ($arr_sequences[$current_index] == $arr_sequences[$saved_index]) {
             $current_index = $current_index + 1;
          }
          else {
             echo "{sequenceKey: \"".$arr_sequences[$saved_index]."\", startKey: \"".$arr_images[$saved_index]."\", stopKey: \"".$arr_images[$current_index -1]."\",\n";
             $thePage .= "{sequenceKey: \"".$arr_sequences[$saved_index]."\", startKey: \"".$arr_images[$saved_index]."\", stopKey: \"".$arr_images[$current_index -1]."\",\n";

             echo "  infoKeys: [\n";
             $thePage .= "  infoKeys: [\n";

             for($i=$saved_index; $i < $current_index; $i++)
             {
               echo "     {key: \"".$arr_images[$i]."\", description: \"".$arr_texts[$i]."\"},\n";
               $thePage .= "     {key: \"".$arr_images[$i]."\", description: \"".$arr_texts[$i]."\"},\n";
             }
             echo "            ]";
             $thePage .= "            ]";

             echo "},";
             $thePage .= "},";

             $saved_index = $current_index;
          }
        }

        echo "{sequenceKey: \"".$arr_sequences[$saved_index]."\", startKey: \"".$arr_images[$saved_index]."\", stopKey: \"".$arr_images[$current_index -1]."\",";
        $thePage .= "{sequenceKey: \"".$arr_sequences[$saved_index]."\", startKey: \"".$arr_images[$saved_index]."\", stopKey: \"".$arr_images[$current_index -1]."\",";

        echo "  infoKeys: [";
        $thePage .= "  infoKeys: [";

        for($i=$saved_index; $i < $current_index; $i++)
        {
          echo "     {key: \"".$arr_images[$i]."\", description: \"".$arr_texts[$i]."\"},";
          $thePage .= "     {key: \"".$arr_images[$i]."\", description: \"".$arr_texts[$i]."\"},";
        }
        echo "            ]";
        $thePage .= "            ]";

        echo "},";
        $thePage .= "},";

        echo "], playing: true});\n";
        $thePage .= "], playing: true});\n";

        echo "mlyRoute.activateComponent(\"route\");\n";
        $thePage .= "mlyRoute.activateComponent(\"route\");\n";

        echo "play = function play() {\n";
        $thePage .= "play = function play() {\n";

        echo "  mlyRoute.deactivateCover();\n";
        $thePage .= "  mlyRoute.deactivateCover();\n";

        echo "  route.play();\n";
        $thePage .= "  route.play();\n";

        echo "}\n";
        $thePage .= "}\n";

        echo "stop = function stop() {\n";
        $thePage .= "stop = function stop() {\n";

        echo "  route.stop()\n";
        $thePage .= "  route.stop()\n";

        echo "}\n";
        $thePage .= "}\n";

        echo "})\n";
        $thePage .= "})\n";

        echo '</script>';
        $thePage .= "</script>";

        $thePage .= "</body>";
        $thePage .= "</html>";

        //*** Return the page code for the download ...
        return $thePage;
        }
      ?>
  </body>
</html>
