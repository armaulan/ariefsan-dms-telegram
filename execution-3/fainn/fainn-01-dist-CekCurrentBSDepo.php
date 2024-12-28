<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/fainn/fainn-01-dist-CekCurrentBSDepo.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depo = $json[1];
# $cutdate = $json[2];

#$depo = '70002150';
#$cutdate = '2023-05-05';

$sql = "select 
d.depo_id 
, d.depo_code 
, d.depo_name 
, pd.product_id 
, (select p.product_code from product p where p.product_id=pd.product_id) as product_code
, (select p.short_name from product p where p.product_id=pd.product_id) as short
, IFNULL(aaa.stock_bs,0)  as bs_toko
, IFNULL(bbb.stock_bs,0) as bs_depo
, now() as timegenerated
from product_depo pd 
join depo d on d.depo_id = pd.depo_id 
left join ( #bstoko
	select 
	isn.product_id 
	, sum(isn.stock_bs) as stock_bs
	, 'BS Toko' as description
	from inventory_salesman_new isn 
	join depo d on d.depo_id = isn.depo_id 
	where 0=0
	and d.depo_code = '$depo'
	and isn.salesman_id in (select ul.user_id from user_login ul where ul.is_active=1)
	group by isn.product_id 
	having stock_bs > 0
	) as aaa on aaa.product_id = pd.product_id  
left join ( #bsdepo
	select 
	aa.product_id
	, sum(aa.stock) as stock_bs
	, 'BS Depo' as description
	from (
	SELECT 
	rbdd.product_id 
	, CASE 
		when rbdd.quantity  > rbdd.posisi_stock then rbdd.posisi_stock 
		else rbdd.quantity 
		END as stock
	from retur_bs_depo rbd 
	join depo d on d.depo_id = rbd.depo_id 
	join retur_bs_depo_detail rbdd on rbdd.retur_bs_depo_id =rbd.idretur_bs_depo 
	where 0=0
	and d.depo_code = '$depo'
	and rbd.retur_bs_status_id = 1
	and rbd.retur_type = 'fisik'
	order by rbd.idretur_bs_depo desc
	) as aa
	group by aa.product_id
	) as bbb on bbb.product_id = pd.product_id
where 0=0
and d.depo_code = '$depo'
and pd.is_active = 1
having bs_toko + bs_depo > 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      
      	if($dataMessage == ""){
          	$dataMessage .= "*Saldo Stock BS Depo*\n";
          	$dataMessage .= "Generate : ". $row['timegenerated']. "\n";
        	$dataMessage .= $row['depo_code']. "-". $row['depo_name']. "\n \n" ;
        }
      
		$dataMessage .= $row['product_code']. "-". $row['short']. " > BSToko : ". $row['bs_toko']. ", BSDepo : ". $row['bs_depo']. "\n" ;      

      	
      	

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
    $dataMessage .= "BS Toko : Roti BS Yang belum kembali ke Depo. \n";
  	$dataMessage .= "BS Depo : Roti BS Yang belum kembali ke Plant. \n";
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




