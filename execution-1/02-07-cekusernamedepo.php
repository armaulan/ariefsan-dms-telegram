<?
// https://ariefsan.basproject.online/telegram/execution-1/02-07-cekusernamedepo.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$kodedepo = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select b.depo_code, 
b.depo_name as `NAMA DEPO`, 
a.user_id as `USER ID`, a.name, a.username as `USERNAME`, c.salesman_type_name as `TYPE SALESMAN`,
case when a.is_button_show = 1 then 'AKTIF' else 'NOT AKTIF' end as `BUTTON SHOW`
from user_login a
join depo b on b.depo_id=a.depo_id 
join salesman_type c on c.salesman_type_id=a.salesman_type_id
where b.depo_code = '$kodedepo'
and a.is_active=1
and c.salesman_type_id in ('1','2')
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Username Depo) \n";
if(mysqli_num_rows($exec) > 0) {
  $row2 = mysqli_fetch_array($exec);
  $msg .= "Depo Name: \n" . $row2['NAMA DEPO'] . "\n \n" ;
    	
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID:" . $row['USER ID'] . "\n" .
          		"Name:" . $row['name'] . "\n" .
				"Username:" . $row['USERNAME'] . "\n" .
          		"Button Show: " . $row['BUTTON SHOW'] . "\n" .
				"Salesman Type:" . $row['TYPE SALESMAN'] . "\n \n" 
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
