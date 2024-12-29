<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-07-estimasi-inn.php
exit();
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$sql = "
SELECT
aaa.plant_name
, aaa.depo_code
, aaa.depo_name
, aaa.salesman_id 
, aaa.store_id
, aaa.store_code
, aaa.store_name
, aaa.store_type
, aaa.store_classification
, aaa.main_store
, aaa.status_name
, aaa.product_code
, aaa.product_name
, aaa.short_name
, aaa.quantity
, aaa.cbp
, aaa.discount
, aaa.cbp - (aaa.discount/100 * aaa.cbp) as rbp
, aaa.quantity * (aaa.cbp - (aaa.discount/100 * aaa.cbp)) as total_rbp
, format(aaa.quantity * (aaa.cbp - (aaa.discount/100 * aaa.cbp)) / 1.11,0) as rbp_exclude
, aaa.suggest_order_date
FROM (
SELECT 
# aa.estimate_order_detail_id 
#, aa.estimate_order_id 
# , aa.depo_id
(select p.plant_name from plant p join depo d on d.plant_id=p.plant_id where d.depo_id=aa.depo_id) as plant_name
, (select d.depo_code from depo d where d.depo_id=aa.depo_id) as depo_code
, (select d.depo_name from depo d where d.depo_id=aa.depo_id) as depo_name
# , aa.owner_id
# , aa.plant_id
, aa.salesman_id 
, aa.store_id
, (select s.store_code from store s where s.store_id=aa.store_id) as store_code
, (select s.store_name from store s where s.store_id=aa.store_id) as store_name
, (select st.store_type_name from store_type st join store s on s.store_type_id=st.store_type_id where s.store_id=aa.store_id) as store_type
, (select sc.classification_name from store_classification sc join store s on s.classification_id=sc.classification_id where s.store_id=aa.store_id) as store_classification
, (select sm.mainstore_name from store_mainstore sm join store_classification sc2 on sc2.mainstore_id = sm.mainstore_id join store s2 on s2.classification_id = sc2.classification_id where s2.store_id=aa.store_id) as main_store
# , aa.product_id 
, (select p.product_code from product p where p.product_id= aa.product_id) as product_code
, (select p.product_name from product p where p.product_id= aa.product_id) as product_name
, (select p.short_name from product p where p.product_id= aa.product_id) as short_name
, aa.quantity
, (select pd.cbp from product_depo pd where pd.depo_id=aa.depo_id and pd.product_id=aa.product_id) as cbp
, (select ds.discount_value from discount_store ds where ds.depo_id=aa.depo_id and ds.store_id=aa.store_id and ds.product_id=aa.product_id) as discount
, aa.suggest_order_date
, aa.status_name
from (
SELECT 
eod.estimate_order_detail_id 
, eo.estimate_order_id
, eo.suggest_order_date
, eo.depo_id
, (select d.owner_id from depo d where d.depo_id= eo.depo_id) as owner_id
, (select d.plant_id from depo d where d.depo_id= eo.depo_id) as plant_id
, eo.salesman_id 
, eod.store_id 
, eod.product_id 
, eod.quantity
, (select eos.status_name from estimate_order_status eos where eos.status_id = eo.status_id) as status_name
from estimate_order_detail eod
join estimate_order eo on eo.estimate_order_id = eod.estimate_order_id  
where 0=0
-- and eo.suggest_order_date = DATE_add(CURRENT_DATE(), interval 2 day)
and eo.suggest_order_date between CURRENT_DATE() and DATE_ADD(CURRENT_DATE(),interval 6 day)  
and eo.status_id in (2,3)
and eod.quantity > 0
having owner_id = 28
) as aa
) as aaa;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
#$dataMessage = "PLANT;DEPO CODE;DEPO NAME;SALESMAN;STORE ID;STORE CODE;STORE NAME;STORE TYPE;STORE CLASS;MAIN STORE;STATUS NAME;PRODUCT CODE;PRODUCT NAME;SHORT;QTY;CBP;DISCOUNT;RBP;TOTAL RBP;RBP_EXCLUDE;ORDER DATE\r\n";

$excel = Excel::create(['DATA']);
$sheet = $excel->sheet();
#$sheet->writeRow([ 'PLANT', 'DEPO CODE', 'DEPO NAME', 'SALESMAN', 'STORE ID', 'STORE CODE', 'STORE NAME', 'STORE TYPE', 'STORE CLASS', 'MAIN STORE', 'STATUS NAME', 'PRODUCT CODE', 'PRODUCT NAME', 'SHORT', 'QTY', 'DISCOUNT', 'CBP', 'RBP', 'TOTAL RBP', 'RBP_EXCLUDE', 'ORDER DATE' ]);
$header = [
            'PLANT' => '@string', 
            'DEPO CODE' => '@string', 
            'DEPO NAME' => '@string', 
            'SALESMAN' => '@string', 
            'STORE ID' => '@string', 
            'STORE CODE' => '@string', 
            'STORE NAME' => '@string', 
            'STORE TYPE' => '@string', 
            'STORE CLASS' => '@string', 
            'MAIN STORE' => '@string', 
            'STATUS NAME' => '@string', 
            'PRODUCT CODE' => '@string', 
            'PRODUCT NAME' => '@string', 
            'SHORT' => '@string', 
            'QTY' => '@integer', 
            'CBP' => '#,##0.00', 
            'DISCOUNT' => '#,##0.00',
            'RBP' => '#,##0.00', 
            'TOTAL RBP' => '#,##0.00',
            'RBP_EXCLUDE' => '#,##0.00',
            'ORDER DATE' => '@string',
            ];
$sheet->writeHeader($header);

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
	    $sheet->writeRow([ $row['plant_name'], 
	        $row['depo_code'], 
	        $row['depo_name'], 
	        $row['salesman_id'], 
	        $row['store_id'], 
	        $row['store_code'], 
	        $row['store_name'], 
	        $row['store_type'],
	        $row['store_classification'],
	        $row['main_store'],
	        $row['status_name'],
	        $row['product_code'],
	        $row['product_name'],
	        $row['short_name'],
	        $row['quantity'],
	        $row['cbp'],
	        $row['discount'],
	        $row['rbp'],
	        $row['total_rbp'],
	        $row['rbp_exclude'],
	        $row['suggest_order_date'] 
	        ]);
	   
    	    /*
            $dataMessage .= $row['plant_name']. ";";
            $dataMessage .= $row['depo_code']. ";";	
            $dataMessage .= $row['depo_name']. ";";
            $dataMessage .= $row['salesman_id']. ";";
            $dataMessage .= $row['store_id']. ";";
            $dataMessage .= $row['store_code']. ";";
            $dataMessage .= $row['store_name']. ";";
            $dataMessage .= $row['store_type']. ";";
            $dataMessage .= $row['store_classification']. ";";
            $dataMessage .= $row['main_store']. ";";
            $dataMessage .= $row['status_name']. ";";
            $dataMessage .= $row['product_code']. ";";
            $dataMessage .= $row['product_name']. ";";
            $dataMessage .= $row['short_name']. ";";
            $dataMessage .= $row['quantity']. ";";
            $dataMessage .= $row['cbp']. ";";
            $dataMessage .= $row['discount']. ";";
            $dataMessage .= $row['rbp']. ";";
            $dataMessage .= $row['total_rbp']. ";";
            $dataMessage .= $row['rbp_exclude']. ";";
            $dataMessage .= $row['suggest_order_date']. "\n";
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
$zipname = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_estimasi_inn.zip";

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

if (0 == 0) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Phone Store Inndi", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/". $filename);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132048665', "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '082180603613', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214783', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214971', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214733', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
}

# Delete file in directory
unlink($filename);
unlink('download/' . $zipname);

exit();