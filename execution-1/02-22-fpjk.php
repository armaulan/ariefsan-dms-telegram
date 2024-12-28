<?
// https://ariefsan.basproject.online/telegram/execution-1/02-22-fpjk.php
require '../00-02-conn-dist.php';

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];

$hari_final = "-". $hari . " days";

$tanggal_awal = date('Y-m-d', strtotime($hari_final));
$tanggal_akhir = date('Y-m-d');


$sql = "select d.depo_code as `DEPO CODE`
, d.depo_name as `NAMA DEPO`
, fpid.faktur_pajak_invoice_id as `ID FP`
, fpi.nomor_seri_faktur_pajak as `NO SERI FP`
, fpi.nomor_dokumen as `DOC FP DMS`
, t.invoice_number as `NO INVOICE`
, s.store_id as `STORE ID`
, s.store_name as `STORE NAME`
, if(s.is_pkp= 1,'PKP','NPKP') as `STATUS PKP` 
, if(s.is_single= 1,'PER FAKTUR', 'GABUNGAN') as `FAKTUR TYPE`
from faktur_pajak_invoice_detail fpid
join tagihan t on t.invoice_number = fpid.invoice_number 
join faktur_pajak_invoice fpi on fpi.faktur_pajak_invoice_id = fpid.faktur_pajak_invoice_id
join depo d on d.depo_id = t.depo_id 
join store s on s.store_id = t.store_id 
where fpi.nomor_seri_faktur_pajak = '$value'
or fpi.nomor_dokumen = '$value' ;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Informasi Faktur Pajak) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['DEPO CODE'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n \n" .
          		"ID Faktur DMS: " . $row['ID FP'] . "\n" .
          		"Nomor Seri FP: " . $row['NO SERI FP'] . "\n" .
        		"Doc FP : \n" . $row['DOC FP DMS'] . "\n" .
                "No_Invoice : \n" . $row['NO INVOICE'] . "\n \n" .
                "Store ID: " . $row['STORE ID'] . " \n" .
          		"Store Name: \n" . $row['STORE NAME'] . "\n" .
          		"Status PKP: " . $row['STATUS PKP'] . "\n" .
                "Faktur Type: " . $row['FAKTUR TYPE'] .  "\n \n"
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
