<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['message']) || trim($input['message']) === '') {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$message = $input['message'];
$apiKey = 'AIzaSyBUjM7s_Z6Ys0bQR5c0TMWS74uxSQvok-g';

$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'contents' => [
        [
            'parts' => [
                ['text' => $message]
            ]
        ]
    ]
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
    $errorMsg = curl_error($ch);
    curl_close($ch);
    echo json_encode(['error' => "Curl error: {$errorMsg}"]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode === 200 && isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    echo json_encode(['reply' => $data['candidates'][0]['content']['parts'][0]['text']]);
} elseif (isset($data['error']['message'])) {
    echo json_encode(['error' => $data['error']['message']]);
} else {
    echo json_encode(['error' => 'Unknown error']);
}

curl_close($ch);

