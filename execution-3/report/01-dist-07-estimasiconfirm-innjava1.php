<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/01-dist-07-estimasiconfirm-innjava1.php
exit();
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
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
and plant_id in (1,7,8,9,10)
) as aa
having main_store in ('MTI','WARUNG')
) as aaa;
";

# Execute query
$results = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# If there's a result
if(mysqli_num_rows($results) > 0) {
    
    # Prepare file name
    $fileName = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_estimasijava1-inn.csv";
    
    # Prepare file path
    $filePath = 'download/' . $fileName;
    
    # Open that file
    $fp = fopen($filePath, 'w');    
    
    # Fetch columnn data
    $fields = mysqli_fetch_fields($results);
    
    # Put Column name into first row
    $header_row = [];
    foreach ($fields as $field) {
        $header_row[] = strtoupper($field->name);
    }
    fputcsv($fp, $header_row);
    
    # Put rest of all data
    while ($row = mysqli_fetch_assoc($results)) {
        fputcsv($fp, $row);
    }   
    
    # close file
    fclose($fp);
    
    # Preparing name for zip file
    $zipName = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_estimasijava1-inn";
    $zipExtention = ".zip";
    $zipPath = "download/". $zipName. $zipExtention;
    
    # Instantiate new zip object
    $zip = new ZipArchive;
    
    if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE)
    {
        # Adding a password
        $zip->setPassword('@Roti2024');
        
        # Adding excel file into zip
        $zip->addFile($filePath, $zipName. '.csv');
        
        # Set encryption
        $zip->setEncryptionName($zipName. '.csv', ZipArchive::EM_AES_256);
    
        # Close the process    
        $zip->close();
    }
    
    # Send file throught whatsapp
    $config["whacentersenddoc"]($config['key-whacenter-1'], '082180603613', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/". $zipPath);
    
    # Delete file in directory
    unlink($filePath);
    unlink($zipPath);
    exit();
}
