<?php 
include '../koneksi.php';
$id  = $_POST['id'];
$pertanyaan  = $_POST['pertanyaan'];

mysqli_query($koneksi, "update pertanyaan set pertanyaan='$pertanyaan' where pertanyaan_id='$id'");
header("location:master.php");