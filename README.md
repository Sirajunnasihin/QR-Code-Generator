## Welcome to QR Code Generator

Ini adalah contoh penerapan dari [PHP QR Code Encoder](http://phpqrcode.sourceforge.net/) dengan bulk fungsi pada generate qr code dengan form input dari text area.

### How to use

Lakukan pengecekan terhadap value inputan tidak dalam keadaan kosong saat submit

```markdown
if (isset($_POST['codes']) && !empty($_POST['codes'])) {
```
Load dan cek directory lokasi file qr code tersimpan exist atau tidak
```
  $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;

  $PNG_WEB_DIR = 'temp/';

  include "qrlib.php";    

  if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);
```
Konfigurasi kualitas QR Code yang akan digenerate dengan level kesalahan / error correction level (L=Low, M=Medium dan H=High)
```
  $errorCorrectionLevel = 'L';
  $matrixPointSize = 4;
 ```
Ambil data dari inputan yg dikasus ini menggunakan text area, 
Data yang akan dibuatkan qr code jika lebih dari satu, maka data dapat dipisahkan menggunakan enter pada form input agar qr code yg digenerate lebih dari satu dan memerlukan perlu pemisahan terhadap data dengan pemisah enter (\n) menggunakan code berikut
```
  $list = explode("\n", $_POST['codes']);
```
Jika membutuhkan hasil qr code yg digenerate langsung dikompres, maka bisa menggunakan code berikut
```
  $jumlah = count($list) - 1;

  $akhir = substr($list[$jumlah], 48, 13);

  $zip = new ZipArchive();

  $zipName = substr($list[0], 48, 13).'-'.$akhir;
  ```
  Setelah berhasil memisahkan data dari enter, data tersebut akan tersimpan ke dalam bentuk array dan memerlukan loop untuk mengambil data tersebut satu-persatu. Dan berikut adalah caranya
 ```
    foreach ($list as $key => $data) {
      $just_code = substr($data, 48, 13);
      $filename = $PNG_TEMP_DIR.$just_code.'.png';
      QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    }
```
dan jika ingin memasukkan qr code yg telah digenerate kedalam zip, maka perlu menambahkan code berikut ke dalam loop nya
```
$zip->addFile($filename);
```

Dan berikut adalah contoh form inputan yg digunakan
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

Selamat mencoba
