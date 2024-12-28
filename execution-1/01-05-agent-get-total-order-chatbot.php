<?
// https://ariefsan.basproject.online/telegram/execution-1/01-05-agent-get-total-order-chatbot.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$hari = $json[1];

$hari_final = "-". $hari . " days";

$tanggal_awal = date('Y-m-d', strtotime($hari_final));
$tanggal_akhir = date('Y-m-d');


$sql = "
    select aod.order_date 
    , count(order_id) as total_order
    from agent_order_data aod
    where 0=0
    and aod.order_date BETWEEN '$tanggal_awal' and '$tanggal_akhir'
    group by aod.order_date 
";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "All Order From Balesin \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Tanggal : " . $row['order_date'] . "\n" .
  				"Jumlah Order : " . $row['total_order'] . "\n \n"
                    ;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output;
} 

