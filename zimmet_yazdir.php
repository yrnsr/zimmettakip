<?php
require 'vendor/autoload.php';
include 'baglanti.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Session kontrolü ekle (isteğe bağlı)
session_start();

// GET ile zimmetID al
if (!isset($_GET['id'])) {
    die("Geçersiz istek.");
}

$zimmetID = (int)$_GET['id'];

// Veritabanından ilgili zimmet kaydını çek
$sql = "SELECT z.*, p.Ad AS PersonelAd, p.Soyad AS PersonelSoyad, e.MarkaModel AS EsyaMarkaModel
        FROM zimmet z
        JOIN Personel p ON z.PersonelID = p.PersonelID
        JOIN Esya e ON z.EsyaID = e.EsyaID
        WHERE ZimmetID = $zimmetID";
$result = $conn->query($sql);
if (!$result || $result->num_rows != 1) {
    die("Kayıt bulunamadı.");
}
$data = $result->fetch_assoc();

// Excel şablonunu yükle
$spreadsheet = IOFactory::load("Zimmet Tutanağı.xlsx");
$sheet = $spreadsheet->getActiveSheet();

// Hücrelere verileri yaz
$sheet->setCellValue('B2', $data['PersonelAd'] . ' ' . $data['PersonelSoyad']);
$sheet->setCellValue('B3', $data['EsyaMarkaModel']);
$sheet->setCellValue('B4', $data['ZimmetTarihi']);
$sheet->setCellValue('B5', $data['IadeTarihi']);
$sheet->setCellValue('B6', $data['Aciklama']);

// Dosyayı çıktıya hazırla
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="zimmet_tutanagi.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
