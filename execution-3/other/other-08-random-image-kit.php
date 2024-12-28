<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-08-random-image-kit.php
#require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

// ImageKit API credentials
$publicKey = 'public_qf5Oe5/Fz6ALOkKoW8rdhB0bHpU=';
$privateKey = 'private_8hJ331sztTIL4YRW2PuYPHIMPdE=';

// Prepare the API request
$url = 'https://api.imagekit.io/v1/files';
$params = [
    'skip' => 0,
    'limit' => 100,
];
$fullUrl = $url . '?' . http_build_query($params);

// Initialize cURL session
$ch = curl_init();

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . base64_encode($privateKey . ':')
]);

// Execute the cURL request
$response = curl_exec($ch);
$arrResponse = json_decode($response, true);
$total_array = count($arrResponse);
$randomInt = rand(0, $total_array-1);
echo $arrResponse[$randomInt]["url"];




curl_close($ch);
