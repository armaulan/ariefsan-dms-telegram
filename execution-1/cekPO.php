<?
// https://ariefsan.basproject.online/telegram/execution-1/cekPO.php
require '../00-01-conn-agent.php';


$tanggal_akhir = date('Y-m-d');


$sql = "
    select 
    a.status_id
 , b.username as `NAMA USER`
 , a.total_po as `TOTAL PO`
 , a.created as `WAKTU PUSH PO to SAP`
from po_to_sap a 
join web_user b on b.id_web_user=a.created_by
where a.po_to_sap_date='$tanggal_akhir'
and a.status_id='1'
and a.total_po>10; 
";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-AGENT (Push PO DMS >10) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nama User :" . $row['NAMA USER'] . "\n" .
          		"TOTAL PO :" . $row['TOTAL PO'] . "\n" .
  				"Waktu Push :" . $row['WAKTU PUSH PO to SAP'] . "\n \n"
                    ;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendMessage?chat_id=-1001542755544&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output;
} 

