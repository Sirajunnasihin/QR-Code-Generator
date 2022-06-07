## Welcome to QR Code Generator

Ini adalah contoh penerapan dari [PHP QR Code Encoder](http://phpqrcode.sourceforge.net/) dengan bulk fungsi pada generate qr code dengan form input dari text area.

### Code

Markdown is a lightweight and easy-to-use syntax for styling your writing. It includes conventions for

```markdown
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
```

dan form sebagai berikut
```
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
```
