	<?
// https://ariefsan.basproject.online/telegram/execution-1/01-11-cekname.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$json = json_decode($_POST['let2']);

if(!$json[2]) {
	$namahawker = $json[1];
} else {
  	$namahawker = $json[1] . " " . $json[2];
};

$sql = "select
b.depo_code as `KODE DEPO`
, b.depo_name as `NAMA DEPO`
, a.username as `USERNAME`
, a.name as `NAMA HAWKER`
, a.ktp_number as `NOMOR KTP`
, c.bank_name as `NAMA BANK`
, a.bank_account_number as `NOMOR REKENING`
, if(a.is_active=1,'AKTIF','TIDAK AKTIF') as `STATUS`
, (select sum(isn.stock_good) from inventory_salesman_new isn where isn.salesman_id=a.user_id) as `ONHAND`
, (select concat('Rp. ',format(sum(as2.total_rbp),0)) as `RBP` from agent_selling as2 where as2.user_id=a.user_id 
	 and as2.selling_number not in (select pkd.invoice_number from penerimaan_kasir_detail pkd) 
		and as2.status_id <> 5) as `AR`
, (select as3.tanggal_selling from agent_selling as3 where as3.user_id = a.user_id order by as3.tanggal_selling asc limit 1) as `FIRST TRANS`
, (select as3.tanggal_selling from agent_selling as3 where as3.user_id = a.user_id order by as3.tanggal_selling desc limit 1) as `LAST TRANS`
from user_login a
join depo b on b.depo_id=a.depo_id
left join bank c on c.bank_id=a.bank_name
where a.name like '%$namahawker%'
and b.owner_id not in (10,22)
limit 7
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Trace Nama Hawker) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nama Hawker:" . $row['NAMA HAWKER'] . "\n" .
          		"Username:" . $row['USERNAME'] . "\n" .
          		"Nama Depo:\n" . $row['NAMA DEPO'] . "\n" .
          		"First Trans: " . $row['FIRST TRANS'] . "\n" .
          		"Last Selling: " . $row['LAST TRANS'] . "\n" .
  				"Status Hawker:" . $row['STATUS'] . "\n" .
          		"On Hand: " . $row['ONHAND'] . "\n" .
          		"AR: " . $row['AR'] . "\n \n" 
                    ;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=". $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output;
} 
