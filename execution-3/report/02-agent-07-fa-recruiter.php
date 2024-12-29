<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/02-agent-07-fa-recruiter.php
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
hr.hawker_recruited_id 
, d.depo_name 
, ul.user_id  
, ul.name 
, date(ul.created) as tgl_created
, IFNULL((select max(as2.tanggal_selling) from agent_selling as2 where as2.user_id = ul.user_id),'') as user_last_selling
, d2.depo_name as depo_recruiter
, ul2.user_id as id_recruiter
, ul2.name as nama_recruiter
, date(ul2.created) as tgl_created_recruiter
, IFNULL((select max(as2.tanggal_selling) from agent_selling as2 where as2.user_id = ul2.user_id),'') as recruiter_last_selling
, if( ul2.created < ul.created, 'OK', 'WARNING') as remarks
from hawker_recruited hr
join user_login ul on ul.user_id = hr.user_id 
join user_login ul2 on ul2.user_id = hr.hawker_recruiter_id 
join depo d on d.depo_id = ul.depo_id 
join depo d2 on d2.depo_id = ul2.depo_id 
join recruiter_level rl on rl.recruiter_level_id = hr.level_recruiter_id 
where 0=0
and d.is_dummy = 0
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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_recruiter.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "Hawker Recruiter", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", $config['domain2']. "execution-3/report/". $filename);
}

# Delete file in directory
unlink($filename);

exit();










