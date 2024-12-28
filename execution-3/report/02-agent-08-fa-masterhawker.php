<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/02-agent-08-fa-masterhawker.php
require '../../00-01-conn-agent.php';
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
, ul.user_id 
, ul.username 
, ul.name 
, ur.role_name 
, ul.ktp_number 
, ul.bank_account_name
, ul.bank_name 
, date(ul.created) as created_date
, ul.is_active
, IFNULL((select max(as2.tanggal_selling) from agent_selling as2 where as2.user_id=ul.user_id),'') last_selling
, ul.address 
from user_login ul 
join depo d on d.depo_id = ul.depo_id
join plant p on p.plant_id = d.plant_id 
left join user_role ur on ur.role_id = ul.role_id 
where 0=0
and d.owner_id = 28
and ((ul.is_active = 1) or (ul.is_active=0 and ul.modified >= DATE_SUB(CURRENT_DATE(), interval 10 day))) 
;
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
$sheet->setColOptions('H', ['format' => '@text', 'width' => 12]);
$sheet->setColOptions('I', ['format' => '@text', 'width' => 12]);

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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_data-user-agent.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Master User Agent", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);

exit();










