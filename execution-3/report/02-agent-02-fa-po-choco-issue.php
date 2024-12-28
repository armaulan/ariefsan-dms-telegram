<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/02-agent-02-fa-po-choco-issue.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

#$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
#$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

$sql = "select 
aa.*,
grd.product_id 
, p.product_code 
, p.product_name 
, grd.qty_received 
, grd.cbp 
from goods_received_detail grd  
join (
	select 
	gr.gr_id 
	, gr.depo_id 
	, d.depo_code 
	, d.depo_name 
	, gr.gr_number 
	, (select concat('`',po.po_number) from purchase_order po where po.po_id=gr.po_id) as po_number
	, gr.received_date 
	, gr.selling_date 
	, gr.created 
	, IFNULL((select grd.product_id from goods_received_detail grd where grd.gr_id=gr.gr_id and grd.product_id not in (215,216,217,218) and grd.qty_received > 0 limit 1),0) is_gr_sr
	, IFNULL((select grd.product_id from goods_received_detail grd where grd.gr_id=gr.gr_id and grd.product_id in (215,216,217,218) and grd.qty_received > 0 limit 1),0) is_gr_ch
	from goods_received gr 
	join depo d on d.depo_id = gr.depo_id 
	where 0=0
	and gr.received_date >= DATE_SUB(CURRENT_DATE(), interval 50 day)
	and d.owner_id = 28
	and gr.status_id <> 2
	having is_gr_sr > 0 and is_gr_ch > 0
) as aa on grd.gr_id = aa.gr_id 
join product p on p.product_id = grd.product_id 
where 0=0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "depo_code;depo_name;po_number;received_date;selling_date;product_code;product_name;qty_received;cbp;created\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['po_number']. ";";
      	$dataMessage .= $row['received_date']. ";";
      	$dataMessage .= $row['selling_date']. ";";
      	$dataMessage .= $row['product_code']. ";";
      	$dataMessage .= $row['product_name']. ";";
      	$dataMessage .= $row['qty_received']. ";";
      	$dataMessage .= $row['cbp']. ";";
      	$dataMessage .= $row['created']. "\n";
      
      	# Prepare Product untuk menggunakan product_code, sebagai bantuan untuk sortir nanti
		#$customProd = $row['product_code']. "-". $row['short_name'];
      
      	# Masukkan list semua product kedalam variable dataHeader
      	#array_push($dataHeader, $customProd);
        
      	#$tempDataRow = $row['store_id']. "$". $row['store_name'];
      	# Masukkan store_id kedalam object dataBody, dengan sifat unique/distinct
      	#$dataBody += [ $tempDataRow  => array() ];
      	
      	# Setelah toko sdh di insert di object, maka toko ini juga diinsert array (multidimensi)
      	#$dataBody[$tempDataRow] += [$customProd => array($row['qty_estimasi'], $row['qty_dropping'], $row['qty_sold'], $row['qty_bs']) ];
      
      }
}
  
# print($dataMessage);
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_PO_Choco_Issue.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter PO Choco & SR", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
}





