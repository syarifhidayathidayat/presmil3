<?php
$id = $_GET['id']; // Mengambil id dari URL
include 'header.php'; ?>

<div class="container">
    <div class="mb-4 survey">
        <h4>Data Kuesioner Kegiatan Ibu Hamil</h4>
        <small>Detail data kuesioner kegiatan ibu hamil.</small>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-primary btn-sm" href="bumil.php">
                <i class="fa fa-arrow-left"></i> &nbsp Kembali
            </a>
            <a class="btn btn-success btn-sm" href="print_pdf.php?id=<?php echo $id; ?>" target="_blank">
                <i class="fa fa-file-pdf"></i> &nbsp Cetak ke PDF
            </a>
        </div>
    </div>

    <?php
    $id = $_GET['id'];
    $data = mysqli_query($koneksi, "SELECT * FROM pertanyaan_keg WHERE bumil_id='$id'");
    $row = mysqli_fetch_array($data);
    ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-bordered">
                        <tr>
                            <td>Yang dilakukan ibu untuk mengatasi keluhan</td>
                            <td><?php echo $row['atasi_keluhan']; ?></td>
                        </tr>
                        <tr>
                            <td>Kunjungan 0-3 Bulan ke Bidan</td>
                            <td><?php echo $row['kunjungan_0_3_bidan']; ?></td>
                        </tr>
                        <tr>
                            <td>Kunjungan 0-3 Bulan ke Dokter</td>
                            <td><?php echo $row['kunjungan_0_3_dokter']; ?></td>
                        </tr>
                        <tr>
                            <td>Kunjungan 3-7 Bulan ke Bidan</td>
                            <td><?php echo $row['kunjungan_3_7_bidan']; ?></td>
                        </tr>
                        <tr>
                            <td>Kunjungan 7-9 Bulan ke Bidan</td>
                            <td><?php echo $row['kunjungan_7_9_bidan']; ?></td>
                        </tr>
                        <tr>
                            <td>Kunjungan 7-9 Bulan ke Dokter</td>
                            <td><?php echo $row['kunjungan_7_9_dokter']; ?></td>
                        </tr>
                        <tr>
                            <td>Memasang stiker P4K didepan rumah</td>
                            <td><?php echo $row['stiker_p4k']; ?></td>
                        </tr>
                        <tr>
                            <td>Masuk kelas ibu hamil 4x pertemuan</td>
                            <td><?php echo $row['kelas_ibu']; ?></td>
                        </tr>
                        <tr>
                            <td>Rutin minum tablet tambah darah atau vitamin ibu hamil secara teratur</td>
                            <td><?php echo $row['vitamin']; ?></td>
                        </tr>
                        <tr>
                            <td>Berapa kali sudah diberikan Imunisasi Tetanus Toxoid</td>
                            <td><?php echo $row['imunisasi_tt']; ?></td>
                        </tr>
                        <tr>
                            <td>Makanan yang dikonsumsi setiap hari </td>
                            <td><?php echo $row['makanan_harian']; ?></td>
                        </tr>
                        <tr>
                            <td>Persalinan</td>
                            <td><?php echo $row['persalinan']; ?></td>
                        </tr>
                        <tr>
                            <td>Melahirkan di Fasilitas</td>
                            <td><?php echo $row['melahirkan_fasilitas']; ?></td>
                        </tr>
                        <tr>
                            <td>Persiapkan tabungan untuk biaya persalinan/biaya lainnya seperti JKN/BPJS</td>
                            <td><?php echo $row['tabungan']; ?></td>
                        </tr>
                        <tr>
                            <td>Siapkan KTP, Kartu Keluarga, dan keperluan lain untuk ibu dan bayi</td>
                            <td><?php echo $row['identitas']; ?></td>
                        </tr>
                        <tr>
                            <td>Menyiapkan pendamping persalinan seperti suami/keluarga/lainnya</td>
                            <td><?php echo $row['pendamping']; ?></td>
                        </tr>
                        <tr>
                            <td>Menyiapkan kendaraan untuk mengantar bila mau melahirkan</td>
                            <td><?php echo $row['kendaraan']; ?></td>
                        </tr>
                        <tr>
                            <td>Menyiapkan pendonor darah yang memiliki golongan darah yang sama jika diperlukan</td>
                            <td><?php echo $row['pendonor']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>