<?php
$i = 1;
$host = "localhost";
$user = "root";
$pass = "";
$db = "laundry";
$connect = mysqli_connect($host, $user, $pass, $db);
// QUERY
//1. QUERY ALL
function querys($query)
{
  global $connect;
  $result = mysqli_query($connect, $query);
  $rows = [];
  while ($row = mysqli_fetch_object($result)) {
    $rows[] = $row;
  }
  return $rows;
}
//2. QUERY SINGLE
function query($query)
{
  global $connect;
  $result = mysqli_query($connect, $query);
  $row = mysqli_fetch_object($result);
  return $row;
}
//INSERT
//3. INSERT DATA CUSTOMER
function tambahcustomer($data)
{
  global $connect;
  $nama_cust = htmlspecialchars($data['nama']);
  $jenis_laundry = htmlspecialchars($data['id_jenis']);
  $alamat = htmlspecialchars($data['alamat']);
  $no_hp = htmlspecialchars($data['no']);
  $tanggal_transaksi = htmlspecialchars($data['tgl']);
  $berat_laundry = htmlspecialchars($data['berat']);
  $query = "INSERT INTO customer (`id_jenis`, `nama_cust`, `alamat`, `no_hp`, `tgl_trans`, `berat_laundry`) 
    VALUES ('$jenis_laundry', '$nama_cust', '$alamat', '$no_hp', '$tanggal_transaksi', '$berat_laundry')";
  mysqli_query($connect, $query);
  return header('Location: /index.php');
}
//4. INSERT DATA TRANSAKSI
function tambahtrans($data)
{
  global $connect;
  $id_customer = htmlspecialchars($data['id_customer']);
  $id_jenis = htmlspecialchars($data['id_jenis']);
  $tgl_pengambilan = htmlspecialchars($data['tgl_pengambilan']);
  $jumlah_dibayar = htmlspecialchars($data['jumlah_dibayar']);
  $query = "INSERT INTO transaksi 
    VALUES ('$id_customer', '$id_customer', '$id_jenis', '$tgl_pengambilan', '$jumlah_dibayar')";
  mysqli_query($connect, $query);
  return mysqli_affected_rows($connect);
}
//UPDATE 
//5. UPDATE DATA CUSTOMER
function ubahcustomer($data, $id)
{
  global $connect;
  $customer = query('SELECT * FROM customer WHERE id = ' . $id);
  $jenis = query('SELECT * FROM jenis_laundry WHERE id = ' . $data['id_jenis']);

  $nama_cust = htmlspecialchars($data['nama']);
  $jenis_laundry = htmlspecialchars($data['id_jenis']);
  $alamat = htmlspecialchars($data['alamat']);
  $no_hp = htmlspecialchars($data['no']);
  $tanggal_transaksi = htmlspecialchars($data['tgl']);
  $berat_laundry = htmlspecialchars($data['berat']);
  if ($jenis_laundry != $customer->id_jenis || $berat_laundry != $customer->berat_laundry) {
    $query = "UPDATE `transaksi` SET `jumlah_dibayar` = $jenis->harga * $berat_laundry WHERE `id_customer` = $id";
    mysqli_query($connect, $query);
  }
  $query = "UPDATE customer SET `id_jenis` = '$jenis_laundry', `nama_cust` = '$nama_cust', `alamat` = '$alamat', `no_hp` = '$no_hp', `tgl_trans` = '$tanggal_transaksi', `berat_laundry` = '$berat_laundry' WHERE `id` = '$id';";
  mysqli_query($connect, $query);
  return header('Location: /index.php');
}

// 6. UPDATE DATA TRANSAKSI
function ubahtrans($data)
{
  global $connect;
  $tgl_pengambilan = htmlspecialchars($data['tgl_pengambilan']);
  $id = htmlspecialchars($data['id']);
  $query = "UPDATE `transaksi` SET `tgl_pengambilan`='$tgl_pengambilan' WHERE `id_customer` = $id";
  mysqli_query($connect, $query);
  return mysqli_affected_rows($connect);
  // header('Location: /');
}

//DELETE
function hapus($id)
{
  global $connect;
  $query = "DELETE FROM transaksi WHERE id = $id";
  mysqli_query($connect, $query);
  $query = "DELETE FROM customer WHERE id = $id";
  mysqli_query($connect, $query);
  return header('location: /index.php');
}