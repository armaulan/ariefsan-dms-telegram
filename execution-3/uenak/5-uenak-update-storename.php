<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/uenak/5-uenak-update-storename.php
# require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$register = [
        "6282180603613" => "1700477784",
        "6282180603613" => "700000000",
        "6281132048631" => "1699500217", #AGA
        "6282188386990" => "1700477784", #NANDA
        "6281328604342" => "1699500217", #RENA -- 1699500217
        "6287815270605" => "1699500217", #DEPOWONOSARI
        "62895414581174" => "1700477784",
        "6285755080563" => "1700476027",
        "6287763952734" => "1699500323", #IFA SEMARANG
        "6281132048781" => "1700477782", #BAYU KEDIRI
        "6282331129943" => "1700477782", #IRA KEDIRI
        "6287840196380" => "1700477782",
        "6285870280236" => "1700516270" #AMEL KEDIRI
    ];


$sender = $_POST['let1'];
$json = json_decode($_POST['let2']);
$name = "";
$depo = "0";
$store = $json[1];

#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=$sender");

if(isset($register[$sender])) {
    
    $depo = $register[$sender];
    
    for ($i = 2; $i < 12; $i++) {
        
        if(isset($json[$i])){
            $tempName = preg_replace('/[^a-zA-Z0-9,-.()]/', '', $json[$i]);
            $name .= $tempName. " ";        
        }
    
    }
} else {
    $config["whatsappSendMessage"]($config['key-wa-bas'],  "Not Registered", $sender, "false");
    exit();
}

$finalName = strtoupper(substr($name, 0, -1));

$payload = [
        'depoCode' => $depo,
        'storeCode' => $store,
        'storeName' => $finalName,
    ];

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-ops.undangsaya.com/api/updatestorename',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array(
    'x-api-key: p1hx92n29d8cb4@!N',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=$response");

$responseData = json_decode($response);
$status = $responseData->status;

if($status == 'success') {
    $newName = $responseData->data->nameAfter;
    $beforeName = $responseData->data->nameBefore;
    $message = "SUCCEED ($newName), Before: $beforeName";
    #file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Succeed ($newName)");
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $message, $sender, "false");
} else {
    #file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Gagal");
    $errMessage = $responseData->error;
    $config["whatsappSendMessage"]($config['key-wa-bas'],  "GAGAL COBA LAGI, EMANG KADANG GANGGUAN- $errMessage", $sender, "false");
} 

