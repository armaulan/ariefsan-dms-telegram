<?
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/ticket/03-01-ticket-entry.php
# require '../00-02-conn-dist.php';
require '00-connection.php';
require '../../00-03-base-config.php';
require '00-func-user.php';
$config = config();
$listUser = getListUser();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line10");

  # var_dump($listUser);
  # exit();

  # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");
  # $tanggal_awal = date('Y-m-d', strtotime($hari_final));
  # $tanggal_akhir = date('Y-m-d');
  # $sender = $_POST['let1'];
  # $url = $_POST['let3'];

# Validasi bahwa create tiket harus dari wa pribadi
$isGroup = $_POST['let3'];
if($isGroup == 1){ 
  file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-exit()");
  exit(); 
}

# Validasi bahwa yang buat tiket harus sudah terdaftar di backend
$sender = $_POST['let1'];
if(isset($listUser[$sender])){
    # file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-senderApproved");
  	# exit();
} else {
	# Jika nomor sender tidak terdaftar, maka stop script
  	exit();
}

# Preparing detail message ticket
$postData = json_decode($_POST['let2']);

# Check If keyword agent or dist has been correctly inputed
if (in_array( strtolower($postData[1]),  ['dist', 'agent'] )){
  	# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-agentOrDistApproved");
} else {
  	# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-agentOrDistRejected");
	exit();
}

$ticketContent = implode(" ", $postData);

# $ticketMsg = explode(",", $postData);
# $ticketMsg2 = implode(" ", $ticketMsg);
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $isGroup );
# $config["whatsappSendMessage"]($config['key-wa-bas'],  $ticketContent, $config['id-wa-group-fa'], "true");
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". gettype($ticketContent) );

# create ticket_date
$ticket_date = date('Y-m-d');
$requester = $sender;
$channel = strtolower($postData[1]);
$detail = str_replace('Tic-entry dist ', '', str_replace('tic-entry dist ', '', str_replace('tic-entry agent ', '', str_replace('Tic-entry agent ', '', $ticketContent)))); # str_replace('_normal', '', $var)
$status = '1';

# $ticket_date = '2024-01-01';
# $requester = '62899046372';
# $detail = str_replace('tic-entry ', '', 'Jagoan Neon Minta di support apa aja'); # str_replace('_normal', '', $var)
# $status = '1';

$queryInsert = "insert into ticket(ticket_date, requester, channel, detail, status) values ('$ticket_date', '$requester', '$channel', '$detail', '$status')" ;
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $queryInsert );
$queryExec = mysqli_query($conn, $queryInsert) or die(mysqli_error($conn));
# var_dump($queryExec);

$post_channel = json_encode(array("pass", $channel));
if($queryExec){
	# file_get_contents("https://ariefsan.crewbasproject.my.id/telegram/execution-3/ticket/03-02-ticket-showlist.php");
    $ch = curl_init();

    #curl_setopt($ch, CURLOPT_URL, "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/ticket/03-02-ticket-showlist.php");
    curl_setopt($ch, CURLOPT_URL, $config['domain2']. "execution-3/ticket/03-02-ticket-showlist.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "let1=null&let2=$post_channel&let3=null");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec($ch);
	curl_close($ch);  
    exit();
}

