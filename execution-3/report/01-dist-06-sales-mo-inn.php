<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-06-sales-mo-inn.php
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=01-dist-06-sales-mo-inn.php");
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$sql = "select
 'D9030-REPORT MASTER OUTLET' as `REPORT NAME`
, NOW() as `REPORT GENERATED TIME`
, (select UPPER(p.plant_name) from plant p join depo d2 on d2.plant_id=p.plant_id where d2.depo_id=s.depo_id) as `PLANT NAME`
, d.depo_code AS `ENTITY CODE`
, d.depo_name as `ENTITY NAME`
, 'SARI ROTI' as `DIVISION`
, (select user_id from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `SALESMAN ID`
, (select ul2.name from journey_plan jp left join user_login ul2 on jp.user_id = ul2.user_id where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `SALESMAN NAME`
, s.store_id as `OUTLET CODE`
, s.store_code as `OUTLET ID`
, s.store_name as `OUTLET NAME`
, if(s.is_active=1, 'ACTIVE', 'NOT ACTIVE') as `STATUS OUTLET`
, (select st.store_type_name from store_type st join store s2 on s2.store_type_id=st.store_type_id  where s2.store_id = s.store_id ) as `TYPE OUTLET`
, (select sc2.classification_name from store_classification sc2 join store s2 on s2.classification_id = sc2.classification_id  where s2.store_id = s.store_id  ) as `OUTLET CLASSIFICATION`
, (select sm.mainstore_name from store_mainstore sm join store_classification sc on sc.mainstore_id = sm.mainstore_id join store s3 on s3.classification_id = sc.classification_id where s3.store_id = s.store_id ) as `MAIN STORE`
, (select sf.function_name from store_function sf join store s2 on s2.function_id = sf.function_id where s2.store_id = s.store_id) as `SAP RECON GROUP`
, (select distinct CEILING(ds.discount_value) from discount_store ds where ds.store_id=s.store_id and ds.product_id = 19 limit 1) as `OUTLET DISCOUNT`
, s.top_id as `TERM OF PAYMENT ID`
, (select UPPER(top.top_name) from term_of_payment top join store s2 on s2.top_id=top.top_id where s2.store_id = s.store_id ) as `TERM OF PAYMENT DESCRIPTION`
, if(s.is_pkp = 1, 'PKP', 'NON PKP') as `STATUS PKP`
, s.npwp as `NO NPWP`
, IF(s.is_credit_limit_active = 1, 'ACTIVE', 'NOT ACTIVE') AS `STATUS CREDIT LIMIT`
, s.credit_limit as `LIMIT CREDIT`
, IF(s.is_max_faktur_active = 1, 'ACTIVE', 'NOT ACTIVE') AS `ACTIVE BLOCK FAKTUR`
, s.max_faktur as `MAXIMAL FAKTUR`
, (select if(retur_type_id=1, 'RETURNABLE', 'NO RETURN') from store_retur_type srt where srt.store_id= s.store_id and is_active = 1 order by srt.modified desc limit 1) `STATUS STORE RETURN`
, (select d2.dropping_date from dropping d2 where d2.store_id = s.store_id limit 1) as `FIRST TRANSACTION`
, (select d2.dropping_date from dropping d2 where d2.store_id = s.store_id order by d2.dropping_id desc limit 1) as `LAST TRANSACTION`
, s.owner_no_ktp as `OWNER NO KTP`
, s.zip_code as `KODE POS`
, (select lk.kelurahan_name from location_kelurahan lk where lk.kelurahan_id = s.kelurahan_id) as `KELURAHAN NAME`
, (select lk3.kelurahan_name from location_kecamatan lk2 join location_kelurahan lk3 on lk3.kecamatan_id=lk2.kecamatan_id where lk3.kelurahan_id=s.kelurahan_id) AS `KECAMATAN NAME`
, s.radius_verification as `RADIUS`
, (select ss.sublocation_name from store_sublocation1 ss where ss.sublocation_id=s.sublocation1_id) as `SUBLOCATION OUTLET NAME`
, (select pt.payment_type_name from payment_type pt where pt.payment_type_id = s.payment_type_id) as `PAYMENT TYPE`
, (select if(jp.monday < 0, 0, jp.monday) + if(jp.tuesday < 0, 0, jp.tuesday) + if(jp.wednesday < 0, 0, jp.wednesday) + if(jp.thursday < 0, 0, jp.thursday)  + if(jp.friday < 0, 0, jp.friday) + if(jp.saturday < 0, 0, jp.saturday) + if(jp.sunday < 0, 0, jp.sunday) from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `JWK SUMMARY`
, (select jp.monday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `SENIN`
, (select jp.tuesday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `SELASA`
, (select jp.wednesday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `RABU`
, (select jp.thursday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `KAMIS`
, (select jp.friday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `JUMAT`
, (select jp.saturday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `SABTU`
, (select jp.sunday from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `MINGGU`
, (select if(jp.is_active = 1, 'ACTIVE', 'NOT ACTIVE') from journey_plan jp where jp.store_id = s.store_id order by jp.is_active desc, jp.first_date desc limit 1) as `STATUS JWK`
, (select lc.city_name from location_city lc join location_kecamatan lk4 on lk4.city_id = lc.city_id join location_kelurahan lk5 on lk5.kecamatan_id = lk4.kecamatan_id where lk5.kelurahan_id = s.kelurahan_id ) as `KOTA NAME`
, (select lp.province_name from location_province lp join location_city lc2 on lc2.province_id = lp.province_id join location_kecamatan lk6 on lk6.city_id=lc2.city_id join location_kelurahan lk7 on lk7.kecamatan_id=lk6.kecamatan_id where lk7.kelurahan_id=s.kelurahan_id) as `PROVINSI NAME`
, (select ts.tools_name from tools_support ts where ts.tools_support_id=s.tools_support_id) as `TOOLS SUPPORT`
, 'SARI ROTI' as `BRAND STORE`
, IFNULL(s.recruiter,'') as `RECRUITER`
, IFNULL((select distinct snn2.platform from store_noo_new snn2 where snn2.store_id= s.store_id),'') as `CREATED FROM`
, IFNULL((select distinct wu.username from store_noo_new snn3 join web_user wu on wu.id_web_user = snn3.created_by where snn3.store_id= s.store_id), '') as `CREATED BY`
, s.created as `CREATED TIME`
, (select GROUP_CONCAT(p2.product_code) from product_store ps join product p2 on p2.product_id = ps.product_id  where ps.store_id=s.store_id and ps.is_selected=1) as `SKU ACTIVE`
# , (select max(po.po_date) from purchase_order po where po.po_date >= DATE_SUB(CURRENT_DATE(), interval 20 day) and po.depo_id=s.depo_id) last_po
from store s
join depo d on d.depo_id = s.depo_id and d.owner_id = 28
where 0=0
and s.is_active = 1
and s.store_type_id not in (845,846,847,848,849,850)
and d.is_dummy = 0
and d.is_active = 1
or 0=0
and s.is_active = 0
and s.created >= DATE_SUB(CURRENT_DATE(), interval 10 day)
and d.is_dummy= 0
and d.is_active = 1
;";

echo "Query starting..." . PHP_EOL;
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo "Query done..." . PHP_EOL;

# Create Excel file
$excel = Excel::create(['DATA']);

# Create a sheet
$sheet = $excel->sheet();

# Put a header
#$sheet->writeRow([ 'REPORT NAME', 'REPORT GENERATED TIME', 'PLANT NAME', 'ENTITY CODE', 'ENTITY NAME', 'DIVISION', 'SALESMAN ID', 'SALESMAN NAME', 'OUTLET CODE', 'OUTLET ID', 'OUTLET NAME', 'STATUS OUTLET', 'TYPE OUTLET', 'OUTLET CLASSIFICATION', 'MAIN STORE', 'SAP RECON GROUP', 'OUTLET DISCOUNT', 'STATUS PKP', 'NO NPWP', 'TERM OF PAYMENT ID', 'TERM OF PAYMENT DESCRIPTION', 'STATUS CREDIT LIMIT', 'LIMIT CREDIT', 'ACTIVE BLOCK FAKTUR', 'MAXIMAL FAKTUR', 'STATUS STORE RETURN', 'FIRST TRANSACTION', 'LAST TRANSACTION', 'OWNER NO KTP', 'KODE POS', 'KELURAHAN NAME', 'KECAMATAN NAME', 'RADIUS', 'SUBLOCATION OUTLET NAME', 'PAYMENT TYPE', 'JWK SUMMARY', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU', 'STATUS JWK', 'KOTA NAME', 'PROVINSI NAME', 'TOOLS SUPPORT', 'BRAND STORE', 'RECRUITER', 'CREATED FROM', 'CREATED BY', 'CREATED TIME', 'SKU ACTIVE' ]);
#$dataMessage = "REPORT NAME;REPORT GENERATED TIME;PLANT NAME;ENTITY CODE;ENTITY NAME;DIVISION;SALESMAN ID;SALESMAN NAME;OUTLET CODE;OUTLET ID;OUTLET NAME;STATUS OUTLET;TYPE OUTLET;OUTLET CLASSIFICATION;MAIN STORE;SAP RECON GROUP;OUTLET DISCOUNT;STATUS PKP;NO NPWP;TERM OF PAYMENT ID;TERM OF PAYMENT DESCRIPTION;STATUS CREDIT LIMIT;LIMIT CREDIT;ACTIVE BLOCK FAKTUR;MAXIMAL FAKTUR;STATUS STORE RETURN;FIRST TRANSACTION;LAST TRANSACTION;OWNER NO KTP;KODE POS;KELURAHAN NAME;KECAMATAN NAME;RADIUS;SUBLOCATION OUTLET NAME;PAYMENT TYPE;JWK SUMMARY;SENIN;SELASA;RABU;KAMIS;JUMAT;SABTU;MINGGU;STATUS JWK;KOTA NAME;PROVINSI NAME;TOOLS SUPPORT;BRAND STORE;RECRUITER;CREATED FROM;CREATED BY;CREATED TIME;SKU ACTIVE\r\n";
$header = [
    'REPORT NAME' => '@string',
    'REPORT GENERATED TIME' => '@string',
    'PLANT NAME' => '@string',
    'ENTITY CODE' => '@string',
    'ENTITY NAME' => '@string',
    'DIVISION' => '@string',
    'SALESMAN ID' => '@string',
    'SALESMAN NAME' => '@string',
    'OUTLET CODE' => '@string',
    'OUTLET ID' => '@string',
    'OUTLET NAME' => '@string',
    'STATUS OUTLET' => '@string',
    'TYPE OUTLET' => '@string',
    'OUTLET CLASSIFICATION' => '@string',
    'MAIN STORE' => '@string',
    'SAP RECON GROUP' => '@string',
    'OUTLET DISCOUNT' => '@string',
    'STATUS PKP' => '@string',
    'NO NPWP' => '@string',
    'TERM OF PAYMENT ID' => '@string',
    'TERM OF PAYMENT DESCRIPTION' => '@string',
    'STATUS CREDIT LIMIT' => '@string',
    'LIMIT CREDIT' => '@string',
    'ACTIVE BLOCK FAKTUR' => '@string',
    'MAXIMAL FAKTUR' => '@string',
    'STATUS STORE RETURN' => '@string',
    'FIRST TRANSACTION' => '@string',
    'LAST TRANSACTION' => '@string',
    'OWNER NO KTP' => '@string',
    'KODE POS' => '@string',
    'KELURAHAN NAME' => '@string',
    'KECAMATAN NAME' => '@string',
    'RADIUS' => '@string',
    'SUBLOCATION OUTLET NAME' => '@string',
    'PAYMENT TYPE' => '@string',
    'JWK SUMMARY' => '@string',
    'SENIN' => '@string',
    'SELASA' => '@string',
    'RABU' => '@string',
    'KAMIS' => '@string',
    'JUMAT' => '@string',
    'SABTU' => '@string',
    'MINGGU' => '@string',
    'STATUS JWK' => '@string',
    'KOTA NAME' => '@string',
    'PROVINSI NAME' => '@string',
    'TOOLS SUPPORT' => '@string',
    'BRAND STORE' => '@string',
    'RECRUITER' => '@string',
    'CREATED FROM' => '@string',
    'CREATED BY' => '@string',
    'CREATED TIME' => '@string',
    'SKU ACTIVE' => '@string'
    ];
$sheet->writeHeader($header);

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {

	    $sheet->writeRow([ $row['REPORT NAME'],
            $row['REPORT GENERATED TIME'],	
            $row['PLANT NAME'],
            $row['ENTITY CODE'],
            $row['ENTITY NAME'],
            $row['DIVISION'],
            $row['SALESMAN ID'],
            $row['SALESMAN NAME'],
            $row['OUTLET CODE'],
            $row['OUTLET ID'],
            $row['OUTLET NAME'],
            $row['STATUS OUTLET'],
            $row['TYPE OUTLET'],
            $row['OUTLET CLASSIFICATION'],
            $row['MAIN STORE'],
            $row['SAP RECON GROUP'],
            $row['OUTLET DISCOUNT'],
            $row['STATUS PKP'],
            $row['NO NPWP'],
            $row['TERM OF PAYMENT ID'],
            $row['TERM OF PAYMENT DESCRIPTION'],
            $row['STATUS CREDIT LIMIT'],
            $row['LIMIT CREDIT'],
            $row['ACTIVE BLOCK FAKTUR'],
            $row['MAXIMAL FAKTUR'],
            $row['STATUS STORE RETURN'],
            $row['FIRST TRANSACTION'],
            $row['LAST TRANSACTION'],
            $row['OWNER NO KTP'],
            $row['KODE POS'],
            $row['KELURAHAN NAME'],
            $row['KECAMATAN NAME'],
            $row['RADIUS'],
            $row['SUBLOCATION OUTLET NAME'],
            $row['PAYMENT TYPE'],
            $row['JWK SUMMARY'],
            $row['SENIN'],
            $row['SELASA'],
            $row['RABU'],
            $row['KAMIS'],
            $row['JUMAT'],
            $row['SABTU'],
            $row['MINGGU'],
            $row['STATUS JWK'],
            $row['KOTA NAME'],
            $row['PROVINSI NAME'],
            $row['TOOLS SUPPORT'],
            $row['BRAND STORE'],
            $row['RECRUITER'],
            $row['CREATED FROM'],
            $row['CREATED BY'],
            $row['CREATED TIME'],
            $row['SKU ACTIVE']
	        ]);
	    
	    /*
        $dataMessage .= $row['REPORT NAME']. ";";
        $dataMessage .= $row['REPORT GENERATED TIME']. ";";	
        $dataMessage .= $row['PLANT NAME']. ";";
        $dataMessage .= $row['ENTITY CODE']. ";";
        $dataMessage .= $row['ENTITY NAME']. ";";
        $dataMessage .= $row['DIVISION']. ";";
        $dataMessage .= $row['SALESMAN ID']. ";";
        $dataMessage .= $row['SALESMAN NAME']. ";";
        $dataMessage .= $row['OUTLET CODE']. ";";
        $dataMessage .= $row['OUTLET ID']. ";";
        $dataMessage .= $row['OUTLET NAME']. ";";
        $dataMessage .= $row['STATUS OUTLET']. ";";
        $dataMessage .= $row['TYPE OUTLET']. ";";
        $dataMessage .= $row['OUTLET CLASSIFICATION']. ";";
        $dataMessage .= $row['MAIN STORE']. ";";
        $dataMessage .= $row['SAP RECON GROUP']. ";";
        $dataMessage .= $row['OUTLET DISCOUNT']. ";";
        $dataMessage .= $row['STATUS PKP']. ";";
        $dataMessage .= $row['NO NPWP']. ";";
        $dataMessage .= $row['TERM OF PAYMENT ID']. ";";
        $dataMessage .= $row['TERM OF PAYMENT DESCRIPTION']. ";";
        $dataMessage .= $row['STATUS CREDIT LIMIT']. ";";
        $dataMessage .= $row['LIMIT CREDIT']. ";";
        $dataMessage .= $row['ACTIVE BLOCK FAKTUR']. ";";
        $dataMessage .= $row['MAXIMAL FAKTUR']. ";";
        $dataMessage .= $row['STATUS STORE RETURN']. ";";
        $dataMessage .= $row['FIRST TRANSACTION']. ";";
        $dataMessage .= $row['LAST TRANSACTION']. ";'";
        $dataMessage .= $row['OWNER NO KTP']. ";";
        $dataMessage .= $row['KODE POS']. ";";
        $dataMessage .= $row['KELURAHAN NAME']. ";";
        $dataMessage .= $row['KECAMATAN NAME']. ";";
        $dataMessage .= $row['RADIUS']. ";";
        $dataMessage .= $row['SUBLOCATION OUTLET NAME']. ";";
        $dataMessage .= $row['PAYMENT TYPE']. ";";
        $dataMessage .= $row['JWK SUMMARY']. ";";
        $dataMessage .= $row['SENIN']. ";";
        $dataMessage .= $row['SELASA']. ";";
        $dataMessage .= $row['RABU']. ";";
        $dataMessage .= $row['KAMIS']. ";";
        $dataMessage .= $row['JUMAT']. ";";
        $dataMessage .= $row['SABTU']. ";";
        $dataMessage .= $row['MINGGU']. ";";
        $dataMessage .= $row['STATUS JWK']. ";";
        $dataMessage .= $row['KOTA NAME']. ";";
        $dataMessage .= $row['PROVINSI NAME']. ";";
        $dataMessage .= $row['TOOLS SUPPORT']. ";";
        $dataMessage .= $row['BRAND STORE']. ";";
        $dataMessage .= $row['RECRUITER']. ";";
        $dataMessage .= $row['CREATED FROM']. ";";
        $dataMessage .= $row['CREATED BY']. ";";
        $dataMessage .= $row['CREATED TIME']. ";";
        $dataMessage .= $row['SKU ACTIVE']. "\n"; 
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
echo "Creating excel..." . PHP_EOL;
$excel->save($filename);

# Preparing name for zip file
$zipname = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_mo_inndi.zip";

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
  
# print($dataMessage);
#$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "MO_INN_FLAG_SKU.txt";

# print($msd);
#$writeResult = file_put_contents("download/". $filename, $dataMessage);

echo "Sending Data..." . PHP_EOL;
if (0 == 0) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Phone Store Inndi", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214965', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214971', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214899', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132214783', "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
  $config["whacentersenddoc"]($config['key-whacenter-1'], '082180603613', "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $zipname);
  #$config["whacentersenddoc"]($config['key-whacenter-1'], '081132048665', "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);
unlink('download/' . $zipname);

echo "Success" . PHP_EOL;

exit();