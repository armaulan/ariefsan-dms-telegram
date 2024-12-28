<?
// https://ariefsan.basproject.online/telegram/execution-1/01-14-usernameagent.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select b.depo_code,
 b.depo_name as `NAMA DEPO`,
 a.user_id as `USER ID`,
 a.name,
 a.username as `USERNAME`,
 c.role_name as `ROLE HAWKER`,
case when a.mutasi_allowed = 1 then 'YA' else 'TIDAK' end as `IZIN MUTASI`
, (select sum(isn.stock_good) from inventory_salesman_new isn where isn.salesman_id=a.user_id) as `ONHAND`
, (select sum(as2.total_cbp_after_discount) from agent_selling as2 where as2.user_id=a.user_id 
	 and as2.selling_number not in (select pkd.invoice_number from penerimaan_kasir_detail pkd) 
		and as2.status_id <> 5) as `AR`
, (select GROUP_CONCAT(c1.role_name) from mutasi_lock a1
join user_login b1 on b1.user_id = a1.user_id
join user_role c1 on c1.role_id = a1.role_id
where 0=0
and a1.user_id = a.user_id ) as `MUTASI KE`
,(select as2.tanggal_selling from agent_selling as2 where as2.depo_id = a.depo_id and as2.user_id = a.user_id order by as2.selling_id desc limit 1) as `LAST SELLING`  
,case when a.is_active = 1 then 'AKTIF' else 'TIDAK AKTIF' end as `AKTIF USER`
,a.credit_limit as `CREDIT LIMIT USER`
,(select sum(isn.stock_good*pd.cbp) from inventory_salesman_new isn join product_depo pd on pd.depo_id = isn.depo_id and pd.product_id = isn.product_id where isn.salesman_id= a.user_id) as `STOCK`
, (select sum(asd.qty_allocation) from allocation_salesman_detail asd where asd.salesman_id=a.user_id and asd.is_taken= 0) as `OUTS_TAKEN`
from user_login a
join depo b on b.depo_id=a.depo_id 
join user_role c on c.role_id = a.role_id
where a.username= '$value'
or a.user_id = '$value'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Username dan Check Mutasi) \n \n";
if(mysqli_num_rows($exec) > 0) {
    	
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nama Depo: " . $row['NAMA DEPO'] . "\n" .
          		"User ID: " . $row['USER ID'] . "\n" .
          		"Username: " . $row['USERNAME'] . "\n" .
          		"Name: " . $row['name'] . "\n" .
          		"Role: " . $row['ROLE HAWKER'] . "\n" .
          		"Aktif User: " . $row['AKTIF USER'] . "\n" .
          		"Izin Mutasi: " . $row['IZIN MUTASI'] . "\n" .
          		"Mutasi Ke: " . $row['MUTASI KE'] . "\n" .
          		"Credit Limit: Rp. " . number_format($row['CREDIT LIMIT USER'],0) . "\n" .
          		"On Hand: " . $row['ONHAND'] . "\n" .
          		"On Hand(Value): Rp. " . number_format($row['STOCK'],0) . "\n" .
          		"Outs AR:  Rp. " . number_format($row['AR'],0) . "\n" . 
          		"OUTS TAKEN: " . $row['OUTS_TAKEN'] . "\n" .
          		"Sisa Limit: Rp. " . number_format($row['CREDIT LIMIT USER']-$row['STOCK']-$row['AR'],0) . "\n" .
				"Last Selling: " . $row['LAST SELLING'] . "\n \n" 
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
