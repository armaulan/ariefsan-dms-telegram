<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/02-agent-03-fa-monthlycommision.php
exit();
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$cutdate = $json[2];
#$depocode = '70002157';
#$cutdate = '2023-05-05';
#$period = '2023-01';
$period = $json[1];

$sql = "select 
p.plant_name 
, d.depo_code 
, d.depo_name 
, as2.user_id
, ul.name
, as2.tanggal_selling  
, sum(as2.total_komisi_monthly) as amt_monthly
, (select d.owner_id from depo d where d.depo_id=as2.depo_id) as owner_id 
from agent_selling as2 
left join depo d on d.depo_id = as2.depo_id
left join plant p on p.plant_id = d.plant_id
left join user_login ul on ul.user_id = as2.user_id
where 0=0
and as2.tanggal_selling >= '$period-01'
and as2.tanggal_selling <= '$period-31'
and as2.tanggal_selling like '$period%'
and total_komisi_monthly > 0
and as2.status_id <> 5
group by user_id, tanggal_selling
having owner_id = 28
";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();

# Put a header
$sheet->writeRow([ 'plant_name', 'depo_code', 'depo_name', 'user_id', 'name', 'tanggal_selling', 'amt_monthly' ]);

# Data iteration and write into excel
if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
	    
	    $sheet->writeRow([ $row['plant_name'], 
	        $row['depo_code'], 
	        $row['depo_name'], 
	        $row['user_id'], 
	        $row['name'], 
	        $row['tanggal_selling'], 
	        $row['amt_monthly'] 
	        ]);
	    
	    /*
      	$dataMessage .= $row['plant_name']. ";";
      	$dataMessage .= $row['depo_code']. ";";
      	$dataMessage .= $row['depo_name']. ";";
      	$dataMessage .= $row['user_id']. ";";
      	$dataMessage .= $row['name']. ";";
      	$dataMessage .= $row['tanggal_selling']. ";";
      	$dataMessage .= $row['amt_monthly']. "\n";
      	*/
      	
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

# Preparing excel file directory
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_data.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Preparing name for zip file
$zipname = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "komisi_bulanan.zip";

# Instantiate new zip object
$zip = new ZipArchive;

# Process zip
if ($zip->open('download/' . $zipname, ZipArchive::CREATE) === TRUE)
{
    # Adding a password
    $zip->setPassword('@Roti2024');
    
    # Adding excel file into zip
    $zip->addFile($filename, $zipname. '.xlsx');
    
    # Set encryption
    $zip->setEncryptionName($zipname. '.xlsx', ZipArchive::EM_AES_256);

    # Close the process    
    $zip->close();
}

# Send file throught whatsapp
if (0 == 0) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Agent Monthy Commission", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);
unlink('download/' . $zipname);

exit();

/*
# print($dataMessage);
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_monthly_amount.txt";
# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);
$text_content = file_get_contents("download/". $filename);
$compressed_content = gzencode($text_content, 6);
file_put_contents("download/". $filename. ".gz", $compressed_content);
*/









