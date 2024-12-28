	<?
// https://ariefsan.basproject.online/telegram/execution-1/01-03-ceknorek.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$nomorRekening = $json[1];

$hari_final = "-". $hari . " days";

$tanggal_awal = date('Y-m-d', strtotime($hari_final));
$tanggal_akhir = date('Y-m-d');


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
from user_login a
join depo b on b.depo_id=a.depo_id
left join bank c on c.bank_id=a.bank_name
where a.bank_account_number='$nomorRekening'
and a.is_active=1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Cek Nomor Rekening) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo:\n" . $row['NAMA DEPO'] . "\n" .
                "Username: " . $row['USERNAME'] . "\n" .
                "Nama Hawker: " . $row['NAMA HAWKER'] . "\n" .
          		"Nomor KTP:\n" . $row['NOMOR KTP'] . "\n" .
                "Nama Bank: \n" . $row['NAMA BANK'] . "\n" .
                "Nomor Rekening Hawker:\n" . $row['NOMOR REKENING'] . "\n" .
  				"Status Hawker: " . $row['STATUS'] . "\n".
          		"On Hand: " . $row['ONHAND'] . "\n".
          		"Pending AR: " . $row['AR'] . "\n \n"
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
