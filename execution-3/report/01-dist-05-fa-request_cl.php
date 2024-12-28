<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-05-fa-request_cl.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
$year = $json[1];
#$year = '2024';
#$depocode = '70002157';
#$cutdate = '2024-05';
#$cutdate = $json[1];

$sql = "select rah.req_aprv_header_id 
, d.depo_code 
, d.depo_name 
, rat.req_aprv_type_name 
, rah.request_number 
, date(rah.request_date) AS request_date
, DATE_FORMAT(rah.request_date, '%H:%i:%s') AS request_time
, rah.request_by 
, wu.username as request_user
, wu.fullname as request_name
, (select raw.where_value from req_aprv_where raw where raw.req_aprv_header_id=rah.req_aprv_header_id) as store_id
, (select s.store_code from req_aprv_where raw join store s on s.store_id= raw.where_value where raw.req_aprv_header_id=rah.req_aprv_header_id) as store_code
, (select s.store_name from req_aprv_where raw join store s on s.store_id= raw.where_value where raw.req_aprv_header_id=rah.req_aprv_header_id) as store_name
, (select rai.req_value_from from req_aprv_item rai where rai.req_aprv_item_id >= rah.req_aprv_header_id and rai.req_aprv_header_id= rah.req_aprv_header_id limit 1) value_before
, (select rai.req_value_to from req_aprv_item rai where rai.req_aprv_item_id >= rah.req_aprv_header_id and req_aprv_header_id= rah.req_aprv_header_id limit 1) value_to
, rah.notes 
, ras.status_name 
from req_aprv_header rah 
join depo d on d.depo_id = rah.depo_id and d.owner_id = 28 and d.is_dummy = 0
left join web_user wu on wu.id_web_user = rah.request_by
left join req_aprv_type rat on rat.req_aprv_type_id = rah.req_aprv_type_id 
left join req_aprv_status ras on ras.status_id = rah.status_id 
where 0=0
and rah.req_aprv_type_id = 4
and rah.request_date BETWEEN '$year-01-01 00:00:01' and '$year-12-31 23:59:59'
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
$sheet->setColOptions('E', ['format' => '@text', 'width' => 15]);

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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_clrequest.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "CL Request", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);

exit();










