<?
# https://ariefsan.crewbasproject.my.id/telegram/webhook-00-03-whacenter-1.php
#require '00-03-base-config.php';
#$config = config();

# Testing trigger ke telegram, cek apakah file ini berhasil di hit oleh wablas
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline7");

# Tampungan semua pesan dari wa blas
#$content = json_decode(file_get_contents('php://input'), true);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". file_get_contents('php://input'));



/*
# Tampung data pesan
$message = $content['message'];
$sender = $content['phone'];
$isGroup = 1;
if($content['isGroup'] == false){
	$isGroup = 0;
}
 # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $isGroup);
 # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=".$sender);

$dataMessageArray = explode(" ", $message);
$dataMessagePass = strtolower($dataMessageArray[0]);
$dataMessageCode = $dataMessageArray[1];
	
  # Buat testing manual
  # $dataMessageArray = array("dropping-dist" , "407006587323041714375602");
  # $dataMessagePass = strtolower($dataMessageArray[0]);
  # $dataMessageCode = $dataMessageArray[1];

$list = array(
  	# execution-3
    'dropping-dist' => $config['domain2']. 'execution-3/03-dist-01-cekdropping.php',
  	'invoice-dist' => $config['domain2']. 'execution-3/03-dist-01-cekinvoicing.php',
  	'pk-dist' => $config['domain2']. 'execution-3/03-dist-01-cekpk.php',
  	'store-dist' => $config['domain2']. 'execution-3/03-dist-02-cekstore.php',
    'server-delay' => $config['domain2']. 'execution-3/03-server-01-delayReporting.php',
    'list-command' => $config['domain2']. 'execution-3/list-command.php',
  	'tic-entry' => $config['domain2']. 'execution-3/ticket/03-01-ticket-entry.php',
    'tic-list' => $config['domain2']. 'execution-3/ticket/03-02-ticket-showlist.php',
  	'tic-update' => $config['domain2']. 'execution-3/ticket/03-01-ticket-update.php',
  	'tic-cancel' => $config['domain2']. 'execution-3/ticket/03-03-ticket-cancel.php',
  	'notes-dist' => $config['domain2']. 'execution-3/03-dist-03-settlementdaily.php',
  	'notes-agent' => $config['domain2']. 'execution-3/03-agent-01-settlementdaily.php',
  	'gr-agent' => $config['domain2']. 'execution-3/03-agent-02-cekGR.php',
    'fa-dist-rbp' => $config['domain2']. 'execution-3/03-dist-04-01CekSalesRBPInndi.php',
  	'fa-agent-rbp' => $config['domain2']. 'execution-3/03-agent-03-01CekSalesRBPInnda.php',
  	'rep-dist-estdrp' => $config['domain2']. 'execution-3/report/01-dist-01-EstimateVsDropping.php',
  	'fa-report-topjwk' => $config['domain2']. 'execution-3/report/01-dist-02-fa-topjwk-1.php',
  	'fa-report-storedata1' => $config['domain2']. 'execution-3/report/01-dist-03-fa-storedata-1.php',
  	'fa-chart-stockinnda' => $config['domain2']. 'execution-3/report/02-agent-01-fa-stockchart.php',
  	'fa-dist-stockbs' => $config['domain2']. 'execution-3/fainn/fainn-01-dist-CekCurrentBSDepo.php',
  	'fa-agent-stockbs' => $config['domain2']. 'execution-3/fainn/fainn-03-agent-CekCurrentBSDepo.php',
	'fa-agent-stockdepo' => $config['domain2']. 'execution-3/fainn/fainn-04-agent-CekStockDepo.php',
  	'fa-dist-stockdepo' => $config['domain2']. 'execution-3/fainn/fainn-02-dist-CekStockDepo.php',
  	'other-nindya' => $config['domain2']. 'execution-3/other/other-01-didyouknow.php',
  	'other-news' => $config['domain2']. 'execution-3/other/other-02-topnews.php',
  	'other-ai' => $config['domain2']. 'execution-3/other/other-04-chatgpt-2.php',
  	'other-ai-img' => $config['domain2']. 'execution-3/other/other-5-chatgpt-image.php',
  	'clara-ai-img' => $config['domain2']. 'execution-3/other/other-5-chatgpt-image.php',
  	'clara-ai' => $config['domain2']. 'execution-3/other/other-03-chatgpt.php',
  	'ar' => $config['domain2']. 'execution-3/03-dist-05-arinn.php',
  
  	# agent
  	'agent-depo' => $config['domain2']. 'execution-3/agent/agent-01-deposearch.php',
  	'agent-user' => $config['domain2']. 'execution-3/agent/agent-02-depoUserDetail.php',
  
  
);

$post = function($dataChatIdSender, $dataMessageArray, $url, $isGroup){
    # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline25");
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "let1=$dataChatIdSender&let2=$dataMessageArray&let3=$isGroup");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close($ch);
};


if(isset($list[$dataMessagePass])){
  	# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline42");
    $json = json_encode($dataMessageArray, true);
    $post($sender, $json, $list[$dataMessagePass], $isGroup);
 
} else {
    # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0936");
	exit();
}
*/
































