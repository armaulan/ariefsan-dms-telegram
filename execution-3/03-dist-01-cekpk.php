<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-01-cekpk.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$json = json_decode($_POST['let2']);
$nomordropping = $json[1];


$sql = "select d.depo_code as `depo`
, d.depo_name as `nama depo`
, pk.penerimaan_kasir_number as `pk`
, pk.penerimaan_date as `tgl pk`
, s.store_code as `store code`
, s.store_name as `nama store`
, t.dropping_number as `nomor dropping`
, t.dropping_date as `tgl drop`
, t.invoice_number as `no invoice`
, t.invoice_date as `tgl invoice`
, format(t.total_dropping,0) as `cbp drop`
, format(t.total_retur,0) as `retur`
, format((t.total_discount/t.total_dropping)*100,2) as `discount toko`
, format(t.total_net,0) as `net`
, format(t.total_pure_sales,0) as `tagihan` 
, format(sbd.total_penerimaan_kasir,0) as `detail sb`
, sb.setoran_number as `sb number`
, sb.setoran_date  as `sb date`
from penerimaan_kasir pk
join penerimaan_kasir_detail pkd on pkd.penerimaan_id = pk.penerimaan_kasir_id 
join penerimaan_kasir_status pks on pks.status_id = pk.status_id 
join tagihan t on t.invoice_number = pkd.invoice_number 
join store s on s.store_id = t.store_id 
join depo d on d.depo_id = s.depo_id
join setoran_bank_detail sbd on sbd.penerimaan_kasir_number = pk.penerimaan_kasir_number 
join setoran_bank sb on sb.setoran_id = sbd.setoran_id 
where pk.penerimaan_kasir_number= '$nomordropping' ";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$exec2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# echo mysqli_num_rows($exec);

$msg = "#02-Dist (Nomor PK) \n";
if(mysqli_num_rows($exec) > 0) {
  $row2 = mysqli_fetch_array($exec);
  $msg .= "Kode Depo: \n" . $row2['depo'] . "\n". 
		  "Nama Depo: \n" . $row2['nama depo'] . "\n".
    	  "Nomor PK : \n" . $row2['pk'] . "\n".
    	  "Tgl PK: \n" . $row2['tgl pk'] . "\n \n" ;
    	
    while($row = mysqli_fetch_array($exec2)) {
        
        $msg .= "SCode:" . $row['store code'] . "\n" .
          		"NaStore:" . $row['nama store'] . "\n" .
				"NoDrop:" . $row['nomor dropping'] . "\n" .
          		"TglDrop: " . $row['tgl drop'] . "\n" .
          		"NoInv: " . $row['no invoice'] . "\n" .
          		"InvDate: " . $row['tgl invoice'] . "\n" .
          		"CBP: " . $row['cbp drop'] . "\n" .
          		"Rtr: " . $row['retur'] . "\n" .
          		"Disc: " . $row['discount toko'] . "% \n" .
          		"Net: " . $row['net'] . "\n" .
          		"Tghn: " . $row['tagihan'] . "\n \n" ;
      }
  
	 $msg .= "Detail SB: " . $row2['detail sb'] . "\n" .
          		"No SB: " . $row2['sb number'] . "\n" .
				"Tgl SB:" . $row2['sb date'] . "\n \n" ;
  
    $msg .= strlen($msg) . " char";
      
    # $ch = curl_init(); 

    // set url 
    # curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    # curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    # $output = curl_exec($ch); 

    // tutup curl 
    # curl_close($ch);      

    // menampilkan hasil curl
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
    # echo $output;
} 
