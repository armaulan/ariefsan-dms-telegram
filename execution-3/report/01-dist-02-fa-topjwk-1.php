<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-02-fa-topjwk-1.php
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
s.depo_id
, (select d.depo_code from depo d where d.depo_id= s.depo_id) as depo_code
, (select d.depo_name from depo d where d.depo_id= s.depo_id) as depo_name
, s.store_id 
, s.store_code 
, s.store_name 
, (select st.store_type_name from store_type st where st.store_type_id = s.store_type_id) as store_type
, s.top_id 
, IFNULL((select (jp.monday+jp.tuesday+jp.wednesday+jp.thursday+jp.friday+jp.saturday+jp.sunday) from journey_plan jp where jp.store_id=s.store_id and jp.is_active = 1 order by jp.user_id desc limit 1),0) as jwk
, (select max(d2.dropping_date) from dropping d2 where d2.store_id=s.store_id) as last_drop
, date(s.created) as created
from store s 
where 0=0
and s.is_active = 1
and s.depo_id in (select d.depo_id from depo d where d.owner_id=28)
and s.depo_id in (select po.depo_id from purchase_order po where po.po_date > DATE_SUB(CURRENT_DATE(), interval 3 day))
# and s.classification_id = 610
and s.store_type_id in (745,746,747,748,749,767,731,730,757,758,759,736,727, 838, 834, 759, 844, 840, 843, 839, 842)
and s.top_id = 7
having jwk > 1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "depo_code;depo_name;store_id;store_code;store_name;store_type;top_id;jwk;last_drop;create_date\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['store_id']. ";";
      	$dataMessage .= $row['store_code']. ";";
      	$dataMessage .= $row['store_name']. ";";
      	$dataMessage .= $row['store_type']. ";";
      	$dataMessage .= $row['top_id']. ";";
      	$dataMessage .= $row['jwk']. ";";
      	$dataMessage .= $row['last_drop']. ";";
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  # $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  # $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/download/".  $filename);
}