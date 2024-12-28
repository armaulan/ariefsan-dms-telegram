<?
// https://ariefsan.basproject.online/telegram/execution-1/02-18-cekbuttonshow.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$depo = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select a.user_id as `USER ID`, a.username as `USERNAME`, a.name as `FULLNAME`, 
case when a.is_button_show = 1 then 'AKTIF' else 'TIDAK AKTIF' end as `BUTTON SHOW`
from user_login a 
join depo b on b.depo_id = a.depo_id
where is_button_show= 1
and b.depo_code='$depo'
and a.salesman_type_id in ('1','2')
and b.owner_id=28
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-DIST (User Delman Aktif +-) \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID:" . $row['USER ID'] . "\n" .
          		"Username:" . $row['USERNAME'] . "\n" .
          		"Name:" . $row['FULLNAME'] . "\n" .
          		"Button Drop:" . $row['BUTTON SHOW'] . "\n \n" 
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
