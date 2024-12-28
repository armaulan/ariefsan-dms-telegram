<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/02-agent-05-fa-umutuk.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
$cutdate = $json[1];

#$depocode = '70002157';
#$cutdate = '2023-05';

$sql = "select distinct
p.plant_name
, dp.depo_code 
, dp.depo_name
, pk.salesman_id 
, ul2.name
, ul2.ktp_number
, ul2.bank_account_name
, pk.penerimaan_date as transaction_date
, pk.penerimaan_kasir_number 
, zz.um
, zz.ut
, xx.uk
, (select d.owner_id from user_login ul join depo d on d.depo_id=ul.depo_id where ul.user_id=pk.salesman_id and d.owner_id=28) as owner_id
from penerimaan_kasir pk 
left join (
	select DISTINCT 
	aa.salesman_id
	, aa.penerimaan_date
	, aa.penerimaan_kasir_number
	, aa.um
	, aa.ut
	from (
	select 
	pk.penerimaan_kasir_id 
	, pk.salesman_id
	, pk.penerimaan_kasir_number 
	, pk.penerimaan_date 
	, IFNULL((select arpl.cost_value  from agent_report_psak_log arpl where arpl.setoran_number=pk.penerimaan_kasir_number and arpl.cost_code ='code6' and arpl.cost_value >0),0) as um
	, IFNULL((select arpl.cost_value from agent_report_psak_log arpl where arpl.setoran_number=pk.penerimaan_kasir_number and arpl.cost_code ='code7' and arpl.cost_value >0),0) as ut
	from penerimaan_kasir pk 
	where 0=0
	-- and pk.penerimaan_date >= '2024-03-01'
	#and pk.penerimaan_kasir_number = '335000664324040216362777'
	and pk.penerimaan_date like '2024-04%'
	) as aa
	) zz on zz.penerimaan_kasir_number = pk.penerimaan_kasir_number and zz.salesman_id = pk.salesman_id and zz.penerimaan_date = pk.penerimaan_date
left join (
	select 
	DISTINCT 
	aa.salesman_id
	, aa.penerimaan_date
	, aa.penerimaan_kasir_number
	, aa.uk
	from (
	select
	pk.penerimaan_kasir_id
	, pk.salesman_id
	, pk.penerimaan_kasir_number
	, pk.penerimaan_date
	, IFNULL((select urdl.value_uk from uk_report_detail_log urdl join uk_master_kpi umk on umk.kpi_id = urdl.kpi_id where urdl.setoran_number=pk.penerimaan_kasir_number and urdl.value_uk>0 and umk.status_id=1),0) as uk
	from penerimaan_kasir pk
	where 0=0
	-- and pk.penerimaan_date >= '2024-03-01'
	#and pk.penerimaan_kasir_number = '335000664324040216362777'
	and pk.penerimaan_date like '2024-04%'
	) as aa
	) as xx on xx.penerimaan_kasir_number = pk.penerimaan_kasir_number and  xx.salesman_id = pk.salesman_id and xx.penerimaan_date = pk.penerimaan_date 
left join user_login ul2 on ul2.user_id = pk.salesman_id 
left join depo dp on dp.depo_id = ul2.depo_id 
left join plant p on p.plant_id = dp.plant_id 
where 0=0
and pk.penerimaan_date >= '2024-04%'
#and pk.salesman_id = 559735490
#and pk.penerimaan_kasir_number = '335000664324040216362777'
having owner_id = 28
and um + ut + uk > 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$dataMessage = "plant_name;depo_code;depo_name;salesman_id;name;ktp_number;bank_account_name;transaction_date;um;ut;uk;owner_id\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dataMessage .= $row['plant_name']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['salesman_id']. ";";
      	$dataMessage .= $row['name']. ";'";
      	$dataMessage .= $row['ktp_number']. ";";
      	$dataMessage .= $row['bank_account_name']. ";";
      	$dataMessage .= $row['transaction_date']. ";";
      	$dataMessage .= $row['um']. ";";
      	$dataMessage .= $row['ut']. ";";
      	$dataMessage .= $row['uk']. ";";
      	#$dataMessage .= $row['top_id']. ";";
      	#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['store_name']). ";";
		#$dataMessage .= str_replace(array("\r", "\n", ";", "\t"), "" , $row['owner_name']). ";";
		#$dataMessage .= "`". str_replace(array("\r", "\n", ";", "\t"), "" , $row['phone']). ";";
      	$dataMessage .= $row['owner_id']. "\n";
      
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
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_umutuk.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Testing", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
}





