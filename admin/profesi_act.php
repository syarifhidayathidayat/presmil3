<?php 
include '../koneksi.php';
$profesi = $_POST['profesi'];

mysqli_query($koneksi, "insert into profesi values (NULL,'$profesi')");
header("location:profesi.php");