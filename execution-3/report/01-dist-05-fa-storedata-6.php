<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-05-fa-storedata-6.php
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
p.plant_name 
, d.depo_code 
, d.depo_name 
, s.store_code
, s.store_name 
, st.store_type_name 
, sc.classification_name 
, s.max_faktur 
, s.credit_limit 
, date(s.last_visited) last_visit 
, (select count(d2.dropping_id) from dropping d2 left join dropping_group_brand dgb on dgb.dropping_id = d2.dropping_id where d2.store_id=s.store_id and d2.status_id in (1,2) and dgb.group_brand_id = 1) as outs_doc_sr
, (select count(d2.dropping_id) from dropping d2 left join dropping_group_brand dgb on dgb.dropping_id = d2.dropping_id where d2.store_id=s.store_id and d2.status_id in (1,2) and dgb.group_brand_id = 2) as outs_doc_milk
, (select count(gbps.group_brand_id) from group_brand_per_store gbps where gbps.store_id=s.store_id and gbps.is_active=1 and gbps.group_brand_id in (1,2)) as count_group_brand
from store s 
left join depo d on d.depo_id = s.depo_id 
left join plant p on p.plant_id = d.plant_id 
left join store_type st on st.store_type_id = s.store_type_id 
left join store_classification sc on sc.classification_id = st.classification_id 
where 0=0
and s.is_active = 1
and d.owner_id = 28
and d.is_dummy = 0
having count_group_brand > 1
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
# $sheet->setColOptions('G', ['format' => '@text', 'width' => 12]);

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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_multigroup.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Multi Group Store", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);

exit();










