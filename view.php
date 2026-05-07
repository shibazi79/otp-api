<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

 $dataFile = __DIR__ . '/otp_data.json';
 $allData = [];

if (file_exists($dataFile)) {
    $allData = json_decode(file_get_contents($dataFile), true) ?: [];
}

echo json_encode([
    'total' => count($allData),
    'otps' => $allData
], JSON_PRETTY_PRINT);
?>