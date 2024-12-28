<?
$data = json_decode(file_get_contents('php://input'), true);


$dataChatIdSender = $data['message']['chat']['id'];
$dataMessage = $data['message']['text'];
$dataMessageArray = explode(" ", $dataMessage);
$dataMessagePass = $dataMessageArray[0];
$dataMessageCode = $dataMessageArray[1];

$list = array(
    
    'trace-get-ktp' => 'https://dmsproject.xyz/dmsbot/dic5000/api-agent-00-00-01-get-ktp-hawker.php',
    'cek-server' => 'https://dmsproject.xyz/dmsbot/dic5000/api-server-00-00-01-cek-synch-report-server.php',
    'get-log-server' => 'https://dmsproject.xyz/dmsbot/dic5000/api-server-00-00-02-send-file-log-server.php'
    
);



$post = function($dataChatIdSender, $dataMessageArray, $url){
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "let1=$dataChatIdSender&let2=$dataMessageArray&let3=$url");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);
};


if(isset($list[$dataMessagePass])){
    $json = json_encode($dataMessageArray, true);
    $post($dataChatIdSender, $json, $list[$dataMessagePass]);
} else {
    //file_get_contents($config['bot7']  . urlencode("ping line 38...") );
    close();
}