<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/uenak/3-uenak-settlement-1.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
#$sender = "6282180603613";
$depoID = $json[1];
$date = $json[2];
#$depoID = 770;
#$date = '2024-12-17';
$listDepo = [770, 754, 755];

if(!in_array($depoID, $listDepo)) {
    exit();
}

$sql = "select 
    t.depo_id 
    , d.depo_name
    , format(sum(t.total_tagihan),0) as total_tagihan
    , count(distinct t.store_id) as total_store
    , count(t.invoice_number) as total_doc
    , IFNULL((select format(sum(t2.total_tagihan),0) from tagihan t2 where t2.depo_id=t.depo_id and t2.status_id in (1,2,3,2) and t.invoice_date < '$date'),0) as outs_tagihan
    , sum(t.total_dropping) as amount_drop
    , sum((select sum(td.amount_bs) from tagihan_detail td where td.tagihan_id=t.tagihan_id)) as amount_bs
    from tagihan t 
    join depo d on d.depo_id = t.depo_id
    where 0=0
    and t.invoice_date = '$date'
    and t.depo_id = $depoID
    ";
    
$sql2 = "
    select 
    LOWER(ul.name) as name
    , FORMAT(sum(t.total_tagihan),0) as total_rbp
    from tagihan t 
    join user_login ul on ul.user_id = t.salesman_id 
    where 0=0
    and t.depo_id = $depoID
    and t.invoice_date = '$date'
    group by t.salesman_id
    ";
    
$sql3 = "
    select 
    lower(suggest_name) as suggest_name
    , sum(qty) as qty_awal
    , sum(stock_good) as qty_akhir
    , sum(qty_bs) as bs
    , sum(qty_sold) as sold
    from (
    	select 
    	isn.depo_id 
    	, isn.salesman_id 
    	, isn.product_id
    	, p.suggest_name
    	, IFNULL((select ispd.stock_good from inventory_salesman_per_day ispd where ispd.depo_id=isn.depo_id and ispd.salesman_id=isn.salesman_id and ispd.product_id=isn.product_id and ispd.tanggal_inventory < '$date' order by ispd.tanggal_inventory desc limit 1 ),0) as qty
    	, IFNULL((select sum(td.qty_drop-td.qty_return_bs-td.qty_return_good) from tagihan t join tagihan_detail td on td.tagihan_id = t.tagihan_id where t.invoice_date = '$date' and t.depo_id = isn.depo_id and t.salesman_id = isn.salesman_id and td.product_id=isn.product_id),0) as qty_sold
    	, IFNULL((select sum(td.qty_return_bs) from tagihan t join tagihan_detail td on td.tagihan_id = t.tagihan_id where t.invoice_date = '$date' and t.depo_id = isn.depo_id and t.salesman_id = isn.salesman_id and td.product_id=isn.product_id),0) as qty_bs
    	, isn.stock_good
    	, isn.stock_bs
    	from inventory_salesman_new isn 
    	left join product p on p.product_id = isn.product_id
    	where 0=0
    	and isn.depo_id = $depoID
    	and p.suggest_name not like '%bns%'
    ) as aa
    group by suggest_name
";

$sql4 = "
    select 
    ul.user_id 
    , ul.name
    , ul.username 
    , (select insert(left(right(d.dropping_number, 8),4), 3, 0, ':') from dropping d where d.dropping_date = '$date' and d.salesman_id=ul.user_id limit 1) as first_ota
    , (select insert(left(right(d.dropping_number, 8),4), 3, 0, ':') from dropping d where d.dropping_date = '$date' and d.salesman_id=ul.user_id order by d.dropping_id desc limit 1) as last_ota
    , (select count(distinct eod.store_id) from estimate_order eo join estimate_order_detail eod on eod.estimate_order_id=eo.estimate_order_id where eo.salesman_id=ul.user_id and eo.suggest_order_date='$date' and eo.status_id=3 and eod.quantity >0) as estimated_store
    , (select count(distinct d2.store_id) from dropping d2 where d2.dropping_date='$date' and d2.salesman_id=ul.user_id) as dropped_store
    , (select sum(isn.stock_good) from inventory_salesman_new isn join product p on p.product_id = isn.product_id where isn.salesman_id=ul.user_id and p.suggest_name not like '%bns%') as stock_good
    , (select aul.action_type from android_user_log aul where aul.username = ul.username order by aul.android_user_id desc limit 1) as session
    from user_login ul 
    where 0=0
    and ul.depo_id = $depoID
    and ul.salesman_type_id = 2
    and ul.is_active = 1
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$exec2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
$exec3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));
$exec4 = mysqli_query($conn, $sql4) or die(mysqli_error($conn));

$dataMessage = "";


if(mysqli_num_rows($exec) > 0) {
    $dataMessage .= "*Draft Settle $date*\n\n";
    $dataMessage .= "*TAGIHAN*\n";
    
	while($row= mysqli_fetch_array($exec)) {
	    $dataMessage .= "Depo: ". $row['depo_name']. "\n\n";
      	$dataMessage .= "Total RBP: ". $row['total_tagihan']. "\n";
      	$dataMessage .= "Total Store: ". $row['total_store']. "\n";
      	$dataMessage .= "Total Doc: ". $row['total_doc']. "\n";
      	$dataMessage .= "Outstanding Prev: ". $row['outs_tagihan']. "\n";
      	$dataMessage .= "CBP Tagihan: ". number_format(round($row['amount_drop'],0)). "\n";
      	$dataMessage .= "BS Tagihan: ". number_format(round($row['amount_bs'],0)). "\n";
      	$percentage =  round($row['amount_bs'] / $row['amount_drop'] * 100, 0);
      	$dataMessage .= "BS %: ". $percentage. "\n\n";
      }
} 


if(mysqli_num_rows($exec2) > 0) {
    $dataMessage .= "*TAGIHAN DETAIL*\n";
    
	while($row= mysqli_fetch_array($exec2)) {
      	$dataMessage .= $row['name']. ": ". $row['total_rbp']. "\n";
      }
} 


if(mysqli_num_rows($exec3) > 0) {
    $dataMessage .= "\n*STOCK*\n";
    #$dataMessage .= "Awal | Akhir | Sold | BS \n";
    
    $awal = 0;
    $akhir = 0;
    $bs = 0;
    $sold = 0;
    
	while($row= mysqli_fetch_array($exec3)) {
      	#$dataMessage .= $row['suggest_name']. " | ". $row['qty_awal']. ", ". $row['qty_akhir']. ", ". $row['sold']. ", ". $row['bs'].  "\n";
      	$awal += $row['qty_awal'];
      	$akhir += $row['qty_akhir'];
      	$sold += $row['sold'];
      	$bs += $row['bs'];
      }
      
    $dataMessage .= "Awal ".  $awal. ", Akhir ". $akhir. ", Sold ". $sold. ", BS ". $bs. "\n";
} 


if(mysqli_num_rows($exec4) > 0) {
    $dataMessage .= "\n*JWK*\n";
    $dataMessage .= "F.OTA | L.OTA\nEstimated Store  | Dropped Store | Stock Good \n\n";
    
	while($row= mysqli_fetch_array($exec4)) {
      	$dataMessage .= $row['name']. " *". $row['session']. "* \n". $row['first_ota']. " to ". $row['last_ota']. "\nEstToko ". $row['estimated_store']. "  DropToko ". $row['dropped_store']. "  SisaStock ". $row['stock_good'].  " Pcs\n\n";
      }
      
} 


if ($dataMessage != ""){
    echo $dataMessage;
    $config["whatsappSendMessage"]($config['key-wa-bas'], $dataMessage , $sender, "false");
    file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=$dataMessage");
} else {
    echo "Failed";
}
  
# print($dataMessage);
# $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
# $writeResult = file_put_contents("download/". $filename, $dataMessage);

# if ($writeResult != false) {
  # $config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  # $config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
# }





