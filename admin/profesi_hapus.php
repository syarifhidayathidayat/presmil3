<?php 
include '../koneksi.php';
$id  = $_GET['id'];

// hapus profesi
mysqli_query($koneksi, "delete from profesi where profesi_id='$id'");


// ubah profesi profesi ke lainnya
mysqli_query($koneksi, "update bumil set profesi='1' where profesi='$id'");
header("location:profesi.php");