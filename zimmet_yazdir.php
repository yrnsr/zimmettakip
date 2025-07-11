<?php
ob_start(); // Output Buffer başlat

require 'vendor/autoload.php';
include 'baglanti.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();

if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Geçersiz istek.");
}

$zimmetID = (int)$_GET['id'];

$sql = "SELECT z.*, p.Ad AS PersonelAd, p.Soyad AS PersonelSoyad, p.Sicil, p.Gorev, p.Departman,
               e.Marka, e.Model, e.EsyaAdi, e.SeriNo, e.Aciklama
        FROM zimmet z
        JOIN Personel p ON z.PersonelID = p.PersonelID
        JOIN Esya e ON z.EsyaID = e.EsyaID
        WHERE ZimmetID = $zimmetID";

$result = $conn->query($sql);

if (!$result || $result->num_rows != 1) {
    die("Kayıt bulunamadı.");
}

$data = $result->fetch_assoc();

$spreadsheet = IOFactory::load("Zimmet Tutanağı.xlsx");
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('D7', $data['PersonelAd'] . ' ' . $data['PersonelSoyad']);
$sheet->setCellValue('D6', $data['Sicil']);
$sheet->setCellValue('D8', $data['Gorev']);
$sheet->setCellValue('D9', $data['Departman']);
$sheet->setCellValue('E18', $data['Marka']);
$sheet->setCellValue('G18', $data['Model']);
$sheet->setCellValue('I18', $data['SeriNo'] . ' ' . $data['Aciklama']);
$sheet->setCellValue('B18', $data['EsyaAdi']);
$sheet->setCellValue('H46', $data['PersonelAd'] . ' ' . $data['PersonelSoyad'] . ' ' . $data['Gorev']);
$sheet->setCellValue('A34', $data['PersonelAd'] . ' ' . $data['PersonelSoyad']);
$sheet->setCellValue('A35', $data['Gorev']);

use PhpOffice\PhpSpreadsheet\RichText\RichText; // RichText ekle

// ...

$isimSoyisim = $data['PersonelAd'] . ' ' . $data['PersonelSoyad'];
$tarih = date('d.m.Y');

// RichText nesnesi oluştur
$richText = new RichText();

// İlk normal metin
$normal1 = $richText->createTextRun("Aşağıda tanımı ve özellikleri belirtilen şirket demirbaşı, ");

// Tarihi kalın yaz
$tarihRun = $richText->createTextRun($tarih);
$tarihRun->getFont()->setBold(true);

// Devam metni
$normal2 = $richText->createTextRun(" tarihinde, şirket çalışanı ");

// İsim soyisim kalın yaz
$isimRun = $richText->createTextRun($isimSoyisim);
$isimRun->getFont()->setBold(true);

// Son metin
$normal3 = $richText->createTextRun("’a teslim edilmiştir.");

// Hücreye ekle
$sheet->getCell('A10')->setValue($richText);


$isimSoyisim = $data['PersonelAd'] . ' ' . $data['PersonelSoyad'];
$tarih = date('d.m.Y');
$yazi = "Aşağıda tanımı ve özellikleri belirtilen şirket demirbaşı, $tarih tarihinde, şirket çalışanı $isimSoyisim'a teslim edilmiştir.";


// Çıktı öncesi temizleme
ob_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="zimmet_tutanagi.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
