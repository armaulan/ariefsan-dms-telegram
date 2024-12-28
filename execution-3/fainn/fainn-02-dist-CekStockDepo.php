<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/fainn/fainn-02-dist-CekStockDepo.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();


# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depo = $json[1];
# $cutdate = $json[2];

# $depo = '70002157';
#$cutdate = '2023-05-05';

$sql = "select 
aa.depo_id
, aa.depo_code
, aa.depo_name
, aa.product_id
, (select p.product_code  from product p where p.product_id= aa.product_id) as product_code
, (select p.short_name from product p where p.product_id= aa.product_id) as short
, sum(aa.qty) stock_depo
, now() as timegenerated
from (
select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, grd.product_id 
, grd.qty_received as qty
, 'Outstanding Allocation' as remarks
from goods_received gr 
join depo d on d.depo_id = gr.depo_id 
join goods_received_detail grd on grd.gr_id = gr.gr_id 
where 0=0
and gr.status_id = 1
and d.depo_code = '$depo'
and grd.is_allocated = 0
and grd.qty_received > 0
UNION
select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, asd.product_id 
, asd.qty_allocation as qty
, 'Outstanding Taken' as remarks
from allocation_salesman as2 
join depo d on d.depo_id = as2.depo_id 
join allocation_salesman_detail asd on as2.allocation_id = asd.allocation_id 
where 0=0
and d.depo_code = '$depo'
and asd.is_taken = 0
having qty_allocation > 0
) as aa 
group by aa.product_id
UNION 
select
d.depo_id 
, d.depo_code 
, d.depo_name 
, '0' as product_id 
, 'end' as product_code
, 'line' as short
, '' as stock_depo
, now() as timegenerated
from depo d 
where 0=0
and d.depo_code = '$depo'
";

print($sql);

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# var_dump($exec);

$dataMessage = "";

if(mysqli_num_rows($exec) > 0) {
  	print(123);
    
	while($row= mysqli_fetch_array($exec)) {
      
      	if($dataMessage == ""){
          	$dataMessage .= "*Stock Depo (GR Belum Taken Alokasi)*\n";
          	$dataMessage .= "Generated : ". $row['timegenerated']. "\n";
        	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. "\n \n" ;
        }
      
		$dataMessage .= $row['product_code']. "-". $row['short']. " : ". $row['stock_depo']. "\n" ;   
      
      	#print($dataMessage);


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
    $dataMessage .= "Stock Depo : Adalah stock yang sudah di GR tetapi belum ditaken salesman \n";
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





