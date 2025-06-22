<?php
// Load file koneksi.php
include "../koneksi.php";

// Load plugin PHPExcel nya
require_once '../PHPExcel/PHPExcel.php';

// Panggil class PHPExcel nya
$excel = new PHPExcel();

// Settingan awal file excel
$excel->getProperties()->setCreator('Sistem Informasi Kuesioner Mahasiswa')
  ->setLastModifiedBy('Sistem Informasi Kuesioner Mahasiswa')
  ->setTitle("Saran Aplikasi")
  ->setSubject("Saran")
  ->setDescription("Saran Semua Data Responden")
  ->setKeywords("Data Saran Kuesioner");

// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
$style_col = array(
  'font' => array('bold' => true), // Set font nya jadi bold
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
$style_row = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
  ),
  'borders' => array(
    'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
    'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
    'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
    'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
  )
);

$excel->setActiveSheetIndex(0)->setCellValue('A1', "Saran KUESIONER IBU HAMIL"); // Set kolom A1 dengan tulisan "DATA SISWA"
$excel->getActiveSheet()->mergeCells('A1:D1'); // Set Merge Cell pada kolom A1 sampai D1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA");
$excel->setActiveSheetIndex(0)->setCellValue('C3', "No.Telepon");
$excel->setActiveSheetIndex(0)->setCellValue('D3', "SARAN");

$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_row);

// Set orientasi kertas jadi LANDSCAPE
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

// Set judul file excel nya
$excel->getActiveSheet(0)->setTitle("Laporan Saran Data Kuesioner");
$excel->setActiveSheetIndex(0);

// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data Laporan Saran Kuesioner.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');

$sql = mysqli_query($koneksi, "SELECT * FROM bumil, profesi WHERE profesi = profesi_id");
$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

while ($data = mysqli_fetch_array($sql)) {
  $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
  $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['bumil_nama']);
  $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['telp']);
  $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['saran']);

  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);

  $no++; // Tambah 1 setiap kali looping
  $numrow++; // Tambah 1 setiap kali looping

}

$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$write->save('php://output');
