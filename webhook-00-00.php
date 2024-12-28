<?
// https://ariefsan.crewbasproject.my.id/telegram/webhook-00-00.php
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=ping line 4...");
# file_get_contents($config['bot7']  . urlencode("ping line 4...") );
# https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/setWebhook?url=https://tlgrm.iccgt.my.id/ariefsan/telegram/webhook-00-00.php&drop_pending_updates=true
require '00-03-base-config.php';
$config = config();


$data = json_decode(file_get_contents('php://input'), true);
$dataChatIdSender = $data['message']['chat']['id'];
$dataMessage = $data['message']['text'];
$dataMessageArray = explode(" ", $dataMessage);
$dataMessagePass = strtolower($dataMessageArray[0]);
$dataMessageCode = $dataMessageArray[1];

$list = array(
  	# execution-1
    'order-chatbot' => 'https://ariefsan.crewbasproject.my.id/telegram/execution-1/01-05-agent-get-total-order-chatbot.php',
  	'kill-user' => $config['domain2']. 'execution-1/01-01-agent-kill-user-agent.php',
  	'kill-dm' => $config['domain2']. 'execution-1/01-01-agent-kill-user-dist.php',
  	'get-order-all' => $config['domain2']. 'execution-1/01-02-agent-get-data-chatbot1.php',
    'out-order' => $config['domain2']. 'execution-1/01-03-agent-get-outstanding-chatbot1.php',
    'cek-order' => $config['domain2']. 'execution-1/01-04-agent-cek-single-order1.php',
  	'no-dropping' => $config['domain2']. 'execution-1/02-01-dist-cek-nomordropping.php',
  	'no-invoice' => $config['domain2']. 'execution-1/02-02-dist-cek-nomorinvoicing.php',
  	'cek-ktp' => $config['domain2']. 'execution-1/01-03-cekktp.php',
  	'cek-norek' => $config['domain2']. 'execution-1/01-03-ceknorek.php',
  	'gr-agent' => $config['domain2']. 'execution-1/01-06-NomorGR.php',
  	'cek-selling' => $config['domain2']. 'execution-1/01-07-NomorSelling.php',
  	'gr-dist' => $config['domain2']. 'execution-1/02-03-NomorGRDist.php',
  	'apk-agent' => $config['domain2']. 'execution-1/01-09-updateapps.php',
	'apk-dist' => $config['domain2']. 'execution-1/02-04-updateapps.php', 
  	'cek-pk' => $config['domain2']. 'execution-1/01-10-NomorPK.php',
  	'nama-hawker' => $config['domain2']. 'execution-1/01-11-cekname.php',
  	'cek-store'	=> $config['domain2']. 'execution-1/02-10-dist-cek-store.php',
  	'trace-username' => $config['domain2']. 'execution-1/01-14-usernameagent.php',
 	'product-drop' => $config['domain2']. 'execution-1/02-20-dist-cek-drop-product.php',
  	'cl-user' => $config['domain2']. 'execution-1/01-18-CL-user.php',
  	'cek-kurir' => $config['domain2']. 'execution-1/01-19-cekuserkurir.php',
  	'eo' => $config['domain2']. 'execution-1/02-21-cekestimate.php',
  	'coverage' => $config['domain2']. 'execution-1/01-20-coveragechatbot.php',
  	'no-fp' => $config['domain2']. 'execution-1/02-22-fpjk.php',
  	  
  
  	# execution-2
  	'zaf-reset-webhook' => $config['domain2']. 'execution-2/00-00-01-reset-webhook.php'
  
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
	    

}

