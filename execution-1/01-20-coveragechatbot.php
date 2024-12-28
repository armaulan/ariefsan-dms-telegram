	<?
// https://ariefsan.basproject.online/telegram/execution-1/01-20-coveragechatbot.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$kelurahan = $json[1];
$codekelurahan = $json[2];

#$hari_final = "-". $hari . " days";

#$tanggal_awal = date('Y-m-d', strtotime($hari_final));
#$tanggal_akhir = date('Y-m-d');


$sql = "select aor.region_id as `ID REGION`
, aor.region_code as `NAMA REGION`
, d.depo_code as `KODE DEPO`
, d.depo_name as `NAMA DEPO`
, aokn.kelurahan_code as `KODE KELURAHAN`
, aokn.kelurahan as `NAMA KELURAHAN`
, if(aord.is_active=1,'AKTIF','TIDAK AKTIF') as `STATUS`
from agent_order_depo_kelurahan_new aodkn
join agent_order_kelurahan_new aokn on aokn.kelurahan_id = aodkn.kelurahan_id
join depo d on d.depo_id = aodkn.depo_id 
join agent_order_region_depo aord on aord.depo_id = d.depo_id 
join agent_order_region aor on aor.region_id = aord.region_id 
where aokn.kelurahan = '$kelurahan'
or aokn.kelurahan_code = '$codekelurahan'
and aord.is_active = 1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Coverage Chatbot) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Region: " . $row['NAMA REGION'] . "\n" .
          		"Kode Depo: " . $row['KODE DEPO'] . "\n" .
                "Nama Depo: " . $row['NAMA DEPO'] . "\n" .
                "Kelurahan: " . $row['NAMA KELURAHAN'] . "\n" .
          		"Status: " . $row['STATUS'] . "\n \n"
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
