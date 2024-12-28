<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/fainn/fainn-04-agent-CekStockDepo.php
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
gr.depo_id 
, d.depo_code 
, d.depo_name 
, grd.product_id
, (select p.product_code from product p where p.product_id=grd.product_id) as pcode
, (select p.short_name from product p where p.product_id=grd.product_id) as pshort
, sum(grd.qty_received) stockdepo
, now() as timegenerated
from goods_received gr 
join goods_received_detail grd on grd.gr_id = gr.gr_id 
join depo d on d.depo_id = gr.depo_id 
where 0=0
and d.depo_code = '$depo'
and gr.status_id = 1
and grd.qty_received > 0
group by grd.product_id 
union
select 
as2.depo_id 
, d.depo_code 
, d.depo_name 
, asd.product_id 
, (select p.product_code from product p where p.product_id=asd.product_id) as pcode
, (select p.short_name from product p where p.product_id=asd.product_id) as pshort
, sum(asd.qty_allocation) as stockdepo
, now() as timegenerated
from allocation_salesman as2 
join depo d on d.depo_id = as2.depo_id 
join allocation_salesman_detail asd on asd.allocation_id = as2.allocation_id 
where 0=0
and d.depo_code = '$depo'
and asd.is_taken = 0
and asd.qty_allocation > 0
group by asd.product_id 
UNION 
select 
dp.depo_id 
, dp.depo_code 
, dp.depo_name 
, '' as product_id
, 'Endline' as pcode
, '-' as pshort
, '' as stockdepo
, now() as timegenerated
from depo dp 
where dp.depo_code = '$depo'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "";

if(mysqli_num_rows($exec) > 0) {
  	echo "Line39";
    
	while($row= mysqli_fetch_array($exec)) {
      
      	if($dataMessage == ""){
          	$dataMessage .= "*Stock Depo (GR Belum Taken Alokasi)*\n";
          	$dataMessage .= "Generated : ". $row['timegenerated']. "\n";
        	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. "\n \n" ;
        }
      
		$dataMessage .= $row['pcode']. "-". $row['pshort']. " : ". $row['stockdepo']. "\n" ;    

      	
      	

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
    $dataMessage .= "Stock Depo : Adalah stock yang sudah di GR tetapi belum ditaken hawker \n";
  	# print($dataMessage);
  	# $dataMessage .= "BS Depo : Roti BS Yang belum kembali ke Plant. \n";
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





