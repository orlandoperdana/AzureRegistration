<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=ondowebapp;AccountKey=Tf4H3EVazgZChxYSwtTTX2e3IlXLQfzEjDcm+ngnUfFH+2HfPqpnFv9ZmJOa/Vk2ggJeejyzFXtkInOqcM9whA==;EndpointSuffix=core.windows.net";
$containerName = "ondocontainer";
// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
if (isset($_POST['submit'])) {
    $fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
    $content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
    // echo fread($content, filesize($fileToUpload));
    $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
    header("Location: analisis.php");
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Analisis Gambar</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
</head>

<body>
    <main role="main" class="container">
        <div class="starter-template"> <br><br><br>
            <h1>Analisis Gambar</h1>
            <p class="lead">Pilih Gambar Anda.<br> Kemudian Click <b>Upload</b>, untuk menganalisa foto pilih <b>analisis</b>.</p>
            <span class="border-top my-3"></span>
        </div>
        <div class="mt-4 mb-2">
            <form class="d-flex justify-content-lefr" action="analisis.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
                <input type="submit" name="submit" value="Upload">
            </form>
        </div>
        <br>
        <br>
        <h4>Total Files : <?php echo sizeof($result->getBlobs()) ?></h4>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Nama URL</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                do {
                    foreach ($result->getBlobs() as $blob) {
                        ?>
                        <tr>
                            <td><?php echo $blob->getName() ?></td>
                            <td><?php echo $blob->getUrl() ?></td>
                            <td>
                                <form action="ondovision.php" method="post">
                                    <input type="hidden" name="url" value="<?php echo $blob->getUrl() ?>">
                                    <input type="submit" name="submit" value="Analisis" class="btn btn-primary">
                                </form>
                            </td>
                        </tr>
                    <?php
                }
                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
            } while ($result->getContinuationToken());
            ?>
            </tbody>
        </table>

        </div>

        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script>
            window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
        </script>
        <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
        <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
</body>

</html>