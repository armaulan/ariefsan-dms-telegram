<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-05-fa-storedata-5.php
exit();
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$month = $json[1];
#$sender = $_POST['let1'];
#$month = '2024-01';

$sql = "
select 
p.plant_name 
, d.depo_code 
, d.depo_name 
, IFNULL(aa.created_date, '') as created_date
, IFNULL(aa.count_noo, 0) as count_noo
, IFNULL(bb.count_appr, 0) as count_appr
from depo d 
left join plant p on p.plant_id = d.plant_id 
left join (
	select s.depo_id 
	, d.depo_code
	, date(s.create_date) as created_date 
	, count(s.store_id) as count_noo
	from store_noo_new snn 
	left join store s on s.store_id = snn.store_id 
	left join depo d on d.depo_id = s.depo_id 
	where 0=0
	and snn.created_date like '$month%'
	and snn.last_status_by_rsm = 'a' and snn.last_status_by_routeplan = 'a' and snn.last_status_by_faro = 'a'
	group by s.depo_id , date(created_date)
	) as aa on aa.depo_code = d.depo_code 
left join (
	select s.depo_id 
	, d.depo_code 
	, date(s.create_date) as created_date 
	, count(s.store_id) as count_appr
	from store_noo_new snn 
	left join store s on s.store_id = snn.store_id 
	left join depo d on d.depo_id = s.depo_id 
	where 0=0
	and snn.created_date like '$month%' 
	and snn.last_status_by_rsm = 'a' and snn.last_status_by_routeplan = 'a' and snn.last_status_by_faro = 'a'
	group by s.depo_id , date(created_date) 
) as bb on bb.depo_code = d.depo_code and bb.depo_code = aa.depo_code and aa.created_date = bb.created_date
where 0=0
and d.owner_id = 28
and d.is_active = 1
and d.depo_id in (select distinct po.depo_id from purchase_order po where po.po_date>= DATE_SUB(CURRENT_DATE(), interval 10 day))
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();

# Put a header
$sheet->writeRow([ 'plant_name', 'depo_code', 'depo_name', 'created_date', 'count_noo', 'count_appr' ]);

# Data iteration and write into excel
if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
	    
	    $sheet->writeRow([ $row['plant_name'], 
	        $row['depo_code'], 
	        $row['depo_name'], 
	        $row['created_date'], 
	        $row['count_noo'], 
	        $row['count_appr']
	        ]);
	    
      }
}

# Preparing excel file directory
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_noo_inndi.xlsx";

# Save file excel into file directory
$excel->save($filename);

$config["whatsappSendMessage"]($config['key-wa-bas'],  "NOO Inndi", $config['id-wa-group-fa'], "true");
#$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/". $filename);

# Delete file in directory
unlink($filename);





