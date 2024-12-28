<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/fainn/fainn-03-agent-CekCurrentBSDepo.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depo = $json[1];
#$cutdate = $json[2];
#$depo = '50006962';
#$cutdate = '2023-05-05';

$sql = "select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, isn.product_id 
, (select p.product_code from product p where p.product_id=isn.product_id) as product_code
, (select p.short_name  from product p where p.product_id=isn.product_id) as short
, sum(isn.stock_bs) as bsdepo
, now() as timegenerated
from inventory_salesman_new isn 
join depo d on d.depo_id = isn.depo_id 
where 0=0
and d.depo_code = '$depo'
and isn.salesman_id in (select ul.user_id  from user_login ul where ul.is_active = 1 and ul.role_id=3)
group by isn.product_id
having bsdepo > 0
UNION 
select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, '' as product_id 
, 'Endline' as product_code
, '-' as short_name
, '-' as stockbs
, now() as timegenerated
from depo d 
where 0=0 and d.depo_code = '$depo'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "";

if(mysqli_num_rows($exec) > 0) {
  	echo "Line39";
    
	while($row= mysqli_fetch_array($exec)) {
      
      	if($dataMessage == ""){
          	$dataMessage .= "*Saldo Stock BS Depo*\n";
          	$dataMessage .= "Generate : ". $row['timegenerated']. "\n";
        	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. "\n \n" ;
        }
      
		$dataMessage .= $row['product_code']. "-". $row['short']. " : ". $row['bsdepo']. "\n" ;      

      	
      	

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
  
  	$dataMessage .= "\n";
    #$dataMessage .= "BS Toko : Roti BS Yang belum kembali ke Depo. \n";
  	$dataMessage .= "BS Depo : Roti BS Yang belum kembali ke Plant. \n";
	$config["whatsappSendMessage"]($config['key-wa-bas'], $dataMessage , $sender, "true");
  	# $config["whatsappSendMessage"]($config['key-wa-bas'], $dataMessage , $config['id-wa-arya'], "false");
}
  
# print($dataMessage);
# $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
# $writeResult = file_put_contents("download/". $filename, $dataMessage);

# if ($writeResult != false) {
  # $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  # $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
# }





