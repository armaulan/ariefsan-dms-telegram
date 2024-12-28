<?
// https://ariefsan.basproject.online/telegram/webhook-00-00.php
// https://tlrgm.iccgt.my.id/telegram/webhook-00-00.php
//file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=ping line 4...");
// file_get_contents($config['bot7']  . urlencode("ping line 4...") );
# https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/setWebhook?url=https://ariefsan.basproject.online/telegram/webhook-00-01.php&drop_pending_updates=true
# https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/setWebhook?url=https://ariefsan.crewbasproject.my.id/telegram/webhook-00-01.php&drop_pending_updates=true
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
    'omset-innda' => $config['domain2']. 'execution-1/01-08-omsetdepo.php',
  	'cek-openuser' => $config['domain2']. 'execution-1/02-06-opendrop.php',
  	'cek-username' => $config['domain2']. 'execution-1/02-07-cekusernamedepo.php',
  	'agent-role' => $config['domain2']. 'execution-1/01-12-cekrole.php',
  	'dist-role' => $config['domain2']. 'execution-1/02-12-cekrole.php',
  	'agent-webuser' => $config['domain2']. 'execution-1/01-13-cekrolebyuser.php',
  	'dist-webuser' => $config['domain2']. 'execution-1/02-13-cekrolebyuser.php',
  	'agent-username' => $config['domain2']. 'execution-1/01-14-cekusernameagent.php',
  	'agent-usernamedepo' => $config['domain2']. 'execution-1/01-16-cekuseragent.php',
  	'agent-depo' => $config['domain2']. 'execution-1/01-17-agentdepo.php',
  	'dist-depo' => $config['domain2']. 'execution-1/02-14-distdepo.php',
  	'button-depo' => $config['domain2']. 'execution-1/02-18-cekbuttonshow.php',
  	'disc-store' => $config['domain2']. 'execution-1/02-19-dist-cek-discount-approval.php',
  	'cek-top' => $config['domain2']. 'execution-1/02-21-dist-cek-TOP.php',
  	'product' => $config['domain2']. 'execution-2/01-23-product.php',
  	'dproduct' => $config['domain2']. 'execution-2/02-00-product.php',
  	'stype' => $config['domain2']. 'execution-2/02-dist-storetype.php',
    'depo' => $config['domain2']. 'execution-2/02-dist-product-depo.php', 
    
  	# execution-2
  	'zaf-reset-webhook' => 'https://ariefsan.crewbasproject.my.id/telegram/execution-2/00-00-01-reset-webhook.php'
  
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

