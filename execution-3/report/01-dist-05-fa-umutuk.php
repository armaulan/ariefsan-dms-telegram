<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-05-fa-umutuk.php
exit();
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
$cutdate = $json[1];

#$depocode = '70002157';
#$cutdate = '2023-05';

$sql = "select distinct
p.plant_name 
, d.depo_code 
, d.depo_name 
, s.store_code 
, s.store_name 
, s.owner_no_ktp 
, s.bank_account_name 
, ptd.invoice_date 
, pk.penerimaan_date
, (select psak_value from psak_transaction_detail ptd2 where ptd2.penerimaan_kasir_number=pk.penerimaan_kasir_number and ptd2.invoice_date=ptd.invoice_date and ptd2.store_id=ptd.store_id and ptd2.psak_rules_id in (1,5,7)) as um
, IFNULL((select psak_value from psak_transaction_detail ptd2 where ptd2.penerimaan_kasir_number=pk.penerimaan_kasir_number and ptd2.invoice_date=ptd.invoice_date and ptd2.store_id=ptd.store_id and ptd2.psak_rules_id in (2,6,8)),0) as ut 
, 0 as uk
from penerimaan_kasir pk 
left join psak_transaction_detail ptd on ptd.penerimaan_kasir_number = pk.penerimaan_kasir_number 
left join store s on s.store_id = ptd.store_id 
left join depo d on d.depo_id = s.depo_id 
left join plant p on p.plant_id = d.plant_id 
where 0=0
and pk.penerimaan_date like '$cutdate%'
and ptd.psak_transaction_detail_id is not null
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "plant_name;depo_code;depo_name;store_code;store_name;owner_no_ktp;bank_account_name;invoice_date;penerimaan_date;um;ut;uk\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['plant_name']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['store_code']. ";";
      	$dataMessage .= $row['store_name']. ";'";
      	$dataMessage .= $row['owner_no_ktp']. ";";
      	$dataMessage .= $row['bank_account_name']. ";";
      	$dataMessage .= $row['invoice_date']. ";";
      	$dataMessage .= $row['penerimaan_date']. ";";
      	$dataMessage .= $row['um']. ";";
      	$dataMessage .= $row['ut']. ";";
      	#$dataMessage .= $row['uk']. ";";
      	#$dataMessage .= $row['top_id']. ";";
      	#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store_name']). ";";
		#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['owner_name']). ";";
		#$dataMessage .= "`". str_replace(array("\r", "\n", ";", "\t"), "" , $row['phone']). ";";
      	$dataMessage .= $row['uk']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_umutuk_dist.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Testing", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $filename);
}





