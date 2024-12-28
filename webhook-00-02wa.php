<?
# https://tlgrm.iccgt.my.id/ariefsan/telegram/webhook-00-02wa.php
require '00-03-base-config.php';
$config = config();

# Testing trigger ke telegram, cek apakah file ini berhasil di hit oleh wablas
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=webhook-00-02wa.php");

# Tampungan semua pesan dari wa blas
$content = json_decode(file_get_contents('php://input'), true);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". file_get_contents('php://input'));

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
#$dataMessageCode = $dataMessageArray[1];
	
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
  	
  	# fa-report
  	'fa-report-topjwk' => $config['domain2']. 'execution-3/report/01-dist-02-fa-topjwk-1.php',
  	'fa-report-storedata1' => $config['domain2']. 'execution-3/report/01-dist-03-fa-storedata-1.php',
  	'fa-report-storedata2' => $config['domain2']. 'execution-3/report/01-dist-04-fa-storedata-2.php',
  	'fa-report-storedata3' => $config['domain2']. 'execution-3/report/01-dist-05-fa-storedata-3.php',
  	'fa-report-storedata4' => $config['domain2']. 'execution-3/report/01-dist-05-fa-storedata-5.php',
  	'fa-report-storedata6' => $config['domain2']. 'execution-3/report/01-dist-05-fa-storedata-6.php',
   	'fa-report-po-choco' => $config['domain2']. 'execution-3/report/02-agent-02-fa-po-choco-issue.php',
   	'fa-report-po' => $config['domain2']. 'execution-3/report/01-dist-05-fa-po-1.php',
   	'fa-nrp' => $config['domain2']. 'execution-3/report/01-dist-05-fa-retur-pajak.php',
   	# fa-report
   	
  	'fa-chart-stockinnda' => $config['domain2']. 'execution-3/report/02-agent-01-fa-stockchart.php',
  	'fa-dist-stockbs' => $config['domain2']. 'execution-3/fainn/fainn-01-dist-CekCurrentBSDepo.php',
  	'fa-agent-stockbs' => $config['domain2']. 'execution-3/fainn/fainn-03-agent-CekCurrentBSDepo.php',
	'fa-agent-stockdepo' => $config['domain2']. 'execution-3/fainn/fainn-04-agent-CekStockDepo.php',
	'fa-agent-monthly' => $config['domain2']. 'execution-3/report/02-agent-03-fa-monthlycommision.php',
	'fa-agent-freeze' => $config['domain2']. 'execution-3/report/02-agent-04-fa-hawker-freeze.php',
	'fa-agent-umutuk' => $config['domain2']. 'execution-3/report/02-agent-05-fa-umutuk.php',
	'fa-agent-hawker' => $config['domain2']. 'execution-3/report/02-agent-06-fa-hawkerActive2.php',
	'fa-agent-hawker2' => $config['domain2']. 'execution-3/report/02-agent-08-fa-masterhawker.php',
	'fa-agent-recruiter' => $config['domain2']. 'execution-3/report/02-agent-07-fa-recruiter.php',
	'fa-dist-umutuk' => $config['domain2']. 'execution-3/report/01-dist-05-fa-umutuk.php',
  	'fa-dist-stockdepo' => $config['domain2']. 'execution-3/fainn/fainn-02-dist-CekStockDepo.php',
  	'fa-icp-cl' => $config['domain2']. 'execution-3/report/01-dist-05-fa-iciphan-cl.php',
  	'fa-cl' => $config['domain2']. 'execution-3/report/01-dist-05-fa-request_cl.php',
  	'other-nindya' => $config['domain2']. 'execution-3/other/other-01-didyouknow.php',
  	'other-news' => $config['domain2']. 'execution-3/other/other-02-topnews.php',
  	'other-ai' => $config['domain2']. 'execution-3/other/other-04-chatgpt-2.php',
  	'other-ai-img' => $config['domain2']. 'execution-3/other/other-5-chatgpt-image.php',
  	'clara-ai-img' => $config['domain2']. 'execution-3/other/other-5-chatgpt-image.php',
  	'clara-ai' => $config['domain2']. 'execution-3/other/other-03-chatgpt.php',
  	'ar' => $config['domain2']. 'execution-3/03-dist-05-arinn.php',
  	'open-drop' => $config['domain2']. 'execution-3/endpoint/dist-01-opendrop.php',
    'doc-mg' => $config['domain2']. 'execution-3/other/other-09-filemanualguide.php',
    'cek' => $config['domain2']. 'execution-3/03-agent-coverage.php',
  
  	# agent
  	'agent-depo' => $config['domain2']. 'execution-3/agent/agent-01-deposearch.php',
  	'agent-user' => $config['domain2']. 'execution-3/agent/agent-02-depoUserDetail.php',
  	'1234' => $config['domain2']. 'execution-3/agent/agent-03-chatbot-get-order.php',
  
  	# other
  	'other-selenium' => $config['domain2']. 'execution-3/other/other-07-generateElement.php',
  	
  	# Uenak
  	'un-jwk' => $config['domain2']. 'execution-3/uenak/1-uenak-jwk-cek.php',
  	'un-po' => $config['domain2']. 'execution-3/uenak/2-uenak-po-cek-adit.php',
  	'un-set' => $config['domain2']. 'execution-3/uenak/3-uenak-settlement-1.php',
  	'un-name' => $config['domain2']. 'execution-3/uenak/5-uenak-update-storename.php'
  
  
  
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
	#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $server_output);

	curl_close($ch);
};


if(isset($list[$dataMessagePass])){
  	#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline42");
    $json = json_encode($dataMessageArray, true);
    $post($sender, $json, $list[$dataMessagePass], $isGroup);
 
} else {
    #file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0936");
    echo "OK";
	exit();
}

































# file_get_contents($config['bot7']  . urlencode("ping line 4...") );
# https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/setWebhook?url=https://ariefsan.crewbasproject.my.id/telegram/webhook-00-00.php&drop_pending_updates=true
/* require '00-03-base-config.php';
 $config = config();
 
$content = json_decode(file_get_contents('php://input'), true);

$id = $content['id'];
$pushName = $content['pushName'];
$isGroup = $content['isGroup'];
if ($isGroup == true) {
    $subjectGroup = $content['group']['subject'];
    $ownerGroup = $content['group']['owner'];
    $decriptionGroup = $content['group']['desc'];
    $partisipanGroup = $content['group']['participants'];
}
$message = $content['message'];
$phone = $content['phone'];
$messageType = $content['messageType'];
$file = $content['file'];
$mimeType = $content['mimeType'];
$deviceId = $content['deviceId'];
$sender = $content['sender'];
$timestamp = $content['timestamp'];
echo $message;


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
  	'kill-delman' => $config['domain2']. 'execution-1/01-01-agent-kill-user-dist.php',
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
	    

}*/

