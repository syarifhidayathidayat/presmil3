<?php
include 'header.php';
include 'qrcode.php';


$id = $_GET['id'];
$url = 'https://presmil.com/kesimpulan.php?id=' . $id;
$filename = 'qrcode_' . $id . '.png';
$size = 4; // Menentukan ukuran QR Code
$qrcodePath = generateQRCode($url, $filename, $size);
?>
<link href="../css/polkessin.css" rel="stylesheet">

<div class="container">

    <div class="mb-4 survey">
        <h4>Data Kuesioner Ibu Hamil</h4>
        <small>Detail data kuesioner Ibu hamil.</small>
    </div>

    <!-- Tautan print ke PDF  --->
    <div class="row mb-3">
        <div class="col-lg-12">
            <a class="btn btn-primary btn-sm" href="survey.php">
                <i class="fa fa-arrow-left"></i> &nbsp Kembali
            </a>
            <a class="btn btn-success btn-sm" href="print_pdf.php?id=<?php echo $id; ?>" target="_blank">
                <i class="fa fa-file-pdf"></i> &nbsp Cetak ke PDF
            </a>
            <i class="alert alert-warning fa fa-info-circle  alert-blink">&nbsp Silahkan Cetak ke PDF dan download untuk ditunjukkan kepada Tenaga Kesehatan (Bidan/Dokter)</i>
        </div>
    </div>
    <!-- Selesai tautan print -->



    <!-- Menambahkan QR Code -->
    <div class="row mt-4">
        <div class="col-lg-12">

            <img src="<?php echo $qrcodePath; ?>" alt="QR Code">
        </div>
    </div>
    <!-- Selesai QR Code -->

    <div class="card">
        <div class="card-body">
            <div class="col-lg-12">
                <h5>Biodata</h5>
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                        $id = $_GET['id'];
                        $data = mysqli_query($koneksi, "SELECT * FROM bumil INNER JOIN profesi ON bumil.bumil_profesi = profesi.profesi_id WHERE bumil.bumil_id='$id'");

                        while ($d = mysqli_fetch_array($data)) {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td><b>Email</b></td>
                                    <td><?php echo $d['bumil_email']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Nama</b></td>
                                    <td><?php echo $d['bumil_nama']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>No.Telepon</b></td>
                                    <td><?php echo $d['telp']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Profesi</b></td>
                                    <td><?php echo $d['profesi']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Wilayah Puskesmas</b></td>
                                    <td><?php echo $d['wil_puskes']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Alamat</b></td>
                                    <td><?php echo $d['alamat']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Pendidikan Terakhir</b></td>
                                    <td><?php echo $d['pendidikan_terakhir']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Pendapatan</b></td>
                                    <td>Rp. <?php echo number_format($d['pendapatan'], 0, ',', '.'); ?></td>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="col-lg-6">
                        <?php
                        mysqli_data_seek($data, 0); // Mengulang kembali data untuk mencetak kolom kedua

                        while ($d = mysqli_fetch_array($data)) {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>HPHT</td>
                                    <td><?php echo $d['hpht']; ?></td>
                                </tr>

                                <tr>
                                    <td>Periksa Kehamilan</td>
                                    <td><?php echo $d['periksa_hamil'] == 1 ? 'Sudah' : 'Belum'; ?></td>
                                </tr>

                                <tr>
                                    <td>Tensi</td>
                                    <td><?php echo $d['tensi']; ?></td>
                                </tr>
                                <tr>
                                    <td>Program Bayi Tabung</td>
                                    <td><?php echo $d['bayi_tabung'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                </tr>
                                <tr>
                                    <td>Tambah Darah/Vitamin</td>
                                    <td><?php echo $d['tablet_tambah_darah'] == 1 ? 'Ya' : 'Tidak'; ?></td>
                                </tr>
                                <tr>
                                    <td>Tinggi Badan</td>
                                    <td><?php echo $d['tinggi_badan']; ?></td>
                                </tr>
                                <tr>
                                    <td>Berat badan sebelum hamil</td>
                                    <td><?php echo $d['berat_badan_sebelum_hamil']; ?></td>
                                </tr>
                                <tr>
                                    <td>Berat badah saat kehamilan sekarang (Kg)</td>
                                    <td><?php echo $d['berat_badan_sekarang']; ?></td>
                                </tr>
                                <tr>
                                    <td>LILA</td>
                                    <td><?php echo $d['lila']; ?></td>
                                </tr>
                                <!-- <tr>
                    <td>Saran Aplikasi</td>
                    <td><?php echo $d['saran']; ?></td>
                </tr> -->
                            </table>
                        <?php
                        }
                        ?>
                    </div>
                </div>



                <div class="row">
                    <!-- data kuesioner -->
                    <div class="col-lg-6">
                        <h5>Data Kuesioner</h5>
                        <?php
                        $no = 1;
                        $polling = mysqli_query($koneksi, "SELECT * FROM polling 
					INNER JOIN bumil ON polling.polling_bumil = bumil.bumil_id 
					INNER JOIN pertanyaan ON polling.polling_pertanyaan = pertanyaan.pertanyaan_id 
					INNER JOIN jawaban ON polling.polling_jawaban = jawaban.jawaban_id 
					WHERE bumil.bumil_id='$id'");
                        while ($p = mysqli_fetch_array($polling)) {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td width="1%"><?php echo $no++ ?></td>
                                    <td><?php echo $p['pertanyaan']; ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <?php
                                        // Tampilkan jawaban
                                        echo $p['jawaban'];
                                        // Tentukan kondisi beresiko
                                        $beresiko_conditions = array(
                                            array('pertanyaan_id' => 1, 'jawaban' => '1 bulan'),
                                            // Tambahkan kondisi  sesuai dengan pertanyaan dan jawaban yang beresiko
                                        );
                                        // Inisialisasi variabel flag
                                        $beresiko_flag = false;
                                        // Periksa apakah jawaban termasuk dalam kondisi beresiko
                                        foreach ($beresiko_conditions as $condition) {
                                            if ($p['pertanyaan_id'] == $condition['pertanyaan_id'] && $p['jawaban'] == $condition['jawaban']) {
                                                $beresiko_flag = true;
                                                break;
                                            }
                                        }
                                        // Tampilkan keterangan "Beresiko" jika flag masih bernilai true
                                        if ($beresiko_flag) {
                                            echo ' <span class="badge badge-danger">Beresiko</span>';
                                        } else {
                                            echo ' <i class="fa fa-check" style="color: green;"></i>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>
                    </div>

                    <!-- Tambahkan tabel tambahan untuk saran berdasarkan kondisi beresiko -->
                    <div class="col-lg-6">
                        <h5>Diagnosanya dan Pencegahan Komplikasi</h5>
                        <?php
                        // Reset nomor
                        $no = 1;
                        // Kembali ke awal data polling
                        mysqli_data_seek($polling, 0);
                        // Inisialisasi total skor
                        $total_skor = 0;
                        // Tentukan kondisi beresiko dan skor yang terkait
                        $beresiko_conditions = array(
                            array('pertanyaan_id' => 1, 'jawaban' => '1 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '2 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '3 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '4 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '5 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '6 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '7 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '8 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 1, 'jawaban' => '9 bulan', 'skor' => 2),
                            array('pertanyaan_id' => 2, 'jawaban' => '>3', 'skor' => 4),
                            array('pertanyaan_id' => 3, 'jawaban' => '≤ 145 cm', 'skor' => 4),
                            array('pertanyaan_id' => 4, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 5, 'jawaban' => '< 2 Tahun', 'skor' => 4),
                            array('pertanyaan_id' => 5, 'jawaban' => '> 5 Tahun', 'skor' => 4),
                            array('pertanyaan_id' => 6, 'jawaban' => '< 20 tahun', 'skor' => 4),
                            array('pertanyaan_id' => 6, 'jawaban' => '> 35 tahun', 'skor' => 4),
                            array('pertanyaan_id' => 7, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 8, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 9, 'jawaban' => 'Ya', 'skor' => 8),
                            array('pertanyaan_id' => 10, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 11, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 12, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 13, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 14, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 15, 'jawaban' => 'Positif', 'skor' => 4),
                            array('pertanyaan_id' => 16, 'jawaban' => 'Positif', 'skor' => 4),
                            array('pertanyaan_id' => 17, 'jawaban' => 'Positif', 'skor' => 4),
                            array('pertanyaan_id' => 18, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 19, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 20, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 21, 'jawaban' => 'Ya', 'skor' => 8),
                            array('pertanyaan_id' => 22, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 23, 'jawaban' => 'Ya', 'skor' => 8),
                            array('pertanyaan_id' => 24, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 25, 'jawaban' => 'Ya', 'skor' => 8),
                            array('pertanyaan_id' => 26, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 27, 'jawaban' => 'Ya', 'skor' => 4),
                            array('pertanyaan_id' => 28, 'jawaban' => 'Ya', 'skor' => 4),
                            // Tambahkan kondisi berikut sesuai dengan pertanyaan dan jawaban yang beresiko
                        );

                        while ($p = mysqli_fetch_array($polling)) {
                            foreach ($beresiko_conditions as $condition) {
                                if ($p['pertanyaan_id'] == $condition['pertanyaan_id'] && $p['jawaban'] == $condition['jawaban']) {
                                    // Tambahkan skor berdasarkan kondisi yang terpenuhi
                                    $total_skor += $condition['skor'];
                                    break;
                                }
                            }
                        }

                        // Tentukan saran berdasarkan total skor
                        $kelompok_resiko = '';
                        $perawatan = '';
                        $rujukan = '';
                        $tempat = '';
                        $penolong = '';
                        $pencegahan = '';

                        if ($total_skor == 2) {
                            $kelompok_resiko = 'Kehamilan Resiko Rendah (KRR)</br><i>(Kehamilan resiko rendah adalah kehamilan tanpa masalah/ faktor risiko, fisiologis dan kemungkinan besar diikuti oleh persalinan normal dengan ibu dan bayi hidup sehat.)</i> ';
                            $perawatan = 'Bidan';
                            $rujukan = 'Tidak perlu dirujuk';
                            $tempat = 'BPM/Polindes';
                            $penolong = 'Bidan';
                            $pencegahan = 'Tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
                            $alert_class = 'alert-success';
                        } elseif ($total_skor >= 6 && $total_skor <= 10) {
                            $kelompok_resiko = 'Kehamilan Resiko Tinggi (KRT)</br><i>(Kehamilan resiko tinggi adalah kehamilan dengan satu atau lebih faktor risiko, baik dari pihak ibu maupun janinnya yang memberi dampak kurang menguntungkan baik bagi ibu maupun janinnya, memiliki risiko kegawatan tetapi tidak darurat.)</i>';
                            $perawatan = 'Bidan atau Dokter';
                            $rujukan = 'Dilakukan rujukan';
                            $tempat = 'Persalinan bisa di Puskesmas poned atau RS';
                            $penolong = 'Bidan atau Dokter';
                            $pencegahan = 'Persiapan bidan, Alat, Keluarga, Surat , Obat, Kendaraan, Uang, dan pendonor darah(BAKSOKUDA)';
                            $alert_class = 'alert-warning';
                        } elseif ($total_skor > 12) {
                            $kelompok_resiko = 'Kehamilan Resiko Sangat Tinggi (KRST)</br><i>(Kehamilan resiko sangat tinggi adalah kehamilan dengan faktor risiko: memberi dampak gawat dan darurat bagi jiwa ibu dan atau banyinya)</i>';
                            $perawatan = 'Dokter';
                            $rujukan = 'Dirujuk, membutuhkan rujukan tepat waktu dan tindakan segera untuk penanganan adekuat dalam upaya menyelamatkan nyawa ibu dan bayinya.';
                            $tempat = 'Rumah Sakit dengan alat lengkap';
                            $penolong = 'Dokter Spesialis';
                            $pencegahan = 'Persiapan bidan, Alat, Keluarga, Surat , Obat, Kendaraan, Uang dan Pendonor Darah (BAKSOKUDA) dan Persiapan Tindakan operasi';
                            $alert_class = 'alert-danger';
                        } else {
                            $kelompok_resiko = 'Tidak ada resiko';
                            $perawatan = 'Bidan';
                            $rujukan = 'Tidak perlu dirujuk';
                            $tempat = 'BPM/Polindes';
                            $penolong = 'Bidan';
                            $pencegahan = 'Tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
                            $alert_class = 'alert-info'; // Default jika tidak ada skor yang cocok dengan kondisi di atas
                        }
                        ?>
                        <div class="card <?php echo $alert_class; ?>">
                            <div class="card-body">
                                <!-- <h5 class="card-title">Total Skor Risiko</h5> -->
                                <h3 class="card-text">
                                    <div class=" alert-blink" role="alert">
                                        <?php echo "Total Skor: $total_skor"; ?>
                                    </div>
                                </h3>
                                <div class="alert <?php echo $alert_class; ?> mt-2">
                                    <strong>Kelompok Resiko:</strong> <?php echo $kelompok_resiko; ?><br>
                                    <strong>Perawatan:</strong> <?php echo $perawatan; ?><br>
                                    <strong>Rujukan:</strong> <?php echo $rujukan; ?><br>
                                    <strong>Tempat:</strong> <?php echo $tempat; ?><br>
                                    <strong>Penolong:</strong> <?php echo $penolong; ?><br>
                                    <strong>Pencegahan Komplikasi:</strong> <?php echo $pencegahan; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- end  -->



        </div>
    </div>
</div>
<?php include 'footer.php'; ?>