<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-2/01-23-product.php
require '../00-01-conn-agent.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select p.product_id as `ID`
, p.product_code as `KODE`
, p.product_name as `NAMA` 
from product p 
where p.product_name like '%$value%'
or p.product_code = '$value'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#PRODUCT AGENT \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "ID: " . $row['ID'] . "\n" .
          		"Kode: " . $row['KODE'] . "\n" .
          		"Nama: " . $row['NAMA'] . "\n \n" 
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
