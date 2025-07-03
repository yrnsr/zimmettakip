<?php
include 'baglanti.php';

$q = '';
if (isset($_GET['q'])) {
  $q = $conn->real_escape_string($_GET['q']);
  $sql = "SELECT EsyaID, MarkaModel, SeriNo FROM Esya 
          WHERE MarkaModel LIKE '%$q%' OR SeriNo LIKE '%$q%' LIMIT 10";
  $result = $conn->query($sql);

  $data = [];
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $data[] = [
        'id' => $row['EsyaID'],
        'text' => $row['MarkaModel'] . ' - ' . $row['SeriNo']
      ];
    }
  }
  header('Content-Type: application/json');
  echo json_encode($data);
}
$conn->close();
?>
