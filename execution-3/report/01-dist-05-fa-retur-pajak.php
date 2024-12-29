<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-05-fa-retur-pajak.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
#$sender = "6282180603613";
$depocode = $json[1];
#$depocode = '70002157';
#$cutdate = '2024-05';
#$cutdate = $json[1];

$sql = "select 
 aa.depo_code 
, aa.depo_name 
, aa.store_code 
, aa.store_name
, aa.cn_number 
, aa.cn_input 
, aa.cn_date 
, aa.is_approval_cn 
, aa.product_code 
, aa.product_name
, aa.qty_cn 
, aa.qty_applied
, aa.faktur_applied
from (
select d.depo_code 
, d.depo_name 
, s.store_code 
, s.store_name 
, sch.cn_number 
, sch.cn_input 
, sch.cn_date 
, sch.is_approval_cn 
, p.product_code 
, p.product_name
, scd.qty_cn 
, IFNULL((select sum(nrpdc.qty_admin_input) from nota_retur_pajak_detail_cn nrpdc where nrpdc.cn_id=sch.cn_id and nrpdc.product_id=p.product_id),0) as qty_applied
, IFNULL((select GROUP_CONCAT(CONCAT(nrp.faktur_pajak_number, ' (', nrpdc2.qty_admin_input, ' Pcs)') separator ', ')  from nota_retur_pajak_detail_cn nrpdc2 left join nota_retur_pajak nrp on nrp.nota_retur_pajak_id = nrpdc2.nota_retur_pajak_id where nrpdc2.cn_id = sch.cn_id and nrpdc2.product_id=scd.product_id), '') as faktur_applied
from stock_cn_header sch
join user_login ul on ul.user_id = sch.salesman_id 
join depo d on d.depo_id = ul.depo_id
join stock_cn_detail scd on scd.cn_id = sch.cn_id 
join product p on p.product_id = scd.product_id 
join store s on s.store_id = sch.store_id 
where 0=0
# and d.depo_id = 409
and d.depo_code = '$depocode'
and sch.is_approval_cn = 1
-- having qty_applied > qty_cn
) as aa 
where 0=0";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();
$sheet->setColWidth(['A', 'B', 'C', 'D', 'E'], 'auto');
#$sheet->setColOptions('G', ['format' => '@text', 'width' => 12]);

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
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_cn-retur.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Hawker Active", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $sender, "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'],  $sender, "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  $config["whacentersenddoc"]($config['key-whacenter-1'],  $sender, "",  $config['domain2']. "execution-3/report/".  $filename);
}

# Delete file in directory
unlink($filename);

exit();

