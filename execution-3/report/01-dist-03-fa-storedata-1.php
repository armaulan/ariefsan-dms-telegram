<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-03-fa-storedata-1.php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-03-fa-storedata-1.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

#$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
#$depocode = '70002157';
#$cutdate = '2024-05';
#$cutdate = $json[1];

$sql = "select 
(select p.plant_name from plant p join depo d on d.plant_id=p.plant_id where d.depo_id=aa.depo_id) as plant_name 
, d.depo_id 
, d.depo_code 
, d.depo_name 
, aa.store_id
, aa.store_code
, aa.store_name
, aa.owner_name
, aa.phone
, (select st.store_type_name from store_type st where st.store_type_id = aa.store_type_id) as store_type
, IFNULL((select max(d2.dropping_date) from dropping d2 where d2.store_id=aa.store_id),'') as last_drop
, date(aa.created) as created
, aa.owner_no_ktp
, (select UPPER(ss.sublocation_name) from store_sublocation1 ss join store s2 on s2.sublocation1_id=ss.sublocation_id where s2.store_id=aa.store_id) as location
, aa.max_faktur
, aa.top_id
, (select if(jp.monday < 0, 0, jp.monday) + if(jp.tuesday < 0, 0, jp.tuesday) + if(jp.wednesday < 0, 0, jp.wednesday) + if(jp.thursday < 0, 0, jp.thursday)  + if(jp.friday < 0, 0, jp.friday) + if(jp.saturday < 0, 0, jp.saturday) + if(jp.sunday < 0, 0, jp.sunday) from journey_plan jp where jp.store_id = aa.store_id order by jp.is_active desc, jp.first_date desc limit 1) as jwk
, if(aa.store_sales_type_id = 2, 'NON CONSIGMENT', 'CONSIGMENT') as trans_type
, aa.status_pkp
, aa.npwp
, aa.npwp_name
, aa.address
from (
	SELECT 
	s.store_id 
	, s.store_code
	, s.store_name
	, s.depo_id
	, (select d.owner_id from depo d where d.depo_id=s.depo_id) as owner_id
	, (select d.is_dummy from depo d where d.depo_id=s.depo_id) as is_dummy
	, s.owner_name 
	, s.phone
	, s.store_type_id
	, s.created
	, s.owner_no_ktp
	, s.max_faktur
	, s.top_id
	, s.store_sales_type_id 
	, IF(s.is_pkp=0, 'NON PKP', 'PKP') status_pkp
	, s.npwp
	, (select n.npwp_name from npwp n where n.depo_id = s.depo_id and n.npwp_no = s.npwp) as npwp_name
	, s.address
	from store s 
	where 0=0
	and s.is_active = 1
	having owner_id = 28 and is_dummy = 0
	) as aa
join depo d on d.depo_id = aa.depo_id and d.is_active = 1
where 0=0
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
$sheet->setColOptions('F', ['format' => '@text', 'width' => 12]);
$sheet->setColOptions('M', ['format' => '@text', 'width' => 12]);
$sheet->setColOptions('T', ['format' => '@text', 'width' => 12]);

# Data Column Header Name iteration and write into excel
$columnNames = [];
while ($field = mysqli_fetch_field($exec)) {
    $columnNames[] = $field->name;
    }
$sheet->writeRow($columnNames);


# Data iteration and write into excel
if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_assoc($exec)) {

        $tempData = [];        
        foreach($row as $key => $value) {
            $tempData[] = $value;
        }
        
        $sheet->writeRow($tempData);
        
      }
}

# Preparing excel file directory
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_store-inn.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Store INN", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/".  $filename);
}

# Delete file in directory
unlink($filename);

exit();










