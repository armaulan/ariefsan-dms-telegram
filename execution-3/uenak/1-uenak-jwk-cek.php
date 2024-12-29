<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/uenak/1-uenak-jwk-cek.php
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=webhook-00-02wa.php");
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

    /*
    $date = new DateTime('2024-01-01');
    $tanggal = strlow($date->format('l'));
    echo $tanggal; */

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$depocode = $json[1];
$cutdate = $json[2];
$date = new DateTime($cutdate);
$tanggal = strtolower($date->format('l'));
$listDepo = ["770", "754", "755"];


if(!in_array($depocode, $listDepo)) {
    exit();
}

#$depocode = '722';
#$tanggal = 'wednesday';
#$cutdate = '2024-09-18';

$sql = "select 
aa.user_id
, aa.name
, aa.store_code
, aa.store_name
, aa.dropping_number
, aa.dropping_date
, aa.total_cbp
, aa.total_rbp
, IFNULL(aa.qty_estimate,0) as qty_estimate
from (
select 
ul.user_id 
, ul.name 
, s.store_code 
, s.store_name 
, jp.jp_type 
, jp.first_date 
, jp.frequency 
, if(jp.jp_type='f', DATEDIFF('$cutdate', jp.first_date) % jp.frequency, 2 ) as check_freq
, jp.$tanggal
, IFNULL(d.dropping_number,'') as dropping_number
, IFNULL(d.dropping_date,'') as  dropping_date
, IFNULL(d.total_cbp,0) as  total_cbp
, IFNULL(d.total_rbp,0) as  total_rbp
, (select sum(eod.quantity) from estimate_order eo join estimate_order_detail eod on eod.estimate_order_id=eo.estimate_order_id where eo.suggest_order_date = '$cutdate' and eod.store_id=s.store_id and eo.status_id=3) as qty_estimate 
from store s 
left join journey_plan jp on jp.store_id = s.store_id 
left join user_login ul on ul.user_id = jp.user_id 
left join dropping d on d.store_id = s.store_id and d.status_id in (1,2)
where 0=0
and s.depo_id = '$depocode'
and s.is_active = 1
having jp.$tanggal = 1
or check_freq = 0
) as aa
";

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=". $sql);

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
$sheet->setColOptions('E', ['format' => '@text', 'width' => 16]);

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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_jwk.xlsx";


# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Multi Group Store", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  "6282180603613", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $filename,  "false");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/uenak/download/". $filename);
  #$config["whacentersenddoc"]($config['key-whacenter-1'],  $sender, "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/uenak/". $filename);
  $config["whacentersenddoc"]($config['key-whacenter-1'],  $sender, "", $config['domain2']. "execution-3/uenak/". $filename);
}

# Delete file in directory
# unlink($filename);

exit();


