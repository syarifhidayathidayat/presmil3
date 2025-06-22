<?php
// Load file koneksi.php
include "../koneksi.php";

// Load plugin PHPExcel
require_once '../PHPExcel/PHPExcel.php';

// Panggil class PHPExcel
$excel = new PHPExcel();

// Setting awal file Excel
$excel->getProperties()->setCreator('Sistem Informasi Kuesioner Mahasiswa')
  ->setLastModifiedBy('Sistem Informasi Kuesioner Mahasiswa')
  ->setTitle("Data Kuesioner")
  ->setSubject("Kuesioner")
  ->setDescription("Laporan Semua Data Kuesioner")
  ->setKeywords("Data Kuesioner");

// Gaya untuk header kolom dan baris
$style_header = array(
  'font' => array(
    'bold' => true,
    'color' => array('rgb' => 'FFFFFF'),
    'size' => 12,
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array('rgb' => 'FFFFFF')
    )
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => '4CAF50')
  )
);

// Gaya untuk sel data
$style_data = array(
  'alignment' => array(
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN,
      'color' => array('rgb' => 'AAAAAA')
    )
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'F3F3F3')
  )
);

// Set judul di baris pertama
$excel->setActiveSheetIndex(0)->setCellValue('A1', "LAPORAN KUESIONER IBU HAMIL");
$excel->getActiveSheet()->mergeCells('A1:Z1'); // Ubah Z sesuai kebutuhan jumlah kolom
$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Set header untuk nomor dan pertanyaan (dimulai dari kolom A dan B)
$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
$excel->setActiveSheetIndex(0)->setCellValue('B3', "PERTANYAAN");
$excel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($style_header);

// Set header untuk nama responden (dimulai dari kolom C)
$responden_query = mysqli_query($koneksi, "SELECT bumil_nama FROM bumil");
$col = 'C';
while ($responden = mysqli_fetch_array($responden_query)) {
  $excel->setActiveSheetIndex(0)->setCellValue($col . '3', $responden['bumil_nama']);
  $excel->getActiveSheet()->getStyle($col . '3')->applyFromArray($style_header);
  $col++;
}

// Mengambil data pertanyaan dan menambahkannya sebagai baris
$pertanyaan_query = mysqli_query($koneksi, "SELECT * FROM pertanyaan");
$row = 4;
$no = 1; // Penomoran pertanyaan
while ($pertanyaan = mysqli_fetch_array($pertanyaan_query)) {
  $excel->setActiveSheetIndex(0)->setCellValue('A' . $row, $no);
  $excel->setActiveSheetIndex(0)->setCellValue('B' . $row, $pertanyaan['pertanyaan']);
  $excel->getActiveSheet()->getStyle('A' . $row . ':B' . $row)->applyFromArray($style_data);

  // Mengambil jawaban untuk setiap pertanyaan berdasarkan ID bumil
  $jawaban_query = mysqli_query($koneksi, "
        SELECT bumil.bumil_id, jawaban.jawaban
        FROM polling
        JOIN jawaban ON polling.polling_jawaban = jawaban.jawaban_id
        JOIN bumil ON polling.polling_bumil = bumil.bumil_id
        WHERE polling.polling_pertanyaan = '" . $pertanyaan['pertanyaan_id'] . "'
    ");

  $col = 'C';
  while ($jawaban = mysqli_fetch_array($jawaban_query)) {
    $excel->setActiveSheetIndex(0)->setCellValue($col . $row, $jawaban['jawaban']);
    $excel->getActiveSheet()->getStyle($col . $row)->applyFromArray($style_data);
    $col++;
  }
  $row++;
  $no++;
}

// Set orientasi kertas jadi LANDSCAPE
$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

// Set judul file excel
$excel->getActiveSheet(0)->setTitle("Laporan Data Kuesioner");
$excel->setActiveSheetIndex(0);

// Proses file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data Laporan Kuesioner.xlsx"');
header('Cache-Control: max-age=0');

$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$write->save('php://output');
