<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-05-fa-storedata-3.php
require '../../00-02-conn-dist.php';
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
(select UPPER(p.plant_name) from plant p join depo d on d.plant_id=p.plant_id where d.depo_id=s.depo_id) as plant
, d.depo_code 
, d.depo_name 
, s.store_id 
, s.store_code 
, s.store_name 
, s.owner_no_ktp 
, s.npwp
, date(s.created) as created
, if(s.is_pkp=1,'PKP','NON-PKP') pkp
, (select snn.last_status_by_faro from store_noo_new snn join store_noo_detail_new sndn on sndn.store_noo_id=snn.store_noo_id where snn.store_id=s.store_id and sndn.`role`='faro' and sndn.status='A') faro_approval
from store s 
join depo d on d.depo_id = s.depo_id 
where 0=0
and s.is_active = 1
and d.owner_id = 28
and s.owner_no_ktp = '0000000000000000'
and s.is_pkp = 0
having created >= '2022-06-01'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "plant;depo_code;depo_name;store_id;store_code;store_name;owner_ktp;npwp;created;pkp;faro_approval\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['plant']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['store_id']. ";";
      	$dataMessage .= $row['store_code']. ";";
      	$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store_name']). ";'";
      	$dataMessage .= $row['owner_no_ktp']. ";'";
      	$dataMessage .= $row['npwp']. ";";
      	$dataMessage .= $row['created']. ";";
      	$dataMessage .= $row['pkp']. ";";
      	$dataMessage .= $row['faro_approval']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_rca_approval.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Screening KTP Rca Approval", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/download/".  $filename);
}





