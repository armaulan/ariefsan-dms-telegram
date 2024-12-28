<?
// https://ariefsan.basproject.online/telegram/execution-1/01-18-CL-user.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select c.depo_code as `KODE DEPO`, 
c.depo_name as `NAMA DEPO`, 
a.user_id as `USER ID`, 
a.username as `USERNAME`, 
a.name as `NAMA HAWKER`, 
if(b.is_hawker_baru,'HAWKER BARU', 'HAWKER LAMA') as `STATUS HAWKER`,
b.tanggal_batch as `TANGGAL BATCH`, 
b.credit_limit  as `BATCH CL` ,
b.created as `CREATED`,
a.credit_limit as `CL USER`,
a.modified as `MODIFIED`,
(select sum(isn.stock_good*pd.cbp) from inventory_salesman_new isn join product_depo pd on pd.depo_id = isn.depo_id and pd.product_id = isn.product_id where isn.salesman_id= a.user_id) as `STOCK`,
(select sum(as2.total_cbp_after_discount) from agent_selling as2 where as2.user_id=a.user_id 
	 and as2.selling_number not in (select pkd.invoice_number from penerimaan_kasir_detail pkd) 
		and as2.status_id <> 5) as `AR`
 from user_login a
join batch_history_credit_limit b on b.user_id= a.user_id
join depo c on c.depo_id = a.depo_id
where a.user_id = '$value'
or a.username = '$value'
ORDER BY `TANGGAL BATCH` desc
limit 1;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-AGENT (CL USER) \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n" .
          		"User ID: " . $row['USER ID'] . "\n" .
          		"Username: " . $row['USERNAME'] . "\n" .
          		"Nama Hawker: " . $row['NAMA HAWKER'] . "\n" .
          		"Status Hawker: " . $row['STATUS HAWKER'] . "\n" .
          		"Tanggal Batch CL: " . $row['TANGGAL BATCH'] . "\n" .
          		"CL User: Rp. " . number_format($row['CL USER'],0) . "\n" .
          		"Outstanding Stock: Rp." . number_format($row['STOCK'],0) .  "\n" .
          		"AR: Rp. " . number_format($row['AR'],0) . "\n" .
          		"Sisa Limit: Rp. " . number_format($row['CL USER'] - $row['STOCK'] - $row['AR'],0) . "\n \n" 
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
