<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/dist/dist-01-deposearch.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depo = $json[1];
#$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

$sql = "select 
d.depo_id 
, d.depo_code
, d.depo_name 
, (select max(po.po_date) from purchase_order po where po.depo_id=d.depo_id) last_po
, if(dec2.ritase=1,'RIT 1',if(dec2.ritase=2,'RIT 2',if(dec2.ritase= 3,'RIT 3','BELUM SET RIT'))) as `RITASE`
from depo d 
join depo_entity_code dec2 on dec2.depo_id = d.depo_id
where 0=0
and d.depo_name like '%$depo%'
or d.depo_code like '%$depo%'
or d.depo_id like '%$depo%'
order by last_po desc
limit 10
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "List Depo\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. "-". $row['RITASE']. " " . "(". $row['last_po']. ")\n" ;

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
  
	$config["whatsappSendMessage"]($config['key-wa-bas'], $dataMessage , $sender, "true");
}
  
# print($dataMessage);
# $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
# $writeResult = file_put_contents("download/". $filename, $dataMessage);

# if ($writeResult != false) {
  # $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  # $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
# }





