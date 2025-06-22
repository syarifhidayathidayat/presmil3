<?php
require_once('../tcpdf/tcpdf.php');

/// Definisikan path atau URL untuk logo baru
define('NEW_HEADER_LOGO', '../gambar/sistem/user.png');
define('NEW_HEADER_LOGO_WIDTH', 30); // Sesuaikan lebar logo baru

// Buat dokumen PDF baru dengan orientasi landscape
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nama Anda');
$pdf->SetTitle('Data Responden & Kuesioner');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, contoh, tutorial, panduan');

// Ganti judul header dan string header
$pdf->SetHeaderData(NEW_HEADER_LOGO, NEW_HEADER_LOGO_WIDTH, 'Presmil.COM', 'Bermitra dengan Ibu Hamil untuk Prediksi dan Pencegahan Risiko');


// Set font header dan footer
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set font monospace default
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margin
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set rasio skala gambar
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set string bahasa yang bergantung pada bahasa (opsional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// Tambah halaman
$pdf->AddPage();

// Set font lebih kecil
$pdf->SetFont('helvetica', '', 8);

// Set konten untuk dicetak
$html = '
    <h4 style="text-align:center;">Data Responden & Kuesioner</h4>
    <small style="text-align:center;">Responden yang sudah mendaftar & mengisi kuesioner</small>
    <br><br>
    <table border="1" cellspacing="0" cellpadding="3">
        <thead>
            <tr style="background-color:#f2f2f2; text-align:center;">
                <th width="3%">NO</th>
                <th width="10%">Email</th>
                <th width="13%">Nama</th>
                <th width="8%">No. Telp</th>
                <th width="6%">Profesi</th>
                <th width="8%">Wilayah Puskes</th>
                <th width="15%">Alamat</th>
                <th width="7%">HPHT</th>
                <th width="9%">Pendapatan</th>
                <th width="5%">Periksa Hamil</th>
                <th width="3%">LILA</th>
                <th width="6%">Tensi</th>
                <th width="5%">Bayi Tabung</th>
                <th width="5%">Riwayat</th>
            </tr>
        </thead>
        <tbody>';

// Koneksi ke database
include '../koneksi.php';

$query = "SELECT * FROM bumil INNER JOIN profesi ON bumil.bumil_profesi = profesi.profesi_id";
$data = mysqli_query($koneksi, $query);

if (!$data) {
  die("Query Error: " . mysqli_error($koneksi));
}

$no = 1;
while ($d = mysqli_fetch_array($data)) {
  $html .= '<tr>
                <td width="3%" style="text-align:center;">' . $no++ . '</td>
                <td width="10%">' . $d['bumil_email'] . '</td>
                <td width="13%">' . $d['bumil_nama'] . '</td>
                <td width="8%">' . $d['telp'] . '</td>
                <td width="6%">' . $d['profesi'] . '</td>
                <td width="8%">' . $d['wil_puskes'] . '</td>
                <td width="15%">' . $d['alamat'] . '</td>
                <td width="7%">' . $d['hpht'] . '</td>
                <td width="9%" style="text-align:right;">Rp. ' . number_format($d['pendapatan'], 0, ',', '.') . '</td>
                <td width="5%" style="text-align:center;">' . ($d['periksa_hamil'] == 1 ? 'Sudah' : 'Belum') . '</td>
                <td width="3%" style="text-align:center;">' . $d['lila'] . '</td>
                <td width="6%" style="text-align:center;">' . $d['tensi'] . '</td>
                <td width="5%" style="text-align:center;">' . ($d['bayi_tabung'] == 1 ? 'Ya' : 'Tidak') . '</td>
                <td width="5%" style="text-align:center;">' . ($d['riwayat'] == 1 ? 'Ya' : 'Tidak') . '</td>
             </tr>';
}

$html .= '</tbody>
    </table>';

// Cetak teks menggunakan writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Tutup dan keluarkan dokumen PDF
$pdf->Output('data_responden_kuesioner.pdf', 'I');
