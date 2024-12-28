<?
# https://ariefsan.crewbasproject.my.id/telegram/execution-2/00-00-01-reset-webhook.php
# require '../00-03-conn-agent.php';
require '../00-03-base-config.php';

$sender = $_POST['let1'];

$config = config();
$config['telegramSendMessage']($config['key-zafbot'],  "Zaf - Preparing for reset and clearing pending webhook..." , $sender);

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/setWebhook?url=https://ariefsan.crewbasproject.my.id/telegram/webhook-00-00.php&drop_pending_updates=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$output = curl_exec($ch); 
curl_close($ch);      
// echo $output;

if (strpos($output, 'Webhook was set') !== false) { 
    $config['telegramSendMessage']($config['key-zafbot'],  "Webhook was set !" , $sender);
} else {
	$config['telegramSendMessage']($config['key-zafbot'],  "Webhook was not set !" , $sender);
}
