<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-06-approval.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

$sql = "select d.depo_code as `depo code`
, d.depo_name as `depo name`
, raw.where_value as `store id`
, s.store_name as `store name`
, rah.request_number as `nomor request`
, rai.req_value_from_text as `data before` 
, rai.req_value_to_text as `data after`
, rah.notes as `notes requester`
, rah.request_date as `tanggal request`
, wu.username as `username`
, wu.fullname as `fullname`
, rat.req_aprv_type_name as `request type`
, (select count(rah3.req_aprv_header_id) from req_aprv_header rah3 where rah3.req_aprv_header_id = rah.req_aprv_header_id and rah3.req_aprv_type_id = rat.req_aprv_type_id) as `Total`
from req_aprv_header rah 
join req_aprv_type rat on rat.req_aprv_type_id = rah.req_aprv_type_id 
join req_aprv_status ras on ras.status_id = rah.status_id 
join depo d on d.depo_id = rah.depo_id
join req_aprv_where raw on raw.req_aprv_header_id = rah.req_aprv_header_id
join store s on s.store_id = raw.where_value 
join req_aprv_history rah2 on rah2.req_aprv_header_id = rah.req_aprv_header_id
join web_user wu on wu.id_web_user = rah.request_by
join req_aprv_item rai on rai.req_aprv_header_id = rah.req_aprv_header_id 
where ras.status_id = 1
and rah.request_date > '2023-05-01'
and d.owner_id = 28
and rat.req_aprv_type_id in ('1','4','5')";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*02-Dist (Request Appoval TOP, Max Faktur, CL)* \n \n";
$msg2 = " \n \n";
$total = 0 ;
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nomor Request : *" . $row['nomor request'] . "*\n" .
          		"Request Type : *" . $row['request type'] . "*\n" .
          		"Tanggal Request : *" . $row['tanggal request'] . "*\n" .
          		"Kode Depo : " . $row['depo code'] . "\n" .
          		"Nama Depo : \n" . $row['depo name'] . "\n" .
          		"Store ID : " . $row['store id'] . "\n" .
          		"Store Name : \n" . $row['store name'] . "\n" .
          		"Before Req : " . $row['data before'] . "\n" .
          		"After Req : " . $row['data after'] . "\n" .
          		"Notes : *" . $row['notes requester'] . "*\n \n" 
                    ;
      $total ++;
      }


	$msg .= "*Jumlah Request : " . $total . "*\n*https://dms.sariroti.com*";
  	# $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], 'Crew BAS', $msg2 . $msg  );
    # $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-riky'], "false");
    $config["whacenterSendMessage"]($config['key-whacenter-1'], '085694787887' , $msg2 . $msg );
  
    }