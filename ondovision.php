<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: analisis.php");
	}
} else {
	header("Location: analisis.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Contoh Analisa</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>

	<script language="javascript">
        document.getElementById('analyze_btn').click(); 
    </script>
<body>

    <script type="text/javascript">
        function processImage() {
            // **********************************************
            // *** Update or verify the following values. ***
            // **********************************************

            // Replace <Subscription Key> with your valid subscription key.
            var subscriptionKey = "d69d92e8fb9e4f77b631687c39b8fc64";

            // You must use the same Azure region in your REST API method as you used to
            // get your subscription keys. For example, if you got your subscription keys
            // from the West US region, replace "westcentralus" in the URL
            // below with "westus".
            //
            // Free trial subscription keys are generated in the "westus" region.
            // If you use a free trial subscription key, you shouldn't need to change
            // this region.
            var uriBase =
                "https://southeastasia.api.cognitive.microsoft.com/";

            // Request parameters.
            var params = {
                "visualFeatures": "Categories,Description,Color",
                "details": "",
                "language": "en",
            };

            // Display the image.
            var sourceImageUrl = document.getElementById("inputImage").value;
            document.querySelector("#sourceImage").src = sourceImageUrl;

            // Make the REST API call.
            $.ajax({
                    url: uriBase + "?" + $.param(params),

                    // Request headers.
                    beforeSend: function(xhrObj) {
                        xhrObj.setRequestHeader("Content-Type", "application/json");
                        xhrObj.setRequestHeader(
                            "Ocp-Apim-Subscription-Key", subscriptionKey);
                    },

                    type: "POST",

                    // Request body.
                    data: '{"url": ' + '"' + sourceImageUrl + '"}',
                })

                .done(function(data) {
                    // Show formatted JSON on webpage.
                    $("#responseTextArea").val(JSON.stringify(data, null, 2));
                    //$("#description").text(data.description.captions[0].text);
                })

                .fail(function(jqXHR, textStatus, errorThrown) {
                    // Display error message.
                    var errorString = (errorThrown === "") ? "Error. " :
                        errorThrown + " (" + jqXHR.status + "): ";
                    errorString += (jqXHR.responseText === "") ? "" :
                        jQuery.parseJSON(jqXHR.responseText).message;
                    alert(errorString);
                });
        };
    </script>
<h1>Analisis Gambar:</h1>
Tekan tombol <strong>Analisis image</strong> untuk memulai proses analisis gambar.
<br><br>
URL gambar:
<input type="text" name="inputImage" id="inputImage"
    value="<?php echo $url ?>" readonly />
<button id="analyze_btn" onclick="processImage()">Analisis Gambar</button>
<br><br>
<script language="javascript">
document.getElementById('analyze_btn').click(); 
</script>
<div id="wrapper" style="width:1020px; display:table;">
    <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br><br>
        <textarea id="responseTextArea" class="UIInput"
                  style="width:580px; height:400px;"></textarea>
    </div>
    <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br><br>
        <img id="sourceImage" width="400" />
    </div>
    </div>
</body>

</html>
