<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-06-approvalsaleseast.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

$sql = "select d.depo_code as `depo code`
, d.depo_name as `depo name`
, raw.where_value as `store id`
, s.store_name as `store name`
, rah.request_number as `nomor request`
-- , rai.req_value_from_text as `data before` 
-- , rai.req_value_to_text as `data after`
, rah.notes as `notes requester`
, rah.request_date as `tanggal request`
, wu.username as `username`
, wu.fullname as `fullname`
, p.plant_name as `plant`
, rat.req_aprv_type_name as `request type`
, (select count(rah3.req_aprv_header_id) from req_aprv_header rah3 where rah3.req_aprv_header_id = rah.req_aprv_header_id and rah3.req_aprv_type_id = rat.req_aprv_type_id) as `Total`
from req_aprv_header rah 
join req_aprv_type rat on rat.req_aprv_type_id = rah.req_aprv_type_id 
join req_aprv_status ras on ras.status_id = rah.status_id 
join depo d on d.depo_id = rah.depo_id
join req_aprv_where raw on raw.req_aprv_header_id = rah.req_aprv_header_id
join store s on s.store_id = raw.where_value 
join plant p on p.plant_id = d.plant_id
join store_classification sc on sc.classification_id = s.classification_id
join req_aprv_history rah2 on rah2.req_aprv_header_id = rah.req_aprv_header_id
join web_user wu on wu.id_web_user = rah.request_by 
where ras.status_id = 1
and rah.request_date > '2023-05-01'
and rah.req_aprv_type_id = 8
and d.owner_id = 28
and sc.mainstore_id = 1
order by total ASC";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);

$msg = "*02-Dist (Request Flag SKU DMS BUM MTI)* \n";
$msg2 = " \n \n";
$total = 0 ;

$dataMessage = "Nomor Request;Request Type;Tanggal Request;PIC;Plant;Kode Depo;Nama Depo;Store ID;Store Name;Notes\n";


if(mysqli_num_rows($exec) > 0) {
         
    while($row = mysqli_fetch_array($exec)) {
        
        $msg2 .="Nomor Request : *" . $row['nomor request'] . "*\n" .
          		"Request Type : *" . $row['request type'] . "*\n" .
          		"Tanggal Request : *" . $row['tanggal request'] . "*\n" .
          		"PIC Request : *" . $row['fullname'] . "*\n" .
          		"Plant : *" . $row['plant'] . "*\n" .
          		"Kode Depo : " . $row['depo code'] . "\n" .
          		"Nama Depo : \n" . $row['depo name'] . "\n" .
          		"Store ID : " . $row['store id'] . "\n" .
          		"Store Name : \n" . $row['store name'] . "\n" .
          		"Notes : *" . $row['notes requester'] . "*\n \n" 
                 ;
      
      	$dataMessage .= "'". $row['nomor request']. ";";
      	$dataMessage .= $row['request type']. ";";
      	$dataMessage .= $row['tanggal request']. ";";
      	$dataMessage .= $row['fullname']. ";";
      	$dataMessage .= $row['plant']. ";";
      	$dataMessage .= $row['depo code']. ";";
      	$dataMessage .= $row['depo name']. ";";
      	$dataMessage .= $row['store id']. ";";
      	$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store name']). ";'";
      	$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['notes requester']). "\n";

      $total ++;
      }
  
# print($dataMessage);
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_flagsku_MTINasional.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

$msg .= "*Jumlah Request : " . $total . "*\n*https://dms.sariroti.com*";
# $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], 'Crew BAS', $msg . $msg2  );
# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-riky'], "false");
$config["whacenterSendMessage"]($config['key-whacenter-1'], '081132214971', $msg . $msg2  ); # 081132214971
  
if ($writeResult != false) {
  $config["whacentersenddoc"]($config['key-whacenter-1'],  "081132214971", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/download/". $filename);
}
  
unlink("download/" . $filename);
 
  
}