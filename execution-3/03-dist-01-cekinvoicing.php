<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-01-cekinvoicing.php
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


$sql = "select 
c.depo_code as `KODE DEPO`
, c.depo_name as `NAMA DEPO`
, l.store_code as `STORE CODE`
, l.store_name as `STORE NAME`
, h.name as `NAMA DELMAN`
, a.dropping_number as `NOMOR DROPPING`
, Format((a.total_discount/a.total_cbp)*100,2) as `DISCOUNT TOKO`
, Format (a.total_cbp,0) as `CBP DROPPING`
, Format (a.total_rbp,0) as `RBP DROPPING`
, a.dropping_date as `TANGGAL DROPPING`
, j.status_name as `STATUS DROPPING`
, CASE
		WHEN h.username is null then i.username
		else h.username 
		END as `DELMAN DROPPING`
, a.created_from as `PLATFORM DROPPING`
, b.invoice_number as `NOMOR INVOICE`
, Format(b.total_retur,0) as `NILAI RETUR`
, Format(b.total_net,0) as `NILAI NET`
, Format((b.total_discount/b.total_net)*100,2) as `DISCOUNT TOKO INVOICE`
, Format(b.total_tagihan,0) as `NILAI TAGIHAN`
, b.invoice_date as `TANGGAL INVOICE`
, k.status_name as `STATUS TAGIHAN`
, CASE
		WHEN h2.username is null then i2.username
		else h2.username 
		END as `DELMAN INVOICING`
, b.created_from as `PLATFORM INVOICING`
, e.penerimaan_kasir_number as `NOMOR PENERIMAAN KASIR` 
, e.penerimaan_date as `TANGGAL PENERIMAAN KASIR`
, g.setoran_number as `NOMOR SETORAN BANK`
, g.setoran_date as `TANGGAL SETORAN BANK`
from dropping a
left join tagihan b on b.dropping_number=a.dropping_number
join depo c on c.depo_id=a.depo_id
left join penerimaan_kasir_detail d on d.invoice_number=b.invoice_number
left join penerimaan_kasir e on e.penerimaan_kasir_id=d.penerimaan_id
left join setoran_bank_detail f on f.penerimaan_kasir_number=e.penerimaan_kasir_number
left join setoran_bank g on g.setoran_id=f.setoran_id
left join user_login h on h.user_id=a.created_by
left join user_login h2 on h2.user_id=b.created_by
left join web_user i on i.id_web_user=a.created_by
left join web_user i2 on i2.id_web_user=b.created_by
join dropping_status j on j.status_id=a.status_id
left join tagihan_status k on k.status_id=b.status_id
join store l on l.store_id=a.store_id 
where b.invoice_number='$nomordropping';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# echo mysqli_num_rows($exec);


$msg = "#02-Dist (Status Nomor Invoicing) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n \n" .
          		"Store Code: " . $row['STORE CODE'] . "\n" .
          		"Store Name: " . $row['STORE NAME'] . "\n" .
        		"Delman Dropping : \n" . $row['DELMAN DROPPING'] . "\n \n" .
                "Nomor Dropping : \n" . $row['NOMOR DROPPING'] . "\n" .
                "Discount Toko: " . $row['DISCOUNT TOKO'] . "% \n" .
          		"CBP Dropping: Rp. " . $row['CBP DROPPING'] . "\n" .
          		"RBP Dropping: Rp. " . $row['RBP DROPPING'] . "\n" .
                "Tanggal Dropping: " . $row['TANGGAL DROPPING'] . "\n" .
          		"Status Dropping : " . $row['STATUS DROPPING'] . "\n" .
                "Platform Dropping : " . $row['PLATFORM DROPPING'] . "\n \n" .
          		"Delman Penagihan: " . $row['DELMAN INVOICING'] . "\n" .
          		"Platform Penagihan: " . $row['PLATFORM INVOICING'] . "\n" .
                "Nomor Invoice: " . $row['NOMOR INVOICE'] . "\n" .
                "Tanggal Invoice: " . $row['TANGGAL INVOICE'] . "\n" .
          		"Status Tagihan : " . $row['STATUS TAGIHAN'] . "\n" .
          		"Total Dropping: Rp. " . $row['CBP DROPPING'] . "\n" .
          		"Total Retur: Rp. " . $row['NILAI RETUR'] . "\n" .
          		"Total Net(exc Diskon): Rp. " . $row['NILAI NET'] . "\n" .
          		"Discount Invoice: " . $row['DISCOUNT TOKO INVOICE'] . "% \n" .
          		"Nilai Tagihan: Rp. " . $row['NILAI TAGIHAN'] . "\n \n" .
          		"Nomor Penerimaan Kasir: \n" . $row['NOMOR PENERIMAAN KASIR'] . "\n" .
                "Tanggal Penerimaan Kasir: \n" . $row['TANGGAL PENERIMAAN KASIR'] . "\n \n" .
                "Nomor Setoran Bank : \n" . $row['NOMOR SETORAN BANK'] . "\n" .
  				"Tanggal Setoran Bank : \n" . $row['TANGGAL SETORAN BANK'] . "\n \n"
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
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
    $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");
    # echo $output;
} 
