<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-03-chatgpt.php
# require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# https://newsdata.io/api/1/news?apikey=pub_21851616bd74055c97a73e622e0abaae1c876&q=indonesia&country=id
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");
# https://newsapi.org/v2/top-headlines?country=id&apiKey=1179b547ab8540ac9c79ced6d4259f0d

$sender = json_decode($_POST['let1']);
$json = json_decode($_POST['let2']);
$jsonImplode = implode(" ", $json);
$question = str_replace('other-ai ', '', $jsonImplode); # str_replace('_normal', '', $var)

$postfield = [
	# "message" => "selamat malam openai dan mimpi indah untukMu"
  	"message" => $question
];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.tobbaca.my.id/chat',
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

#print($response);
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $response);

$message = json_decode($response);
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $message->content);

$config["whatsappSendMessage"]($config['key-wa-bas'], $message->content, $config['id-wa-group-fa'], "true");

#$input = file_get_contents("https://newsdata.io/api/1/news?apikey=pub_21851616bd74055c97a73e622e0abaae1c876&q=$theme&country=id");
#echo $input;
# var_dump($input);

#$news = json_decode($input);
#$list = $news->results;

/*
$msg = "Todays News \n";
for ($x = 0; $x <= 4; $x++) {
  $msg .= $list[$x]->title. "\n";
  $msg .= $list[$x]->link. "\n \n";
}*/ 

# print($msg);

# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-arya'], "false");
# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");

#echo $input;

#$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
#$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

# $sql = "";

 
# print($dataMessage);
/*
$random1 = substr(str_shuffle('12'),1 , 2);
$random2 = substr(str_shuffle('1234567890'),1 , 1);
$random3 = substr(str_shuffle('1234567890'),1 , 1);
$combine = (int)$random1.$random2.$random3;

if($combine > 250) {
  	#echo "280";
	$config["whatsappSendMessage"]($config['key-wa-bas'],  "Did you know ? \n". $list[$combine-99], $config['id-wa-group-fa'], "true");
} else {
  	# echo "283";
	$config["whatsappSendMessage"]($config['key-wa-bas'],  "Did you know ? \n". $list[$combine], $config['id-wa-group-fa'], "true");
}
*/





