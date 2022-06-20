<?php
require_once '../function.php';
if (@$_GET['id']) {
  $customer = query("SELECT * FROM customer WHERE id = '$_GET[id]'");
  global $connect;
  $customer->jenis = mysqli_query(
    $connect,
    "SELECT `jenis_laundry`.`nama_jenis` 
                  FROM `customer` 
                  INNER JOIN `jenis_laundry` 
                  ON `customer`.`id_jenis` = `jenis_laundry`.`id`
                  WHERE `customer`.`id_jenis` = $customer->id_jenis;"
  );
  $customer->jenis = mysqli_fetch_object($customer->jenis);
  $customer->jenis = $customer->jenis->nama_jenis;
}
$jeniss = querys("SELECT * FROM `jenis_laundry`; ");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <title>Form <?= @$_GET['id'] ? 'Edit' : 'Tambah'; ?></title>
  <style>
    body {
      background-color: whitesmoke ;
    }
    a {
      text-decoration: none;
    }
  </style>
</head>
<div class="container mt-5">
  <div class="row d-flex justify-content-center">
    <div class="col-lg-6">
      <form action="<?= @$customer ? '../update/updatecust.php?id=' . $customer->id : 'addcust.php' ?>" method="POST">
        <div class="mb-3">
          <label for="jenis" class="form-label">Jenis : </label>
          <select class="form-control text-center" name="id_jenis" id="jenis">
            <?php foreach ($jeniss as $jenis) : ?>
              <option value="<?= $jenis->id; ?>" <?= @$customer->id_jenis == $jenis->id ? 'selected' : '' ?>><?= $jenis->nama_jenis; ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Customer : </label>
          <input value="<?= @$customer ? @$customer->nama_cust : '' ?>" type="text" name="nama" class="form-control" id="nama">
        </div>
        <div class="mb-3">
          <label for="alamat" class="form-label">Alamat : </label>
          <textarea name="alamat" class="form-control" id="alamat" cols="5" rows="3"><?= @$customer ? @$customer->alamat : '' ?></textarea>
        </div>
        <div class="mb-3">
          <label for="no" class="form-label">No HP : </label>
          <input value="<?= @$customer ? @$customer->no_hp : '' ?>" type="number" name="no" class="form-control" id="no">
        </div>
        <div class="mb-3">
          <label for="tgl" class="form-label">Tanggal Transaksi : </label>
          <input value="<?= @$customer ? @$customer->tgl_trans : '' ?>" type="date" name="tgl" class="form-control" id="tgl">
        </div>
        <div class="mb-3">
          <label for="berat" class="form-label">Berat Laundry : </label>
          <input value="<?= @$customer ? @$customer->berat_laundry : '' ?>" type="number" name="berat" class="form-control" id="berat">
        </div>
        <div class="d-flex justify-content-end">
          <button href="../index.php" type="submit" class="btn btn-primary"><a href="../index.php" style="color :white">Tambah</a></button>
          
        </div>
      </form>
    </div>
  </div>
</div>

<body>

</body>

</html>