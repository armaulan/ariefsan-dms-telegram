<?
// https://ariefsan.basproject.online/telegram/execution-1/02-13-cekrolebyuser.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select a.id_web_user as `ID`, a. username as `USERNAME`, a.fullname as `FULLNAME`, a.email as `EMAIL`, b.nama_role as `NAMA ROLE` 
from web_user a
join web_role b on b.id_web_role = a.role_id
where a.username = '$value'
or a.id_web_user = '$value'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-DIST (Web User Depo) \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID:" . $row['ID'] . "\n" .
          		"Username:" . $row['USERNAME'] . "\n" .
          		"Name:" . $row['FULLNAME'] . "\n" .
          		"Email:" . $row['EMAIL'] . "\n" .
				"Nama Role:" . $row['NAMA ROLE'] . "\n \n" 
				;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendMessage?chat_id=-1001542755544&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output; 
} 
