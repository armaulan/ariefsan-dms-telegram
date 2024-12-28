<?
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/00-test.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();
echo "Ping-64";

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
, (select st.store_type_name from store_type st join store s2 on s2.store_type_id=st.store_type_id  where s2.store_id = s.store_id ) as `TYPE OUTLET NAME`
, (select sc2.classification_name from store_classification sc2 join store s2 on s2.classification_id = sc2.classification_id  where s2.store_id = s.store_id  ) as `OUTLET CLASSIFICATION NAME`
, (select sf.function_name from store_function sf join store s2 on s2.function_id = sf.function_id where s2.store_id = s.store_id) as `OUTLET FUNCTION NAME`
, (select distinct CEILING(ds.discount_value) from discount_store ds where ds.store_id=s.store_id and ds.product_id = 19 limit 1) as `OUTLET DISCOUNT`
, s.top_id as `TERM OF PAYMENT ID`
, (select UPPER(top.top_name) from term_of_payment top join store s2 on s2.top_id=top.top_id where s2.store_id = s.store_id ) as `TERM OF PAYMENT DESCRIPTION`
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
# , (select max(po.po_date) from purchase_order po where po.po_date >= DATE_SUB(CURRENT_DATE(), interval 20 day) and po.depo_id=s.depo_id) last_po
from store s
join depo d on d.depo_id = s.depo_id and d.owner_id = 28
where 0=0
and s.is_active = 1
and d.is_dummy = 0
and d.is_active = 1
or 0=0
and s.is_active = 0
and s.created >= DATE_SUB(CURRENT_DATE(), interval 10 day)
and d.is_dummy= 0
and d.is_active = 1
;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo "Ping-76";

$dataMessage = "REPORT NAME;REPORT GENERATED TIME;PLANT NAME;ENTITY CODE;ENTITY NAME;DIVISION;SALESMAN ID;SALESMAN NAME;OUTLET CODE;OUTLET ID;OUTLET NAME;STATUS OUTLET;TYPE OUTLET NAME;OUTLET CLASSIFICATION NAME;OUTLET FUNCTION NAME;OUTLET DISCOUNT;TERM OF PAYMENT ID;TERM OF PAYMENT DESCRIPTION;STATUS CREDIT LIMIT;LIMIT CREDIT;ACTIVE BLOCK FAKTUR;MAXIMAL FAKTUR;STATUS STORE RETURN;FIRST TRANSACTION;LAST TRANSACTION;OWNER NO KTP;KODE POS;KELURAHAN NAME;KECAMATAN NAME;RADIUS;SUBLOCATION OUTLET NAME;PAYMENT TYPE;JWK SUMMARY;SENIN;SELASA;RABU;KAMIS;JUMAT;SABTU;MINGGU;STATUS JWK;KOTA NAME;PROVINSI NAME;TOOLS SUPPORT;BRAND STORE;RECRUITER;CREATED FROM;CREATED BY;CREATED TIME \r\n";

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
        $dataMessage .= $row['REPORT NAME']. ";";
        $dataMessage .= $row['REPORT GENERATED TIME']. ";";	
        $dataMessage .= $row['PLANT NAME']. ";";
        $dataMessage .= $row['ENTITY CODE']. ";";
        $dataMessage .= $row['ENTITY NAME']. ";";
        $dataMessage .= $row['DIVISION']. ";";
        $dataMessage .= $row['SALESMAN ID']. ";";
        $dataMessage .= $row['OUTLET CODE']. ";";
        $dataMessage .= $row['OUTLET ID']. ";";
        $dataMessage .= $row['OUTLET NAME']. ";";
        $dataMessage .= $row['STATUS OUTLET']. ";";
        $dataMessage .= $row['TYPE OUTLET NAME']. ";";
        $dataMessage .= $row['OUTLET CLASSIFICATION NAME']. ";";
        $dataMessage .= $row['OUTLET FUNCTION NAME']. ";";
        $dataMessage .= $row['OUTLET DISCOUNT']. ";";
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
        $dataMessage .= $row['CREATED TIME']. "\n";
        
      
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
  
# print($dataMessage);
$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_mo-inn.txt";

# print($msd);
$writeResult = file_put_contents("download/". $filename, $dataMessage);

if ($writeResult != false) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Data Phone Store Inndi", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename);
}




