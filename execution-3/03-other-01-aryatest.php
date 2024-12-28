<?
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-other-01-aryatest.php
# require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

# "waFooter" => function ($key, $button, $id, $isGroup) {
$config["whatsappSendMessage"]($config['key-wa-bas'],  "6287877719193@s.whatsapp.net" , $config['id-wa-group-fa'], "true");  

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

# $json = json_decode($_POST['let2']);
# $value = $json[1];
#$value = '1437432359';
# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");

# $sql = "";

# $exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# echo mysqli_num_rows($exec);


# $msg = "#02-Dist (Status Store Dist) \n \n";
# if(mysqli_num_rows($exec) > 0) {

/*
$curl = curl_init();
$token = "";
$payload = [
    "data" => [
        [
            'phone' => $config['id-wa-group-fa'],
            'message' => [
                'header' => [
                    'type' => 'text',
                    'content' => 'arya test',
                ],
                'buttons' => ["Reply 1","Reply 2","Reply 3"],
                'content' => 'sending button message.',
                'footer' => 'powered by wablas',
            ],
            'isGroup' => true,
            'source' => 'postman',
        ]
    ]
];
curl_setopt($curl, CURLOPT_HTTPHEADER,
    array(
        "Authorization: " . $config['key-wa-bas'],
        "Content-Type: application/json"
    )
);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/v2/send-button");
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($curl);
curl_close($curl);
echo "<pre>";
print_r($result);
*/