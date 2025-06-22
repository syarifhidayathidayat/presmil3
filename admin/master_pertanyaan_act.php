<?php
include '../koneksi.php';
$pertanyaan  = $_POST['pertanyaan'];

mysqli_query($koneksi, "insert into pertanyaan values (NULL,'$pertanyaan')");
header("location:master.php");
