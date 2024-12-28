<?php
// https://ariefsan.basproject.online/telegram/00-00-wa.php

$curl = curl_init();
$token = "2P7Vyd5j1EZsU9J6DD6bqubKcCByLcoUqdyRmyBrjSDTAVkvKfOMbHMAApJS9meo";
$data = [
    'phone' => '6281319152872-1588651135',
    'date' => '2022-11-18',
    'time' => '16:16:00',
    'timezone' => 'Asia/Jakarta',
    'message' => 'SAFIRA RIZQI AMALIA PASARIBU',
    'isGroup' => 'true',
    'random' => 'false',
];
curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: $token",
    )
);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-schedule");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($curl);
curl_close($curl);
echo "<pre>";
print_r($result);

?>