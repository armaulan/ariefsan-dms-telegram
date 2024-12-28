<?
// https://ariefsan.basproject.online/telegram/execution-1/01-07-NomorSelling.php
require '../00-01-conn-agent.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$nomorselling = $json[1];

$hari_final = "-". $hari . " days";

$tanggal_awal = date('Y-m-d', strtotime($hari_final));
$tanggal_akhir = date('Y-m-d');


$sql = "select 
c.depo_code as `KODE DEPO`
, c.depo_name as `NAMA DEPO`
, a.`name` as `NAMA HAWKER`
, ulsh.status_name as `STATUS HAWKER`
, a.username as `USERNAME`
, b.role_name as `ROLE TYPE`
, b.default_comm_daily as `KOMISI USER`
, d.selling_number as `NOMOR SELLING`
, d.selling_date as `TANGGAL SELLING`
, case when d.is_cashless = 1 then 'CASHLESS' else 'CASH' end as `STATUS PEMBAYARAN`
, concat('Rp. ',format(d.total_cbp,0)) as `CBP`
, concat('Rp. ',format(d.total_rbp,0)) as `RBP`
, f.penerimaan_kasir_number as `NOMOR PK`
, (select arpl.cost_value from agent_report_psak_log arpl 
   join agent_cost_component acc on acc.cost_code = arpl.cost_code 
   where arpl.setoran_number = f.penerimaan_kasir_number
   and acc.cost_code= 'code6') as `UANG MAKAN`
, (select arpl.cost_value from agent_report_psak_log arpl 
   join agent_cost_component acc on acc.cost_code = arpl.cost_code 
   where arpl.setoran_number = f.penerimaan_kasir_number
   and acc.cost_code= 'code7') as `UANG TRANSPORT`
, urdl.value_uk as `UANG KEHADIRAN`
, f.penerimaan_date as `TANGGAL PK`
, h.setoran_number as `NOMOR SB`
, h.setoran_date as `TANGGAL SB`
from user_login a
join user_login_status_hawker ulsh on ulsh.status_id = a.hawker_status 
left join user_role b on b.role_id=a.role_id
left join depo c on c.depo_id=a.depo_id
left join agent_selling d on d.user_id=a.user_id
left join penerimaan_kasir_detail e on e.invoice_number=d.selling_number 
left join penerimaan_kasir f on f.penerimaan_kasir_id=e.penerimaan_id
join uk_report_detail_log urdl on urdl.setoran_number = f.penerimaan_kasir_number
left join setoran_bank_detail g on g.penerimaan_kasir_number=f.penerimaan_kasir_number
left join setoran_bank h on h.setoran_id=g.setoran_id
where d.selling_number='$nomorselling'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Status Selling) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n" .
                "Nama Hawker:\n" . $row['NAMA HAWKER'] . "\n" .
				"Username: " . $row['USERNAME'] . "\n" .
				"Status Hawker: " . $row['STATUS HAWKER'] . "\n" .
				"Role Type: " . $row['ROLE TYPE'] . "\n" .
				"Komisi User: " . $row['KOMISI USER'] . "\n \n" .
				"Nomor Selling:\n" . $row['NOMOR SELLING'] . "\n" .
				"Tanggal Selling: " . $row['TANGGAL SELLING'] . "\n" .
          		"Type Pembayaran: " . $row['STATUS PEMBAYARAN'] . "\n" .
				"Value CBP: " . $row['CBP'] . "\n" .
				"Value RBP: " . $row['RBP'] . "\n" .
				"Nomor PK: \n" . $row['NOMOR PK'] . "\n" .
				"Uang Makan: " . $row['UANG MAKAN'] . "\n" .
				"Uang Kehadiran: " . $row['UANG KEHADIRAN'] . "\n" .
				"Tanggal PK:  " . $row['TANGGAL PK'] . "\n" .
				"Nomor SB: \n" . $row['NOMOR SB'] . "\n" .
				"Tanggal SB: " . $row['TANGGAL SB'] . "\n"           
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
