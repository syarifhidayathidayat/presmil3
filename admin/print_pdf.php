<?php
require_once('../tcpdf/tcpdf.php');

// Koneksi ke database
include '../koneksi.php';
include 'qrcode.php'; // QR Code generator


if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}

// Ambil data dari URL
$id = $_GET['id'];

// Query untuk mendapatkan data bumil dan profesi
$data = mysqli_query($koneksi, "SELECT * FROM bumil INNER JOIN profesi ON bumil.bumil_profesi = profesi.profesi_id WHERE bumil.bumil_id='$id'");
$d = mysqli_fetch_array($data);

// Membuat kelas kustom untuk TCPDF
class MYPDF extends TCPDF
{
    // Menambahkan header
    public function Header()
    {
        // Logo
        $this->Image('../gambar/sistem/presmil.png', 10, 10, 20);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->Cell(0, 12, 'PREDIKSI & PENCEGAHAN ', 0, 1, 'C');
        $this->Cell(0, -10, 'Resiko Ibu Hamil Berbasis Online', 0, 1, 'C');
    }

    // Menambahkan footer
    public function Footer()
    {
        // Posisi di 15 mm dari bawah
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Nomor halaman
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Inisialisasi PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('presmil.com');
$pdf->SetTitle('Laporan Data Kuesioner Ibu Hamil');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set margin
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Tambahkan halaman pertama
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);
$url = '/presmil2/admin/bumil_survey.php?id=' . $id;
$filename = 'qrcode_' . $id . '.png';
$size = 4;
$qrcodePath = generateQRCode($url, $filename, $size);
$pdf->Image($qrcodePath, 173, 5, 25);
// Menambahkan informasi data ibu hamil
$html = '<h4>Data Ibu Hamil</h4>';
$html .= '<table border="1" cellpadding="5">';
$html .= '<tr><td>Email</td><td>' . $d['bumil_email'] . '</td></tr>';
$html .= '<tr><td>Nama</td><td>' . $d['bumil_nama'] . '</td></tr>';
$html .= '<tr><td>No. Telepon</td><td>' . $d['telp'] . '</td></tr>';
$html .= '<tr><td>Profesi</td><td>' . $d['profesi'] . '</td></tr>';
$html .= '<tr><td>Wilayah Puskesmas</td><td>' . $d['wil_puskes'] . '</td></tr>';
$html .= '<tr><td>Alamat</td><td>' . $d['alamat'] . '</td></tr>';
$html .= '<tr><td>Alamat</td><td>' . $d['alamat'] . '</td></tr>';
$html .= '<tr><td>HPHT</td><td>' . $d['hpht'] . '</td></tr>';
$html .= '<tr><td>Pendapatan</td><td>Rp. ' . number_format($d['pendapatan'], 0, ',', '.') . '</td></tr>';
$html .= '<tr><td>Periksa Kehamilan</td><td>' . ($d['periksa_hamil'] == 1 ? 'Sudah' : 'Belum') . '</td></tr>';
$html .= '<tr><td>Tensi</td><td>' . $d['tensi'] . '</td></tr>';
$html .= '<tr><td>Program Bayi Tabung</td><td>' . ($d['bayi_tabung'] == 1 ? 'Ya' : 'Tidak') . '</td></tr>';
$html .= '<tr><td>Tambah Darah/Vitamin</td><td>' . ($d['tablet_tambah_darah'] == 1 ? 'Ya' : 'Tidak') . '</td></tr>';
$html .= '<tr><td>Berat badan sebelum hamil (Kg)</td><td>' . $d['berat_badan_sebelum_hamil'] . '</td></tr>';
$html .= '<tr><td>Berat badah saat kehamilan sekarang (Kg)</td><td>' . $d['berat_badan_sekarang'] . '</td></tr>';
$html .= '<tr><td>LILA</td><td>' . $d['lila'] . '</td></tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Tambahkan halaman kedua untuk Data Kuesioner
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Data Kuesioner
$html = '<h4>Faktor Resiko</h4>';
$html .= '<table border="1" cellpadding="5">';
$polling = mysqli_query($koneksi, "SELECT * FROM polling 
INNER JOIN bumil ON polling.polling_bumil = bumil.bumil_id 
INNER JOIN pertanyaan ON polling.polling_pertanyaan = pertanyaan.pertanyaan_id 
INNER JOIN jawaban ON polling.polling_jawaban = jawaban.jawaban_id 
WHERE bumil.bumil_id='$id'");

while ($p = mysqli_fetch_array($polling)) {
    $html .= '<tr><td>' . $p['pertanyaan'] . '</td><td>' . $p['jawaban'] . '</td></tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Perhitungan Skor Risiko dan Diagnosa
$total_skor = 0;
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

mysqli_data_seek($polling, 0);
while ($p = mysqli_fetch_array($polling)) {
    foreach ($beresiko_conditions as $condition) {
        if ($p['pertanyaan_id'] == $condition['pertanyaan_id'] && $p['jawaban'] == $condition['jawaban']) {
            $total_skor += $condition['skor'];
            break;
        }
    }
}

$kelompok_resiko = '';
$perawatan = '';
$rujukan = '';
$tempat = '';
$penolong = '';
$pencegahan = '';

if ($total_skor == 2) {
    $kelompok_resiko = 'Kehamilan Resiko Rendah (KRR)Kehamilan resiko rendah adalah kehamilan tanpa masalah/ faktor risiko, fisiologis dan kemungkinan besar diikuti oleh persalinan normal dengan ibu dan bayi hidup sehat. ';
    $perawatan = 'Bidan';
    $rujukan = 'Tidak perlu dirujuk';
    $tempat = 'BPM/Polindes';
    $penolong = 'Bidan';
    $pencegahan = 'Tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
} elseif ($total_skor >= 6 && $total_skor <= 10) {
    $kelompok_resiko = 'Kehamilan Resiko Tinggi (KRT)<i>Kehamilan resiko tinggi adalah kehamilan dengan satu atau lebih faktor risiko, baik dari pihak ibu maupun janinnya yang memberi dampak kurang menguntungkan baik bagi ibu maupun janinnya, memiliki risiko kegawatan tetapi tidak darurat.</i>';
    $perawatan = 'Bidan atau Dokter';
    $rujukan = 'Dilakukan rujukan';
    $tempat = 'Persalinan bisa di Puskesmas poned atau RS';
    $penolong = 'Bidan atau Dokter';
    $pencegahan = 'Persiapan bidan, Alat, Keluarga, Surat , Obat, Kendaraan, Uang ';
} elseif ($total_skor > 12) {
    $kelompok_resiko = 'Kehamilan Resiko Sangat Tinggi (KRST)Kehamilan resiko sangat tinggi adalah kehamilan dengan faktor risiko: memberi dampak gawat dan darurat bagi jiwa ibu dan atau banyinya';
    $perawatan = 'Dokter';
    $rujukan = 'Dirujuk, membutuhkan rujukan tepat waktu dan tindakan segera untuk penanganan adekuat dalam upaya menyelamatkan nyawa ibu dan bayinya.';
    $tempat = 'Rumah Sakit dengan alat lengkap';
    $penolong = 'Dokter Spesialis';
    $pencegahan = 'Persiapan bidan, Alat, Keluarga, Surat , Obat, Kendaraan, Uang dan Pendonor Darah (BAKSOKUDA) dan Persiapan Tindakan operasi';
} else {
    $kelompok_resiko = 'Tidak ada resiko';
    $perawatan = 'Bidan';
    $rujukan = 'Tidak perlu dirujuk';
    $tempat = 'BPM/Polindes';
    $penolong = 'Bidan';
    $pencegahan = 'Tetap konsumsi makanan sehat, jaga kesehatan, tidur yang cukup, jaga kebiasaan baik agar kesehatan janin dan ibu selalu terjaga.';
}

// Tambahkan informasi Diagnosanya dan Pencegahan Komplikasi
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$html = '<h4>Diagnosanya dan Pencegahan Komplikasi</h4>';
$html .= '<table border="1" cellpadding="5">';
$html .= '<tr><td><strong>Total Skor:</strong></td><td>' . $total_skor . '</td></tr>';
$html .= '<tr><td><strong>Kelompok Resiko:</strong></td><td>' . $kelompok_resiko . '</td></tr>';
$html .= '<tr><td><strong>Perawatan:</strong></td><td>' . $perawatan . '</td></tr>';
$html .= '<tr><td><strong>Rujukan:</strong></td><td>' . $rujukan . '</td></tr>';
$html .= '<tr><td><strong>Tempat:</strong></td><td>' . $tempat . '</td></tr>';
$html .= '<tr><td><strong>Penolong:</strong></td><td>' . $penolong . '</td></tr>';
$html .= '<tr><td><strong>Pencegahan Komplikasi:</strong></td><td>' . $pencegahan . '</td></tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Laporan_Data_Kuesioner.pdf', 'I');
