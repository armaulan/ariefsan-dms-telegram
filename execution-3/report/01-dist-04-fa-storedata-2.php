<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-04-fa-storedata-2.php
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
CONCAT(DATE_SUB(CURRENT_DATE(), interval 30 day), ' - ', CURRENT_DATE()) as periode 
, d.depo_code 
, d.depo_name 
, ul.name as salesman
, aa.store_id
, s.store_name 
, st.store_type_name 
, CONCAT(min(aa.invoice_date), ' to ', max(aa.invoice_date)) trans_range
, count(aa.tagihan_id) as count_trans
, sum(aa.amount_good) as sum_trans
, IFNULL(bb.total_tagihan,0) as real_count_trans
, IFNULL(bb.total_net,0) as real_net_trans
from (
select
t.store_id 
, t.salesman_id
, t.depo_id
, t.invoice_date
, t.tagihan_id 
, t.total_dropping 
, t.total_tagihan 
, (select sum(td.amount_good) from tagihan_detail td where td.tagihan_id=t.tagihan_id) as amount_good
from tagihan t  
where 0=0
and t.invoice_date >= DATE_SUB(CURRENT_DATE(), interval 30 day)
and t.total_net = 0
having amount_good = total_dropping
) as aa 
join depo d on d.depo_id = aa.depo_id
join store s on s.store_id = aa.store_id
join store_type st on st.store_type_id = s.store_type_id 
join user_login ul on ul.user_id = aa.salesman_id
left join (
	select 
	t.store_id 
	, sum(t.total_net) as total_net
	, count(t.tagihan_id) as total_tagihan
	from tagihan t 
	where 0=0
	and t.invoice_date >= DATE_SUB(CURRENT_DATE(), interval 30 day)
	and t.total_dropping <> t.total_retur 
	group by t.store_id 
) as bb on aa.store_id = bb.store_id
where 0=0
and d.owner_id = 28
group by aa.store_id
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "periode;depo_code;depo_name;salesman;store_id;store_name;store_type_name;trans_range;count_trans;sum_trans;real_count_trans;real_net_trans\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['periode']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['salesman']. ";";
      	$dataMessage .= $row['store_id']. ";";
      	$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store_name']). ";";
      	$dataMessage .= $row['store_type_name']. ";";
      	$dataMessage .= $row['trans_range']. ";";
      	$dataMessage .= $row['count_trans']. ";";
      	$dataMessage .= $row['sum_trans']. ";";
      	$dataMessage .= $row['real_count_trans']. ";";
      	$dataMessage .= $row['real_net_trans']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_suspect_store.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Screening Store Full Retur Baik", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $filename);
}





