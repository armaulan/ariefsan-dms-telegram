<?
// https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-2/02-dist-product-depo.php
require '../00-02-conn-dist.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];

/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select d.depo_id as `DEPO_ID`
, d.depo_code as `KODE_DEPO`
, d.depo_name as `DEPO_NAME`
, p.product_code as `KODE_PRODUCT`
, p.short_name as `SHORT_NAME`
, pd.cbp as `PRICE`
, if(pd.is_estimasi= 1,'AKTIF','NOT AKTIF') as `EST`
from product_depo pd 
join depo d on d.depo_id = pd.depo_id
join product p on p.product_id = pd.product_id 
where 0=0
and d.depo_code = '$value'
#and d.depo_code = '1700516270'
and pd.is_estimasi = 1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#Product Depo Dist \n \n";
$flagDepo = 0;
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
      
        if($flagDepo == 0){
            $msg .= "DEPO ID : " . $row['DEPO_ID'] . "\n" . "CODE : " . $row['KODE_DEPO']. "\n". "NAME: " . $row['DEPO_NAME']. "\n\n";
            $flagDepo = 1;
        }
        
        $msg .= "SH NAME : " . $row['SHORT_NAME'] . "\n" .
          		"PRICE: " . $row['PRICE'] . "\n" .
          		"EST : " . $row['EST'] . "\n \n"  
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
