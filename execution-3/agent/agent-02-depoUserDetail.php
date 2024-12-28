<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/agent/agent-02-depoUserDetail.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depo = $json[1];
# $cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

$sql = "select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, (select max(po.po_date) from purchase_order po where po.depo_id=d.depo_id) last_po
, date(d.created) as depo_created
, ul.user_id 
, ul.username 
, ul.role_id 
, (select ur.role_name from user_role ur where ur.role_id=ul.role_id) as role 
, date(ul.created) as user_created
, IFNULL((select max(as2.tanggal_selling) from agent_selling as2 where as2.depo_id=d.depo_id and as2.user_id=ul.user_id),'') last_selling
, IFNULL((select IF(ud.username is not null, 1, 0) from user_device ud where ud.username=ul.username),0) is_login
, IFNULL((select sum(isn.stock_good) from inventory_salesman_new isn where isn.depo_id=ul.depo_id and isn.salesman_id=ul.user_id),0) as stock
from depo d
join user_login ul on ul.depo_id =d.depo_id 
where d.depo_code = '$depo'
and ul.is_active = 1
and ul.role_id <> 7
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      
      	if($dataMessage == ""){
        	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. " (". $row['last_po']. ")\n \n" ;
        }
      
		$dataMessage .= $row['user_id']. "-". $row['username']. " | ". $row['role']. "\n";      
      		$dataMessage .= "created :". $row['user_created']. "\n";
      		$dataMessage .= "is_login :". $row['is_login']. "\n";
      		$dataMessage .= "sellingLast :". $row['last_selling']. "\n";
      		$dataMessage .= "stock :". $row['stock']. "\n \n";
      	
      	

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





