<?
// https://ariefsan.basproject.online/telegram/execution-1/02-04-updateapps.php
require '../00-02-conn-dist.php';

$sender = $_POST['let1'];
$json = json_decode($_POST['let2']);
$typeapk = $json[1];
$mapping = array(
	'production'  => 'production' 
   
);
$description = $mapping[$typeapk];



#$nomorGR = '2050006521220723024698';
#$tanggal_awal = date('Y-m-d', strtotime($hari_final));
#$tanggal_akhir = date('Y-m-d');


$sql = "select
url_link, latest_version, idapps_metadata from apps_metadata aa 
where 0=0
and aa.is_active= 1
and url_link like '%$description%'
ORDER BY idapps_metadata desc
limit 1;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (APK SFADMS) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Update Version: " . $row['latest_version'] . "\n" .
          		"Link URL APK: " . $row['url_link'] . "\n \n" 
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
