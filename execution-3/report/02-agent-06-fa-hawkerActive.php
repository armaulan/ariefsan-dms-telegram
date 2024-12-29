<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/02-agent-06-fa-hawkerActive.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
$cutdate = $json[1];

#$depocode = '70002157';
#$cutdate = '2024-05';

$sql = "select distinct
'$cutdate' as month_transaction
, p.plant_name
, d.depo_code 
, d.depo_name 
, pk.salesman_id as hakwer_id
, ul.name as hawker_name
, ul.ktp_number 
, ul.bank_account_name 
from penerimaan_kasir pk 
left join user_login ul on ul.user_id = pk.salesman_id 
left join depo d on d.depo_id = ul.depo_id 
left join plant p on p.plant_id = d.plant_id 
where 0=0
and pk.penerimaan_kasir_id >= 5348208 # 2024 bulan 2
and pk.penerimaan_date like '$cutdate%'
and d.owner_id = 28
and ul.role_id = 2
and d.is_dummy = 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "month_transaction;plant_name;depo_code;depo_name;hakwer_id;hawker_name;ktp_number;bank_account_name\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['month_transaction']. ";";
      	$dataMessage .= $row['plant_name']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['hakwer_id']. ";";
      	$dataMessage .= $row['hawker_name']. ";'";
      	$dataMessage .= $row['ktp_number']. ";";
      	$dataMessage .= $row['bank_account_name']. "\n";
      	#$dataMessage .= $row['um']. ";";
      	#$dataMessage .= $row['ut']. ";";
      	#$dataMessage .= $row['uk']. ";";
      	#$dataMessage .= $row['top_id']. ";";
      	#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store_name']). ";";
		#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['owner_name']). ";";
		#$dataMessage .= "`". str_replace(array("\r", "\n", ";", "\t"), "" , $row['phone']). ";";
      	#$dataMessage .= $row['owner_id']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_act-hawker.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Hawker Active", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/download/". $filename);
}





