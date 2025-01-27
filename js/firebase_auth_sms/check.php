<?php
header('Content-Type: application/json');

include 'config.php';

if (!isset($_POST['token']) || !isset($_POST['code'])) {
    echo json_encode(['status'=>0,'message'=>'Invalid input: verificationId or OTP is missing']);exit;
}

$payload = json_encode([
    'sessionInfo' => $_POST['token'],
    'code' => $_POST['code'],
]);
$url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPhoneNumber?key=" . FIREBASE_API_KEY;
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
$response = curl_exec($curl);
$httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$response = json_decode($response, true);
if ($httpStatusCode === 200) {
    // Successful OTP verification
    echo json_encode([
        'status' => 1,
        'message'=>'Otp verified successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Otp is not valid',
    ]);
}