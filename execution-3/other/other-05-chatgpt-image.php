<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-5-chatgpt-image.php
# require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=other-5-chatgpt-image.php-invoke");


$sender = $_POST['let1'];

$is_group = "false";
if(in_array($sender, array("6281319152872-1588651135", "120363107340166301", "6281212500170-1596252861" ))) {
	$is_group = "true";
}
  
$json = json_decode($_POST['let2']);
$jsonImplode = implode(" ", $json);
$question = str_replace('other-ai-img ', '', $jsonImplode); # str_replace('_normal', '', $var)


$postfield = [
	#"message" => "gambar domba"
    "message" => $question
];


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.tobbaca.my.id/image',
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
  ),
));

$response = curl_exec($curl);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $response);

$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_openai.jpg";
/*$url = $response ;
$img = '/file-other/'. $filename;
file_put_contents($img, file_get_contents($url)); */

/*
$fp = fopen("/file-other/". $filename, 'w');
fwrite($fp, $response);
fclose($fp);
*/

#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". json_decode($response));
$ch = curl_init(json_decode($response));
$fp = fopen('file-other/'. $filename, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);

#print($response);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $response);

#$message = json_decode($response);
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $message->content);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $message->content);

#$config["whatsappSendMessage"]($config['key-wa-bas'], $message->content, $sender, $is_group);
$config["whatsappSendImg"]($config['key-wa-bas'],  $sender, "https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/file-other/". "$filename",  $is_group, "chatgpt");
# $config["whatsappSendImg"]($config['key-wa-bas'],  $sender, "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/other/file-other/RDCP_openai.jpg",  $is_group, "chatgpt");
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/




