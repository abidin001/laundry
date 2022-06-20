<?php
require 'function.php';
$customers = querys("SELECT * FROM `customer`; ");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <title>Laundry</title>
</head>
<style>
  body {
    background-color: whitesmoke;
  }
</style>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-black text-center mt-5">Selamat datang di Laundrry</h1>
        <a href="create/formdtrans.php" class="btn btn-sm btn-success mt-2 mb-3">Tambah Daftar Transaksi</a>
        <table class="table table-hover table-bordered table-sm">
          <thead class="table text-center">
            <tr>
              <!-- <th rowspan="2">Hello</th> -->
              <th scope="col">No</th>
              <th scope="col">Jenis</th>
              <th scope="col">Nama Customer</th>
              <th scope="col">Alamat</th>
              <th scope="col">No HP</th>
              <th scope="col">Tanggal Transaksi</th>
              <th scope="col">Berat Laundry</th>
              <th scope="col">Tanggal Pengambilan</th>
              <th scope="col">Jumlah Dibayar</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody class="text-center table-secondary table-group-divider">
            <?php foreach ($customers as $customer) : ?>
              <?php
              global $connect;
              $customer->jenis = mysqli_query(
                $connect,
                "SELECT `jenis_laundry`.`nama_jenis`, `jenis_laundry`.`harga`
                  FROM `customer` 
                  INNER JOIN `jenis_laundry` 
                  ON `customer`.`id_jenis` = `jenis_laundry`.`id`
                  WHERE `customer`.`id_jenis` = $customer->id_jenis;"
              );
              $customerj = mysqli_fetch_object($customer->jenis);
              $customer->jenis = $customerj->nama_jenis;
              $customer->harga = $customerj->harga;
              $transaksi = query("SELECT * FROM `transaksi` WHERE `id_customer`=$customer->id; ");
              ?>
              <tr>
                <th scope="row"><?= $i++ ?></th>
                <td><?= $customer->jenis ?></td>
                <td><?= $customer->nama_cust ?></td>
                <td><?= $customer->alamat ?></td>
                <td><?= $customer->no_hp ?></td>
                <td><?= $customer->tgl_trans ?></td>
                <td id="berat"><?= $customer->berat_laundry . 'KG' ?></td>
                <?php if (@$transaksi) : ?>
                  <td id="tgl-p"><?= $transaksi->tgl_pengambilan ?></td>
                  <td><?= 'Rp. ' . number_format($transaksi->jumlah_dibayar) ?></td>
                  <td id="id" style="display: none;">
                    <p><?= $customer->id ?></p>
                  </td>
                <?php else : ?>
                  <td><input data-harga="<?= $customer->harga ?>" data-jenis="<?= $customer->id_jenis ?>" data-customer="<?= $customer->id ?>" data-berat="<?= $customer->berat_laundry ?>" id="date" type="date"></td>
                  <td>-</td>
                <?php endif; ?>
                <td>
                  <a class="text-decoration-none text-warning" href="create/formdtrans.php?id=<?= $customer->id ?>">Edit</a> /
                  <a class="text-decoration-none text-danger" href="delete/del.php?id=<?= $customer->id ?>">Hapus</a>
                </td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>





  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      $(document).on('dblclick', 'td#tgl-p', function() {
        $(this).html(`<input id="tgl-p" type="date">`)
        var id = $(this).parent().find('#id').children().text()
        $(document).on('keypress', 'input#tgl-p', function() {
          if (event.keyCode === 13) {
            $.ajax({
              url: "update/updatetrans.php",
              type: "POST",
              data: {
                'id': id,
                'tgl_pengambilan': $("input[type='date']").val(),
              },
              success: function(response) {
                location.reload();
              }
            });
          }
        });
      });
      //Enter keypress date
      $(document).on('keypress', '#date', function() {
        if (event.keyCode === 13) {
          var jenis = Number($(this).data('jenis'));
          var jumlah = Number($(this).data('harga'));
          jumlah = Number($(this).data('berat')) * jumlah;
          $.ajax({
            url: "create/addtrans.php",
            type: "POST",
            data: {
              'id_customer': Number($(this).data('customer')),
              'id_jenis': jenis,
              'tgl_pengambilan': $("input[type='date']").val(),
              'jumlah_dibayar': jumlah
            },
            success: function(response) {
              location.reload();
            }
          });
        }
      });
    });
  </script>
</body>

</html>