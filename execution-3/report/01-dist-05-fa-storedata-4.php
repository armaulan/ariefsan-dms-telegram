<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-05-fa-storedata-4.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
$start = $json[1];
$end = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

$sql = "
(select p.plant_name from plant p join depo d2 on d2.plant_id=p.plant_id where d2.depo_id=d.depo_id) as plant_name
, d.depo_code 
, d.depo_name 
, po.selling_date 
, po.total_quantity
, (select count(DISTINCT eod.store_id) from purchase_order_estimate poe join estimate_order eo on eo.estimate_order_id=poe.estimate_order_id join estimate_order_detail eod on eod.estimate_order_id = eo.estimate_order_id where poe.purchase_order_id=po.po_id and eod.quantity > 0) total_store
, po.total_amount
from purchase_order po 
join depo d on d.depo_id = po.depo_id and d.owner_id = 28
where 0=0
and po.po_id >= 600000
and po.selling_date between '$start' and '$end'
and po.status_id <> 3
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "plant_name;depo_code;depo_name;selling_date;total_quantity;total_store;total_amount\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
	    $dataMessage .= $row['plant_name']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['selling_date']. ";";
      	$dataMessage .= $row['total_quantity']. ";";
      	$dataMessage .= $row['total_store']. ";";
      	$dataMessage .= $row['total_amount']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_estimate_po.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "PO Estimate INNDI", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/download/". $filename);
}





