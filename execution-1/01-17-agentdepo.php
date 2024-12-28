<?
// https://ariefsan.basproject.online/telegram/execution-1/01-17-agentdepo.php
require '../00-01-conn-agent.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select d.depo_id as `DEPO ID`,
d.depo_code as `DEPO CODE`,
d.depo_name as `DEPO NAME`, 
d.latitude as `LATITUDE`, 
d.longitude as `LONGITUDE`,
d.sales_office_id as `OFFICE ID`,
if(dec2.ritase=1,'RIT 1',if(dec2.ritase=2,'RIT 2',if(dec2.ritase= 3,'RIT 3','BELUM SET RIT'))) as `RITASE`
from depo d
join depo_entity_code dec2 on dec2.depo_id = d.depo_id 
where 0=0
and depo_name like '%$value%'
and d.owner_id not in('10','22','27')
or depo_code = '$value'
and d.owner_id not in('10','22','27')
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-AGENT (AGENT DEPO) \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Depo ID: " . $row['DEPO ID'] . "\n" .
          		"Kode Depo: " . $row['DEPO CODE'] . "\n" .
          		"Nama Depo: " . $row['DEPO NAME'] . "\n" .
          		"Latitude: " . $row['LATITUDE'] . "\n" .
          		"Longitude: " . $row['LONGITUDE'] . "\n" .
          		"Sales Office ID: " . $row['OFFICE ID'] . "\n" .
          		"Ritase:" . $row['RITASE'] .  "\n \n" 
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
