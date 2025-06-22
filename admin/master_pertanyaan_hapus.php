<?php 
include '../koneksi.php';
$id  = $_GET['id'];

// hapus jawaban
mysqli_query($koneksi, "delete from jawaban where jawaban_pertanyaan='$id'");

// hapus pertanyaan
mysqli_query($koneksi, "delete from pertanyaan where pertanyaan_id='$id'");

// hapus polling
mysqli_query($koneksi, "delete from polling where polling_pertanyaan='$id'");

header("location:master.php");