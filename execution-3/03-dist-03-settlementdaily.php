<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-03-settlementdaily.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$json = json_decode($_POST['let2']);
$depo = $json[1];
$tanggal = $json[2];

$sql = "select d.depo_id as `DEPO ID`
, d.depo_code as `DEPO CODE`
, d.depo_name as `DEPO NAME`
, sdn.alamat as `ALAMAT DEPO`
, sdn.nama as `NAMA KADEP`
, sdh.created as `TANGGAL`
, sdh.submitted_time as `WAKTU EKSEKUSI`
, sdh.stock_akhir as `STOCK AKHIR`
, format(sdh.stock_akhir_cbp,0) as `CBP STOCK AKHIR`
, sdh.outstanding_alokasi as `Variance IC Qty`
, format(sdh.outstanding_alokasi_amount,0) as `Variance IC Value`
, sdh.outstanding_pk_transaksi as `Variance PK Transaksi`
, format(sdh.outstanding_pk_cbp,0) as `Variance PK Value`
, format(sdh.outstanding_sb_cbp,0) as `Variance SB Value`
, sdn.notes_inventory as `Notes IC`
, sdn.notes_penerimaan_kasir as `Notes PK`
, sdn.notes_otf as `Summary Notes`
, sdn.link_foto as `Sign Kadep`
from settlement_daily_header sdh 
join settlement_daily_notes sdn on sdn.settlement_header_id = sdh.settlement_header_id 
join depo d on d.depo_id = sdh.depo_id 
where d.depo_code = '$depo'
and sdh.tanggal = '$tanggal';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*02-Dist (Settlement Daily Dist)* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['DEPO CODE'] . "\n" .
          		"Nama Depo: " . $row['DEPO NAME'] . "\n" .
          		"Alamat: " . $row['ALAMAT DEPO'] . "\n" .
          		"Nama Kadep: " . $row['NAMA KADEP'] . "\n" .
          		"Created Settlement: " . $row['TANGGAL'] . "\n" .
          		"Waktu Eksekusi: " . $row['WAKTU EKSEKUSI'] . "\n \n" .
          		"Stock Akhir: " . $row['STOCK AKHIR'] . "\n" .
          		"CBP Stock: Rp. " . $row['CBP STOCK AKHIR'] . "\n" .
          		"Variance IC Qty: " . $row['Variance IC Qty'] . "\n" .
          		"Variance IC Value: Rp. " . $row['Variance IC Value'] . "\n" .
          		"*Notes IC: " . $row['Notes IC'] . "*\n \n" .
        		"Variance PK Transaksi : " . $row['Variance PK Transaksi'] . "\n" .
                "Variance PK Value : Rp. " . $row['Variance PK Value'] . "\n" .
          		"*Notes PK: " . $row['Notes PK'] . "*\n \n" .
                "Variance SB Value: Rp. " . $row['Variance SB Value'] . " \n \n" .
          		"*Summary Notes: " . $row['Summary Notes'] . "*\n \n" .
          		"Sign TTD Kadep : \n" . $row['Sign Kadep'] . "\n \n"
                    ;
      }
    
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
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");
  	# $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");  
    # echo $output;
} 
