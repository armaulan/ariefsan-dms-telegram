<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-agent-coverage.php
require '../00-01-conn-agent.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$json = json_decode($_POST['let2']);
$value1 = $json[1];
$value2 = "";
    if(isset($json[2])){
        $value2 = $json[2];
    }
$value3 = $value1 . " " . $value2;



$sql = "select aor.region_name as `REGION NAME`
, aor.region_code as `KODE REGION`
, aokn.kelurahan_code as `KELURAHAN KODE`
, aokn.kelurahan as `NAMA KELURAHAN`
, d.depo_code as `DEPO KODE`
, d.depo_name as `NAMA DEPO`
from agent_order_depo_kelurahan_new aodkn  
join agent_order_kelurahan_new aokn on aodkn.kelurahan_id = aokn.kelurahan_id 
join agent_order_region_depo aord on aord.depo_id = aodkn.depo_id 
join agent_order_region aor on aor.region_id = aord.region_id 
join depo d on d.depo_id = aodkn.depo_id 
where aokn.kelurahan like '%$value3%' 
and aord.is_active = 1
or aokn.kelurahan_code = '$value3'
and aord.is_active = 1;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Coverage Chatbot \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Region : " . $row['REGION NAME'] . "\n" .
          		"Region Name: " . $row['KODE REGION'] . "\n" .
                "Kode Kelurahan: " . $row['KELURAHAN KODE'] . "\n" .
                "Nama Kelurahan: " . $row['NAMA KELURAHAN'] . "\n" .
          		"Kode Depo: " . $row['DEPO KODE'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n \n" 
                    ;
      }

    # $ch = curl_init(); 

    // set url 
    # curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    # curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    # $output = curl_exec($ch); 

    // tutup curl 
    # curl_close($ch);      

    // menampilkan hasil curl
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-etrademode'], "true");
    # echo $output;
} 
