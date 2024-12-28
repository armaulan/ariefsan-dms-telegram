<?
// https://ariefsan.basproject.online/telegram/execution-1/01-19-cekuserkurir.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$kodedepo = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select c.depo_code as `KODE DEPO`, c.depo_name as `NAMA DEPO`, a.user_id as `USER ID`, a.username as `USERNAME`,
a.name as `NAMA HAWKER`, b.role_name as `NAMA ROLE` 
from user_login a
join user_role b on b.role_id = a.role_id
join depo c on c.depo_id = a.depo_id
where b.role_id=5
and a.is_active = 1
and c.depo_code = '$kodedepo';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-AGENT (Username AKTIF KURIR) \n";
if(mysqli_num_rows($exec) > 0) {
  $row2 = mysqli_fetch_array($exec);
  $msg .= "Depo Name: \n" . $row2['NAMA DEPO'] . "\n \n" .
    
    			"User ID :" . $row2['USER ID'] . "\n" .
          		"Name :" . $row2['NAMA HAWKER'] . "\n" .
				"Username : " . $row2['USERNAME'] . "\n \n"
    ;

    	
    /* while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID :" . $row['USER ID'] . "\n" .
          		"Name :" . $row['NAMA HAWKER'] . "\n" .
				"Username : " . $row['USERNAME'] . "\n \n" 
				;
      }*/
    
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
