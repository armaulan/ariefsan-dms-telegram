<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-01-EstimateVsDropping.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depocode = $json[1];
$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

$sql = "select aaa.depo_id 
, aaa.store_id
, (select s.store_name from store s where s.store_id = aaa.store_id) as store_name
, aaa.product_id 
, (select p.short_name from product p where p.product_id = aaa.product_id) as short_name
, (select p.product_code from product p where p.product_id = aaa.product_id) as product_code
, aaa.tanggal
, IFNULL(bbb.qty,0) as qty_estimasi
, IFNULL(ccc.qty,0) as qty_dropping
, IFNULL((select sum(td.qty_drop - td.qty_return_bs - td.qty_return_good)  from tagihan t join tagihan_detail td on td.tagihan_id=t.tagihan_id where t.store_id=aaa.store_id and t.dropping_date='$cutdate' and td.product_id=aaa.product_id),0) as qty_sold
, IFNULL((select sum(td.qty_return_bs) from tagihan t join tagihan_detail td on td.tagihan_id=t.tagihan_id where t.store_id=aaa.store_id and t.dropping_date='$cutdate' and td.product_id=aaa.product_id),0) as qty_bs
from (
	select 
	eod.depo_id 
	, eod.store_id 
	, eod.product_id 
	, SUM(eod.quantity) as qty
	, '$cutdate' as tanggal
	, 'estimate' as trans
	from estimate_order_detail eod 
	where 0=0
	and eod.estimate_order_id in (
		select aa.estimate_order_id from (
			select 
			eo.estimate_order_id 
			, IFNULL((select po.status_id from purchase_order_estimate poe join purchase_order po on po.po_id=poe.purchase_order_id where poe.estimate_order_id=eo.estimate_order_id and po.status_id=1),0) is_gr
			from estimate_order eo 
			where 0=0
			and eo.suggest_order_date = '$cutdate'
			# and eo.depo_id = 302
			and eo.depo_id in (select dp.depo_id from depo dp where dp.depo_code = '$depocode')
			and eo.status_id = 3
			having is_gr = 1
			) as aa
		)
	and eod.quantity > 0
	group by eod.store_id, eod.product_id , tanggal
	UNION
	select 
	d.depo_id 
	, d.store_id 
	, dd.product_id 
	, sum(dd.qty_drop) as qty
	, d.dropping_date as tanggal
	, 'dropping' as trans
	from dropping d 
	join dropping_detail dd on dd.dropping_id = d.dropping_id 
	join depo dp on dp.depo_id = d.depo_id 
	where 0=0
	# and d.depo_id = 302
	and dp.depo_code = '$depocode'
	and d.dropping_date = '$cutdate'
	group by d.store_id, dd.product_id 
) as aaa
left join (
	select 
	eod.depo_id 
	, eod.store_id 
	, eod.product_id 
	, SUM(eod.quantity) as qty
	, '$cutdate' as tanggal
	, 'estimate' as trans
	from estimate_order_detail eod 
	where 0=0
	and eod.estimate_order_id in (
		select aa.estimate_order_id from (
			select 
			eo.estimate_order_id 
			, IFNULL((select po.status_id from purchase_order_estimate poe join purchase_order po on po.po_id=poe.purchase_order_id where poe.estimate_order_id=eo.estimate_order_id and po.status_id=1),0) is_gr
			from estimate_order eo 
			where 0=0
			and eo.suggest_order_date = '$cutdate'
			and eo.depo_id in (select dp.depo_id from depo dp where dp.depo_code = '$depocode')
			and eo.status_id = 3
			having is_gr = 1
			) as aa
		)
	and eod.quantity > 0
	group by eod.store_id, eod.product_id , tanggal
	) as bbb on bbb.store_id = aaa.store_id and bbb.product_id = aaa.product_id and bbb.tanggal = aaa.tanggal 
left join (
	select 
	d.depo_id 
	, d.store_id 
	, dd.product_id 
	, sum(dd.qty_drop) as qty
	, d.dropping_date as tanggal
	, 'dropping' as trans
	from dropping d 
	join dropping_detail dd on dd.dropping_id = d.dropping_id 
	join depo dp on dp.depo_id = d.depo_id 
	where 0=0
	# and d.depo_id = 302
	and dp.depo_code = '$depocode'
	and d.dropping_date = '$cutdate'
	group by d.store_id, dd.product_id 
	) as ccc on ccc.store_id = aaa.store_id and ccc.product_id = aaa.product_id and ccc.tanggal = aaa.tanggal 
group by aaa.depo_id, aaa.store_id, aaa.product_id
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# var_dump($exec);

$dataHeader = array();
$dataBody = array();

if(mysqli_num_rows($exec) > 0) {
    
	while($row = mysqli_fetch_array($exec)) {
      
      	# Prepare Product untuk menggunakan product_code, sebagai bantuan untuk sortir nanti
		$customProd = $row['product_code']. "-". $row['short_name'];
      
      	# Masukkan list semua product kedalam variable dataHeader
      	array_push($dataHeader, $customProd);
        
      	$tempDataRow = $row['store_id']. "$". $row['store_name'];
      	# Masukkan store_id kedalam object dataBody, dengan sifat unique/distinct
      	$dataBody += [ $tempDataRow  => array() ];
      	
      	# Setelah toko sdh di insert di object, maka toko ini juga diinsert array (multidimensi)
      	$dataBody[$tempDataRow] += [$customProd => array($row['qty_estimasi'], $row['qty_dropping'], $row['qty_sold'], $row['qty_bs']) ];
      
      }
}
  
sort($dataHeader);
$dataHeaderFiltered = array_unique($dataHeader);

$output = "store_id;store_name;trans_type";
foreach($dataHeaderFiltered as $item){
  	$output .= ";";
  	$output .= substr(strrchr($item, '-'),1);
}
$output .= "\n";


foreach($dataBody as $store => $product){
  	
  	$tempType = array("estimasi", "dropping", "sold", "bs");
  
  	for ($x = 0; $x <= 3; $x++) {
  		$output .= explode('$', $store)[0]. ";";
      	$output .= explode('$', $store)[1]. ";";
      	$output .= $tempType[$x];
      
      	foreach($dataHeaderFiltered as $itemHeader){
          	$output .= ";";
        	if(isset($product[$itemHeader])){
            	 $output .= $product[$itemHeader][$x];
            } else {
            	 $output .= '0';
            }
        }
      	$output .= "\n";
	}
}

$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_estvsdrop.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $output);
# $key, $phone, $filePath, $isGroup

if ($writeResult != false) {
  $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-arya'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "false");
}





