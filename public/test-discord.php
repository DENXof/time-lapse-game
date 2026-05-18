<?php
$webhookUrl = 'https://discord.com/api/webhooks/1505980280224350398/Kv_31HWoMmITq0Etwel4BhE12uJhir5l51J8j-hfLZzekarjEpQIy_M1h1p7_nipwckK';

$data = [
    'content' => 'Тестовое сообщение из PHP! 🎉'
];

$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
