<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Use Vercel KV or simple JSON file storage
 $dataFile = __DIR__ . '/otp_data.json';

// Read existing data
 $allData = [];
if (file_exists($dataFile)) {
    $json = file_get_contents($dataFile);
    $allData = json_decode($json, true) ?: [];
}

 $request = array_merge($_GET, $_POST);

if (isset($request['phone']) && !empty($request['phone'])) {
    $phone = trim($request['phone']);
    
    if ((isset($request['otp']) && !empty($request['otp'])) || (isset($request['msg']) && !empty($request['msg']))) {
        $otp = isset($request['otp']) ? trim($request['otp']) : trim($request['msg']);
        
        // Store OTP
        $allData[$phone] = [
            'otp' => $otp,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents($dataFile, json_encode($allData, JSON_PRETTY_PRINT));
        
        echo json_encode([
            'status' => 'success',
            'msg' => $otp,
            'phone' => $phone,
            'time' => date('Y-m-d H:i:s')
        ]);
    } else {
        // Retrieve OTP
        if (isset($allData[$phone])) {
            echo json_encode([
                'status' => 'success',
                'msg' => $allData[$phone]['otp'],
                'phone' => $phone,
                'time' => $allData[$phone]['updated_at']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'No OTP found.']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'msg' => 'Phone number required.']);
}
?>