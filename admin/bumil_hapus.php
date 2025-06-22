<?php 
include '../koneksi.php';
$id  = $_GET['id'];
$pertanyaan  = $_GET['pertanyaan'];

// hapus bumil
mysqli_query($koneksi, "delete from bumil where bumil_id='$id'");

// hapus polling
mysqli_query($koneksi, "delete from polling where polling_bumil='$id'");

header("location:bumil.php");