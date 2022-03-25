<?php

$status = false;
$msg = NULL;

if (isset($_POST['codes']) && !empty($_POST['codes'])) {

  $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

  $PNG_WEB_DIR = 'temp/';

  include "qrlib.php";    

  if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);

  $errorCorrectionLevel = 'L';
  $matrixPointSize = 4;

  $list = explode("\n", $_POST['codes']);

  $jumlah = count($list) - 1;

  $akhir = substr($list[$jumlah], 48, 13);

  $zip = new ZipArchive();

  $zipName = substr($list[0], 48, 13).'-'.$akhir;

  if ($zip->open('temp/'.$zipName.'.zip', ZipArchive::CREATE)!==TRUE) {

    $status = false;
    $msg = 'Cant open path';

  }else{

    foreach ($list as $key => $data) {
      $just_code = substr($data, 48, 13);
      $filename = $PNG_TEMP_DIR.$just_code.'.png';
      QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

      $zip->addFile($filename);
    }

    $status = true;
    $msg = $zip->status;

  }
  $zip->close();

  $download_zip = $zipName.'.zip';

}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>QR Code | Generator</title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/Ionicons/css/ionicons.min.css">

  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style type="text/css">
    textarea {
      resize: none;
    }
  </style>


</head>

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <header class="main-header">
      <nav class="navbar navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <a href="" class="navbar-brand"><b>Berugak</b>TITE</a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>

        </div>

      </nav>
    </header>

    <div class="content-wrapper">
      <div class="container">

        <section class="content-header">
          <h1>
            Generator
            <small>QR Code</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Generator</a></li>
            <li class="active">QR Code</li>
          </ol>
        </section>

        <section class="content">
          <div class="box">
            <form class="form-horizontal" method="post">
              <div class="box-header">
                <h3 class="box-title">Form Generator</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <textarea class="form-control" id="codes" name="codes" rows="15" onchange="setelah_ubah()"><?php if (isset($_POST['code']) && !empty($_POST['codes'])) { echo $_POST['codes']; } ?></textarea>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" id="submit" class="btn btn-primary pull-right" disabled>Generate</button>
              </div>
              <?php if ($status != false) { ?>
                <div class="alert alert-success alert-dismissible">
                  <h4><i class="icon fa fa-check"></i> Success!</h4>
                  <a href="download.php?file=<?php echo $download_zip; ?>" class="btn btn-success"><i class="fa fa-download"></i> Download</a>
                </div>
              <?php } ?>

            </form>
          </div>
        </section>

      </div>

    </div>

    <footer class="main-footer">
      <div class="container">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="https://ljtech.my.id">Berugak-TITE</a>.</strong> All rights
        reserved.
      </div>

    </footer>
  </div>


  <script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <script src="https://adminlte.io/themes/AdminLTE/bower_components/fastclick/lib/fastclick.js"></script>
  <script src="https://adminlte.io/themes/AdminLTE/dist/js/adminlte.min.js"></script>
  <script src="https://adminlte.io/themes/AdminLTE/dist/js/demo.js"></script>

  <script>
    function setelah_ubah() {
      var submitBtn = document.getElementById('submit');

      var text = $("#codes").val();
      var lines = text.split("\n");
      var count = lines.length;

      if (count > 0) {
        submitBtn.disabled=false;
      } else {
        submitBtn.disabled=true;
      }
      submitBtn.innerHTML = `Generate ${count.toLocaleString()} code${(count !== 1)?'s':''}`;
    }
  </script>

</body>
</html>
