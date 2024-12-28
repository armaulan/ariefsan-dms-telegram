<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/02-21-cekestimate.php
require '../00-02-conn-dist.php';
//cek-store id 408430


$sender = $_POST['let1'];
// $url = $_POST['let3'];
$json = json_decode($_POST['let2']);
//$param = $json[1];
$depo = $json[1];
$tanggal = $json[2];


//$hari_final = "-". $hari . " days";

//$tanggal_awal = date('Y-m-d', strtotime($hari_final));
//$tanggal_akhir = date('Y-m-d');


$sql = "select eo.estimate_order_number as `EO NUMBER`,
ul.username as `USERNAME`,
eowg.estimate_weekly_group_number as `EO WEEKLY`,
po.po_number as `NOMOR PO`, 
if(po.sent_sap=1,'SENT','NOTSENT') as `SENT TO SAP`,
eo.total_quantity as `QTY EST`,
d.depo_name as `NAMA DEPO`
from estimate_order eo 
join purchase_order_estimate poe on poe.estimate_order_id = eo.estimate_order_id 
join purchase_order po on po.po_id = poe.purchase_order_id
join estimate_order_status eos on eo.status_id = eos.status_id 
join depo d on d.depo_id = eo.depo_id
join user_login ul on ul.user_id = eo.salesman_id 
join estimate_order_weekly_detail eowd on eowd.estimate_order_id = eo.estimate_order_id
join estimate_order_weekly_header eowh on eowh.estimate_weekly_header_id = eowd.estimate_weekly_header_id 
join estimate_order_weekly_group eowg on eowg.estimate_weekly_group_id = eowh.estimate_weekly_group_id 
where eos.status_id = 3
and d.depo_code = '$depo'
and eo.suggest_order_date = '$tanggal'";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Estimasi Order SENT SAP) \n";
if(mysqli_num_rows($exec) > 0) {
  $row2 = mysqli_fetch_array($exec);
  $msg .= "Depo Name: \n" . $row2['NAMA DEPO'] . "\n \n" ;
    	
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Weekly:" . $row['EO WEEKLY'] . "\n" .
          		"EO Number:" . $row['EO NUMBER'] . "\n" .
          		"Nomor PO: " . $row['NOMOR PO'] . "\n" .
				"Qty Estimasi:" . $row['QTY EST'] . "\n \n" 
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
