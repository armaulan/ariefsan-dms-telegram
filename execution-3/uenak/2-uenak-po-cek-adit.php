<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/uenak/2-uenak-po-cek-adit.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$cutdate = $json[1];
#$cutdate = '2024-09-19';

$sql = "select 
right(eo.estimate_order_number,10) as estimate_order_number
, ul.name 
, eo.suggest_order_date 
, eo.notes
, eo.total_quantity 
from estimate_order eo 
join depo d on d.depo_id = eo.depo_id 
join user_login ul on ul.user_id = eo.salesman_id 
where 0=0
and eo.depo_id = 700
and eo.suggest_order_date = '$cutdate'
and eo.status_id = 2
and ul.name like '%uenak%'
and eo.total_quantity > 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "*List Estimasi PO $cutdate*\n \n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['estimate_order_number']. " (". $row['total_quantity']. ")\n". $row['name']. "\n\n" ;

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
  
    echo "Sending... ";
	$config["whatsappSendMessage"]($config['key-wa-bas'], $dataMessage , $sender, "true");
	echo "Done... ";
} else {
    $config["whatsappSendMessage"]($config['key-wa-bas'], 'Masih belum om adit, bojo kudune loro !' , $sender, "true");
}
  
# print($dataMessage);
# $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
# $writeResult = file_put_contents("download/". $filename, $dataMessage);

# if ($writeResult != false) {
  # $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  # $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
# }





