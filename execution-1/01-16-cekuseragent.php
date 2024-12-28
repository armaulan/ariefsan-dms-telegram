<?
// https://ariefsan.basproject.online/telegram/execution-1/01-16-cekuseragent.php
require '../00-01-conn-agent.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$depo = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select a.user_id as `USER ID`
, a.username as `USERNAME`
, a.name as `NAMA HAWKER`
, c.role_name as `ROLE HAWKER`
, (select as2.tanggal_selling from agent_selling as2 where as2.depo_id = a.depo_id and as2.user_id = a.user_id order by as2.selling_id desc limit 1) as `LAST SELLING`
, (select sum(isn.stock_good) from inventory_salesman_new isn where isn.salesman_id=a.user_id) as `ONHAND`
, (select concat('Rp. ',format(sum(as2.total_rbp),0)) as `RBP` from agent_selling as2 where as2.user_id=a.user_id 
	 and as2.selling_number not in (select pkd.invoice_number from penerimaan_kasir_detail pkd) 
		and as2.status_id <> 5) as `AR`
from user_login a
join depo b on b.depo_id = a.depo_id
join user_role c on c.role_id = a.role_id
where b.depo_code = '$depo'
and a.is_active = 1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Username AGENT ) \n \n";
if(mysqli_num_rows($exec) > 0) {
    	
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID: " . $row['USER ID'] . "\n" .
          		"Username: " . $row['USERNAME'] . "\n" .
          		"Name: " . $row['NAMA HAWKER'] . "\n" .
          		"Role: " . $row['ROLE HAWKER'] . "\n" .
          		"On Hand: " . $row['ONHAND'] . "\n" .
          		"Outs AR: " . $row['AR'] . "\n" .
				"Last Selling: " . $row['LAST SELLING'] . "\n \n" 
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
