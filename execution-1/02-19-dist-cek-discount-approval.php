<?
// https://ariefsan.basproject.online/telegram/execution-1/02-19-dist-cek-discount-approval.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$store = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "

select rah.req_aprv_header_id 
, d.depo_code 
, d.depo_name 
, s.store_id 
, s.store_code 
, s.store_name 
, st.store_type_name 
, raw.req_aprv_header_id 
, raw.where_field 
, rah.request_date 
, rah.request_by 
, rah.request_number 
, wu.username 
, wu.fullname 
, rah.status_id 
, ras.status_name 
, rai.req_value_from 
, rai.req_value_to 
, (select rah2.action_date from req_aprv_history rah2 where rah2.req_aprv_header_id= raw.req_aprv_header_id order by rah2.req_aprv_history_id desc limit 1) modified
, (select rah2.action_type from req_aprv_history rah2 where rah2.req_aprv_header_id= raw.req_aprv_header_id order by rah2.req_aprv_history_id desc limit 1) action_type
, (select ds.discount_value  from discount_store ds where ds.store_id=s.store_id and ds.product_id=19) current_disc
from store s
join depo d on d.depo_id = s.depo_id 
join store_type st on st.store_type_id = s.store_type_id 
left join req_aprv_where raw on raw.where_value = s.store_id 
left join req_aprv_header rah on rah.req_aprv_header_id = raw.req_aprv_header_id 
left join web_user wu on wu.id_web_user = rah.request_by 
left join req_aprv_status ras on ras.status_id = rah.status_id 
left join req_aprv_item rai on rai.req_aprv_header_id = raw.req_aprv_header_id
where 0=0
and s.store_id = $store
and raw.where_field = 'discount_store.store_id'
and rah.status_id = 2
or 0=0
and s.store_code = '$store'
and raw.where_field = 'discount_store.store_id'
and rah.status_id = 2
limit 10

";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-DIST History Disc Approval \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Depo:" . $row['depo_name'] . "\n" .
				"Docs:" . $row['request_number'] . "\n" .
          		"Store Id:" . $row['store_id'] . "\n" .
          		"Store Name:" . $row['store_name'] . "\n" .
				"Type Name:" . $row['store_type_name'] . "\n" .
				"Tanggal Approve:" . $row['modified'] . "\n" .
				"Before:" . $row['req_value_from'] . "\n" .
          		"After:" . $row['req_value_to'] . "\n" .
				"RequestBy:" . $row['fullname'] . "\n \n" 
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
