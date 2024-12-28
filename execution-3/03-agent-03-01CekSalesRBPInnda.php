<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-agent-03-01CekSalesRBPInnda.php
exit();
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-agent-03-01CekSalesRBPInnda.php");

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
# $nomordropping = $json[1];

$sql = "select 
pk.penerimaan_date 
, format(sum(pk.total_after_commission),2) as rbp
from penerimaan_kasir pk 
join user_login ul on ul.user_id = pk.salesman_id
join depo d on d.depo_id = ul.depo_id 
where 0=0
and pk.penerimaan_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 4 day)
and d.owner_id = 28
group by penerimaan_date
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#Net Sales RBP PK Innda (Last 5 Days) \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= $row['penerimaan_date']. " *". $row['rbp']. "* \n";

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
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
    # $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");
    # echo $output;
} 
