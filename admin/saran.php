<?php
// Load file koneksi.php
include "../koneksi.php";

// Load plugin PHPExcel nya
require_once '../PHPExcel/PHPExcel.php';

// Panggil class PHPExcel nya
$excel = new PHPExcel();

// Settingan awal fil excel
$excel->getProperties()->setCreator('Sistem Informasi Kuesioner Mahasiswa')
  ->setLastModifiedBy('Sistem Informasi Kuesioner Mahasiswa')
  ->setTitle("Data Kuesioner")
  ->setSubject("Kuesioner")
  ->setDescription("Laporan Semua Data Kuesioner")
  ->setKeywords("Data Kuesioner");

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

$excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN KUESIONER IBU HAMIL"); // Set kolom A1 dengan tulisan "DATA SISWA"
$excel->getActiveSheet()->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai F1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1

$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1


$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
$excel->setActiveSheetIndex(0)->setCellValue('B3', "NAMA");
$excel->setActiveSheetIndex(0)->setCellValue('C3', "NO. Telepon");
$excel->setActiveSheetIndex(0)->setCellValue('D3', "profesi");

$excel->setActiveSheetIndex(0)->setCellValueExplicit('E3', "EMAIL", PHPExcel_Cell_DataType::TYPE_STRING);

$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_row);
$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_row);

$huruf = "F";
$pertanyaan = mysqli_query($koneksi, "select * from pertanyaan");
while ($p = mysqli_fetch_array($pertanyaan)) {
  $hurufx = $huruf++;
  $excel->setActiveSheetIndex(0)->setCellValueExplicit($hurufx . '3', $p['pertanyaan'], PHPExcel_Cell_DataType::TYPE_STRING);
  $excel->getActiveSheet()->getStyle($hurufx . '3')->applyFromArray($style_row);
}

// auto width excel
foreach (range('A', $hurufx) as $columnID) {
  $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$excel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);


$sql = mysqli_query($koneksi, "SELECT * FROM bumil,profesi where profesi_id=profesi");
$no = 1; // Untuk penomoran tabel, di awal set dengan 1
$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4

while ($data = mysqli_fetch_array($sql)) {
  $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
  $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data['bumil_nama']);
  $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data['telp']);
  $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data['profesi']);
  $excel->setActiveSheetIndex(0)->setCellValueExplicit('E' . $numrow, $data['bumil_bumil_email'], PHPExcel_Cell_DataType::TYPE_STRING);

  // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
  $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
  $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);

  $id_bumil = $data['bumil_id'];
  $huruf = "F";
  $polling = mysqli_query($koneksi, "SELECT * FROM polling,bumil,pertanyaan,jawaban WHERE bumil_id=polling_bumil AND bumil_id='$id_bumil' AND polling_pertanyaan=pertanyaan_id AND polling_jawaban=jawaban_id");
  while ($p = mysqli_fetch_array($polling)) {
    $hurufx = $huruf++;
    $excel->setActiveSheetIndex(0)->setCellValueExplicit($hurufx . $numrow, $p['jawaban'], PHPExcel_Cell_DataType::TYPE_STRING);
    $excel->getActiveSheet()->getStyle($hurufx . '3')->applyFromArray($style_row);
    $excel->getActiveSheet()->getStyle($hurufx . $numrow)->applyFromArray($style_row);
  }


  $excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(20);

  $no++; // Tambah 1 setiap kali looping
  $numrow++; // Tambah 1 setiap kali looping
}



// Set orientasi kertas jadi LANDSCAPE
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

// Set judul file excel nya
$excel->getActiveSheet(0)->setTitle("Laporan Data Kuesioner");
$excel->setActiveSheetIndex(0);

// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data Laporan Kuesioner.xlsx"'); // Set nama file excel nya
header('Cache-Control: max-age=0');

$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$write->save('php://output');
