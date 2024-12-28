<?
// https://ariefsan.basproject.online/telegram/execution-1/02-21-dist-cek-TOP.php
require '../00-02-conn-dist.php';
//cek-store id 408430


$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
//$param = $json[1];
$value = $json[1];

//$hari_final = "-". $hari . " days";

//$tanggal_awal = date('Y-m-d', strtotime($hari_final));
//$tanggal_akhir = date('Y-m-d');


$sql = "select h.depo_code as `KODE DEPO`, 
h.depo_name as `NAMA DEPO`,
a.request_number as `NOMOR REQUEST`, 
b.where_value as `STORE ID`,
d.store_code as `STORE CODE`,
d.store_name as `STORE NAME`,
g.req_value_from as `REQUEST FROM`,
g.req_value_to as `REQUEST TO`,
c.status_name as `STATUS`,
i.action_type,
i.action_date as `ACTION DATE`,
i.notes as `NOTES`
from req_aprv_header a
join req_aprv_where b on a.req_aprv_header_id = b.req_aprv_header_id
join req_aprv_status c on c.status_id = a.status_id
left join store d on d.store_id = b.where_value
join req_aprv_type e on e.req_aprv_type_id = a.req_aprv_type_id
join store_type f on f.store_type_id = d.store_type_id
join req_aprv_item g on g.req_aprv_header_id = a.req_aprv_header_id
join req_aprv_history i on i.req_aprv_header_id = a.req_aprv_header_id
join depo h on h.depo_id = d.depo_id
where e.req_aprv_type_id = 1
and g.req_value_to = $value
and c.status_id = 2
and f.store_type_id in 
('701',
'702',
'703',
'756',
'719',
'742',
'722',
'723',
'715',
'743',
'732',
'733',
'734',
'740',
'744') 
and i.action_date > '2022-11-16'
and i.action_type = 'approval';";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Approval Up TOP 1 ) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo : " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo : \n" . $row['NAMA DEPO'] . "\n" .
                "Store ID: " . $row['STORE ID'] . "\n" .
                "Store Code: " . $row['STORE CODE'] . "\n" .
          		"Store Name:" . $row['STORE NAME'] . "\n" .
          		"Nomor Request: \n" . $row['NOMOR REQUEST'] . "\n" .
          		"Request Dari: " . $row['REQUEST FROM'] . "\n" .
          		"Request Ke: " . $row['REQUEST TO'] . "\n" .
          		"Status: " . $row['STATUS'] . "\n" .
           		"Action Date: ". $row['ACTION DATE'] . "\n" .
                "Notes :  ". $row['NOTES'] . "\n \n \n" 
					;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output;
} 
