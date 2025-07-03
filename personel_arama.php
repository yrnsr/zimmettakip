<?php
include 'baglanti.php';

$q = '';
if (isset($_GET['q'])) {
  $q = $conn->real_escape_string($_GET['q']);
  $sql = "SELECT PersonelID, Sicil, Ad, Soyad FROM Personel 
          WHERE Ad LIKE '%$q%' OR Soyad LIKE '%$q%' OR Sicil LIKE '%$q%' LIMIT 10";
  $result = $conn->query($sql);

  $data = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $data[] = [
        'id' => $row['PersonelID'],
        'text' => $row['Sicil'] . ' - ' . $row['Ad'] . ' ' . $row['Soyad']
      ];
    }
  }
  header('Content-Type: application/json');
  echo json_encode($data);
}
$conn->close();
?>
