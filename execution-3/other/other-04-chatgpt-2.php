<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-04-chatgpt-2.php
# require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

$sender = $_POST['let1'];

$is_group = "false";
if(in_array($sender, array("6281319152872-1588651135", "120363107340166301", "6281212500170-1596252861", "120363022968328005" ))) {
	$is_group = "true";
}
  
$json = json_decode($_POST['let2']);
$jsonImplode = implode(" ", $json);
$question = str_replace('other-ai ', '', $jsonImplode); # str_replace('_normal', '', $var)

$postfield = [
    #"message" => $question
  	"chat" => $question
];

file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=other-04-chatgpt-2-invoke-baru". $sender. "-". $question);

$curl = curl_init();

curl_setopt_array($curl, array(
  #CURLOPT_URL => 'https://api.tobbaca.my.id/chat',
  CURLOPT_URL => 'https://chatgpt.pingpepper.pw/chat',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($postfield),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
    ,"Authorization: Bearer SccorBKORRtpdmTst5s"
  ),
));

$response = curl_exec($curl);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $response);

#print($response);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $response);

$message = json_decode($response);
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $message->content);
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $message->message->content);

# $config["whatsappSendMessage"]($config['key-wa-bas'], $message->content, $sender, $is_group);
$config["whacenterSendGroupMessage"]($config['key-whacenter-1'], "Crew BAS", $message->message->content);




