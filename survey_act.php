<?php
include 'koneksi.php';

require 'assets/plugin/PHPMailer-master/src/Exception.php';
require 'assets/plugin/PHPMailer-master/src/PHPMailer.php';
require 'assets/plugin/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Menangkap data dari formulir
$bumil_email = $_POST['bumil_email'];
$bumil_nama = $_POST['bumil_nama'];
$telp = $_POST['telp'];
$bumil_profesi = $_POST['bumil_profesi'];
$wil_puskes = $_POST['wil_puskes'];
$alamat = $_POST['alamat'];
$hpht = $_POST['hpht'];
$pendapatan = $_POST['pendapatan'];
$periksa_hamil = ($_POST['periksa_hamil'] == 'yes') ? 1 : 0; // Mengubah nilai sesuai dengan radio button
$lila = $_POST['lila'];
$tensi = $_POST['tensi'];
$bayi_tabung = ($_POST['bayi_tabung'] == 'yes') ? 'yes' : 'no'; // Mengubah nilai sesuai dengan radio button
$tablet_tambah_darah = ($_POST['tablet_tambah_darah'] == 'Ya') ? 1 : 0; // Mengubah nilai sesuai dengan radio button
// Kolom baru yang ditambahkan
$pendidikan_terakhir = $_POST['pendidikan_terakhir'];
$berat_badan_sebelum_hamil = $_POST['berat_badan_sebelum_hamil'];
$berat_badan_sekarang = $_POST['berat_badan_sekarang'];
$tinggi_badan = $_POST['tinggi_badan'];

// Cek apakah nomor telepon sudah ada di database
$cek = mysqli_query($koneksi, "SELECT * FROM bumil WHERE telp='$telp'");
$c = mysqli_num_rows($cek);

if ($c > 0) {
	// Jika sudah ada
	$row = mysqli_fetch_assoc($cek);
	$last_id = $row['bumil_id'];
	header("location:kesimpulan.php?id=$last_id");
} else {
	// Jika belum ada, masukkan data baru
	// mysqli_query($koneksi, "INSERT INTO bumil (bumil_email, bumil_nama, telp, bumil_profesi, wil_puskes, alamat, hpht, pendapatan, periksa_hamil, lila, tensi, bayi_tabung, tablet_tambah_darah) VALUES ('$bumil_email','$bumil_nama','$telp','$bumil_profesi','$wil_puskes','$alamat','$hpht','$pendapatan','$periksa_hamil','$lila','$tensi','$bayi_tabung','$tablet_tambah_darah')");
	mysqli_query($koneksi, "INSERT INTO bumil (
    bumil_email, bumil_nama, telp, bumil_profesi, wil_puskes, alamat, hpht, pendapatan, periksa_hamil, lila, tensi, bayi_tabung, tablet_tambah_darah,
    pendidikan_terakhir, berat_badan_sebelum_hamil, berat_badan_sekarang, tinggi_badan
) VALUES (
    '$bumil_email', '$bumil_nama', '$telp', '$bumil_profesi', '$wil_puskes', '$alamat', '$hpht', '$pendapatan', '$periksa_hamil', '$lila', '$tensi', '$bayi_tabung', '$tablet_tambah_darah',
    '$pendidikan_terakhir', '$berat_badan_sebelum_hamil', '$berat_badan_sekarang', '$tinggi_badan'
)");

	// Mendapatkan ID terakhir yang dimasukkan
	$last_id = mysqli_insert_id($koneksi);

	// Lanjutkan untuk menyimpan ke tabel pertanyaan_keg
	$atasi_keluhan = $_POST['atasi_keluhan'];
	$kunjungan_0_3_bidan = $_POST['kunjungan_0_3_bidan'];
	$kunjungan_0_3_dokter = $_POST['kunjungan_0_3_dokter'];
	$kunjungan_3_7_bidan = $_POST['kunjungan_3_7_bidan'];
	$kunjungan_7_9_bidan = $_POST['kunjungan_7_9_bidan'];
	$kunjungan_7_9_dokter = $_POST['kunjungan_7_9_dokter'];
	$stiker_p4k = ($_POST['stiker_p4k'] == 'yes') ? 1 : 0;
	$kelas_ibu = ($_POST['kelas_ibu'] == 'yes') ? 1 : 0;
	$vitamin = ($_POST['vitamin'] == 'yes') ? 1 : 0;
	$imunisasi_tt = $_POST['imunisasi_tt'];
	$makanan_harian = $_POST['makanan_harian'];
	$persalinan = ($_POST['persalinan'] == 'yes') ? 1 : 0;
	$melahirkan_fasilitas = ($_POST['melahirkan_fasilitas'] == 'yes') ? 1 : 0;
	$tabungan = ($_POST['tabungan'] == 'yes') ? 1 : 0;
	$identitas = ($_POST['identitas'] == 'yes') ? 1 : 0;
	$pendamping = ($_POST['pendamping'] == 'yes') ? 1 : 0;
	$kendaraan = ($_POST['kendaraan'] == 'Yes') ? 1 : 0;
	$pendonor = ($_POST['pendonor'] == 'Yes') ? 1 : 0;
	$setuju = ($_POST['setuju'] == 'on') ? 1 : 0;

	// Simpan ke tabel pertanyaan_keg
	mysqli_query($koneksi, "INSERT INTO pertanyaan_keg (bumil_id, atasi_keluhan, kunjungan_0_3_bidan, kunjungan_0_3_dokter, kunjungan_3_7_bidan, kunjungan_7_9_bidan, kunjungan_7_9_dokter, stiker_p4k, kelas_ibu, vitamin, imunisasi_tt, makanan_harian, persalinan, melahirkan_fasilitas, tabungan, identitas, pendamping, kendaraan, pendonor) VALUES ('$last_id','$atasi_keluhan','$kunjungan_0_3_bidan','$kunjungan_0_3_dokter','$kunjungan_3_7_bidan','$kunjungan_7_9_bidan','$kunjungan_7_9_dokter','$stiker_p4k','$kelas_ibu','$vitamin','$imunisasi_tt','$makanan_harian','$persalinan','$melahirkan_fasilitas','$tabungan','$identitas','$pendamping','$kendaraan','$pendonor')");

	// Menyimpan jawaban polling jika ada
	if (isset($_POST['pertanyaan']) && isset($_POST['jawaban'])) {
		$pertanyaan = $_POST['pertanyaan'];
		$jawaban = $_POST['jawaban'];

		$jumlah = count($pertanyaan);
		for ($a = 1; $a <= $jumlah; $a++) {
			$p = $pertanyaan[$a];
			$j = $jawaban[$a];
			mysqli_query($koneksi, "INSERT INTO polling (polling_bumil, polling_pertanyaan, polling_jawaban) VALUES ('$last_id','$p','$j')");
		}
	}

	// Mengirim email pemberitahuan
	$mail = new PHPMailer(true);
	try {
		// Konfigurasi server email
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
		$mail->SMTPAuth = true;
		$mail->Username = 'timpresmil@gmail.com'; // Ganti dengan username SMTP Anda
		$mail->Password = 'kubpdvudcsnikbyv'; // Ganti dengan password SMTP Anda
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->Port = 587;

		// Pengaturan email
		$mail->setFrom('timpresmil@gmail.com', 'Tim Kesehatan Presmil.com');
		$mail->addAddress($email);

		// Konten email
		$mail->isHTML(true);
		$mail->Subject = 'Pemberitahuan Kuesioner Ibu Hamil';
		$mail->Body = "Halo $bumil_nama,<br><br>Terima kasih telah mengisi kuesioner ibu hamil.<br><br>Salam,<br>Tim Kesehatan";
		$mail->AltBody = "Halo $bumil_nama,\n\nTerima kasih telah mengisi kuesioner ibu hamil.\n\nSalam,\nTim Kesehatan";

		$mail->send();
	} catch (Exception $e) {
		echo "Email gagal dikirim. Mailer Error: {$mail->ErrorInfo}";
	}

	// Mengarahkan ke halaman kesimpulan.php dengan membawa parameter id bumil
	header("location:kesimpulan.php?id=$last_id");
}
