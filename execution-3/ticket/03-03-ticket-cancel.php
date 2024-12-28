<?
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/ticket/03-03-ticket-cancel.php
# require '../00-02-conn-dist.php';
require '00-connection.php';
require '../../00-03-base-config.php';
require '00-func-user.php';
$config = config();
$listUser = getListUser();

  # var_dump($listUser);
  # exit();

# Validasi bahwa create tiket harus dari wa pribadi
$isGroup = $_POST['let3'];
if($isGroup == 1){ 
  # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-exit()");
  exit(); 
}

# Validasi bahwa yang update tiket harus sudah terdaftar di backend
$sender = $_POST['let1'];
if(isset($listUser[$sender])){
    # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-senderApproved");
  	# exit();
} else {
	# Jika nomor sender tidak terdaftar, maka stop script
  	exit();
}

# Preparing detail message
$postData = json_decode($_POST['let2']);

# Check If keyword agent or dist has been correctly inputed
# if (in_array(strtolower($postData[2]),  ['cancel'] )){
  		# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-agentOrDistApproved");
#	} else {
#  		# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-agentOrDistRejected");
#		exit();
#}

#$pic_vendor = $sender;
#$done_time = date('Y-m-d H:i:s');

$queryUpdate = "update ticket set status=3 where ticket_id = '$postData[1]' and requester = '$sender' and status=1 ";
$queryExec = mysqli_query($conn, $queryUpdate) or die(mysqli_error($conn));
# var_dump($queryExec);

$querySelect = "select channel from ticket where ticket_id = '$postData[1]' ";
$queryExec2 = mysqli_query($conn, $querySelect) or die(mysqli_error($conn));
$row = mysqli_fetch_array($queryExec2);
$post_channel = json_encode(array("pass", $row['channel']));

if($queryExec){
	# file_get_contents("https://ariefsan.crewbasproject.my.id/telegram/execution-3/ticket/03-02-ticket-showlist.php");
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/ticket/03-02-ticket-showlist.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "let1=null&let2=$post_channel&let3=null");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec($ch);
	curl_close($ch);  
    exit();
}

