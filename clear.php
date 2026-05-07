<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

 $dataFile = __DIR__ . '/otp_data.json';
file_put_contents($dataFile, '{}');

echo json_encode(['status' => 'success', 'msg' => 'All OTPs cleared.']);
?>