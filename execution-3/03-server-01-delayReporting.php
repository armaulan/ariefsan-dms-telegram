<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-server-01-delayReporting.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$sender = $_POST['let1'];
# $sender = '120363107340166301';
# $json = json_decode($_POST['let2']);
# $value = $json[1];

# $sql ="";

# $exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# echo mysqli_num_rows($exec);

# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");

# $telegram_msg = "https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=$sender&text=";


$jsonData = file_get_contents("https://dms-monitoring.pitjarus.co/check_slave_json");
$data = json_decode($jsonData, true);
// var_dump($data);


$status = false;
$txt = "Reporting Delay Status \n";
foreach ($data as $value) {
    
    $time =  date("Y-m-d h:i:s");
    $ip = $value['ip'];
    $delay = $value['sec_b_master'];
    
     
    if(intval($delay) > -20 ){
        $status = true;
        $txt .= $ip. " | Sec_behind : " .$delay. "\n" ;
    }
    
}

if($status) {
    # sendAlert($telegram_msg, $status, $txt);
  	$config["whatsappSendMessage"]($config['key-wa-bas'],  $txt , $sender, "true");
  	$config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $sender, "true");
}


# function sendAlert($telegram_msg, $status, $data){
    #if($status){
        #file_get_contents($telegram_msg . urlencode("[Monitoring Server] - Delay Synch Status  "));
        #file_get_contents($telegram_msg . urlencode($data));
        
    #}
    
# };

