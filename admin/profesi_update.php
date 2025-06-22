<?php 
include '../koneksi.php';
$id  = $_POST['id'];
$profesi  = $_POST['profesi'];

mysqli_query($koneksi, "update profesi set profesi='$profesi' where profesi_id='$id'");
header("location:profesi.php");