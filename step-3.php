<!--
***************************************************************************************************
*** Storytelling Author Mapillary BAsed (SAMBA).
*** Description: Step 3 to create a storytelling based on Mapillary: download the code
***      Author: Cesare Gerbino
***        Code: https://github.com/cesaregerbino/StorytellingAuthorMapillaryBAsed
***     License: MIT (https://opensource.org/licenses/MIT)
***************************************************************************************************
-->

<!doctype html>
<html>
  <head>
    <title>Step 3</title>
  </head>
  <body>
    <?php
    //*** Get the page code to prepare its download...
    $thePage = unserialize(base64_decode($_POST["thePage_serialized"])); //prendo i dati dal form
    ?>
    <center>
      <table>
        <tr>
          <td  colspan="3">
            Now you can download the virtual route preview code to use and adapt it in your web pages<br>
            <br>
          </td>
        </tr>
        <tr>
            <td colspan="3">
             <?php
                echo '<textarea id="inputTextToSave" cols="80" rows="25" >'.$thePage.'</textarea>';
             ?>
            </td>
        </tr>
        <tr>
            <td>Filename to Save As:</td>
            <td><input id="inputFileNameToSaveAs" value="thePage.html"></input></td>
            <td><button onclick="saveTextAsFile()">Save Text to File</button></td>
        </tr>
        <tr>
          <!-- *** Add a help page ... -->
          <td  colspan="3">
            <center>
              <br>
              <br>
              <a href="#" onclick="window.open('help-step-3.html', 'Help Step 3', 'width=800, height=600, resizable, status, scrollbars=1, location')">
        		   Take a look to use this page ...;
        		  </a>
            </center>
          </td>
        </tr>
      </table>
    </center>

    <script type="text/javascript">

    function saveTextAsFile()
    {
      var textToSave = document.getElementById("inputTextToSave").value;
      var textToSaveAsBlob = new Blob([textToSave], {type:"text/plain"});
      var textToSaveAsURL = window.URL.createObjectURL(textToSaveAsBlob);
      var fileNameToSaveAs = document.getElementById("inputFileNameToSaveAs").value;

      var downloadLink = document.createElement("a");
      downloadLink.download = fileNameToSaveAs;
      downloadLink.innerHTML = "Download File";
      downloadLink.href = textToSaveAsURL;
      downloadLink.onclick = destroyClickedElement;
      downloadLink.style.display = "none";
      document.body.appendChild(downloadLink);

      downloadLink.click();
    }

    function destroyClickedElement(event)
    {
      document.body.removeChild(event.target);
    }

    </script>
  </body>
</html>
