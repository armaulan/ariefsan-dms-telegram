<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-05-arinn.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

# $json = json_decode($_POST['let2']);
# $nomordropping = $json[1];


$sql = "select aaa.plant_name
, aaa.entity_code as `DEPO`
, aaa.entity_name 
-- , aaa.salesman_brand_code
-- , aaa.salesman_brand_name
-- , aaa.type_outlet
-- , aaa.outlet_id
-- , aaa.outlet_code
-- , aaa.salesman_id
-- , aaa.salesman_nik 
-- , aaa.salesman_name 
-- , aaa.drop_cbp
-- , aaa.netsales_rbp 
, aaa.status_outstanding as `status`
-- , aaa.outlet_top
/*
, case 
	when aaa.status_outstanding = 'Outstanding Dropping' then 0
	when aaa.status_outstanding = 'Outstanding Setoran Bank' then 0
	when aaa.status_outstanding = 'Outstanding AR' then datediff(CURRENT_DATE(), aaa.invoice_date)
	end as umur_piutang
, case 
	when aaa.status_outstanding = 'Outstanding Dropping' then 0
	when aaa.status_outstanding = 'Outstanding Setoran Bank' then 0
	when aaa.status_outstanding = 'Outstanding AR' and (DATE_ADD(aaa.invoice_date, interval aaa.outlet_top day) < CURRENT_DATE())  then datediff(CURRENT_DATE(), DATE_ADD(aaa.invoice_date, interval aaa.outlet_top day) )
	else 0
	end as overdue_piutang
, if(aaa.status_outstanding = 'Outstanding Dropping',  datediff(CURRENT_DATE(), aaa.dropping_date), 0) as overdue_dropping
, '' as 'keterangan'
, aaa.dropping_date
, aaa.invoice_date 
, aaa.payment_date
, aaa.cashier_receipt_date
, aaa.bank_deposit_date
, aaa.dropping_number 
, aaa.invoice_number
, aaa.cashier_receipt_number
, aaa.bank_deposit_number */
, sum(aaa.nilai_rbp_pembebanan_ar) as `AMOUNT`
-- , aaa.generated_time
from (
select 
aa.plant_name
, aa.entity_code
, aa.entity_name 
, 'SR' as salesman_brand_code
, 'Sari Roti' as salesman_brand_name
, aa.type_outlet
, aa.outlet_id
, aa.outlet_code
, aa.salesman_id
, aa.salesman_nik 
, aa.salesman_name 
, aa.drop_cbp
, '' as netsales_rbp
, aa.status_outstanding
, aa.outlet_top
, aa.dropping_date
, '' as invoice_date 
, '' as payment_date
, '' as cashier_receipt_date
, '' as bank_deposit_date
, aa.dropping_number 
, '' as invoice_number
, '' as cashier_receipt_number
, '' as bank_deposit_number
, aa.nilai_rbp_pembebanan_ar
, now() as generated_time
from (
select 
d.depo_id 
, (select dp.depo_code from depo dp where dp.depo_id= d.depo_id) as entity_code
, (select dp.depo_name from depo dp where dp.depo_id= d.depo_id) as entity_name
, (select p.plant_name from depo dp join plant p on p.plant_id =dp.plant_id where dp.depo_id= d.depo_id) as plant_name 
, d.dropping_number 
, d.dropping_date 
, d.total_cbp as drop_cbp
, d.salesman_id
, (select ul.nik from user_login ul where ul.user_id=d.salesman_id) as salesman_nik
, (select ul.name from user_login ul where ul.user_id=d.salesman_id) as salesman_name
, d.store_id as outlet_id
, (select s.store_code from store s where s.store_id = d.store_id) as outlet_code
, (select s.store_name from store s where s.store_id = d.store_id) as outlet_name
, (select st.store_type_name from store_type st join store s on s.store_type_id=st.store_type_id where s.store_id=d.store_id) as type_outlet
# , 'Outstanding Dropping' as status_outstanding
, 'Drop' as status_outstanding
, (select s.top_id from store s where s.store_id=d.store_id) as outlet_top
, d.total_rbp as nilai_rbp_pembebanan_ar
from dropping d 
where 0=0
and d.status_id = 1
having depo_id in (select dp.depo_id from depo dp where dp.owner_id = 28)
# having depo_id in (302, 300)
and total_cbp > 0
) as aa
UNION 
select 
aa.plant_name
, aa.entity_code
, aa.entity_name 
, 'SR' as salesman_brand_code
, 'Sari Roti' as salesman_brand_name
, aa.type_outlet
, aa.outlet_id
, aa.outlet_code
, aa.salesman_id
, aa.salesman_nik 
, aa.salesman_name 
, aa.drop_cbp
, aa.netsales_rbp
, aa.status_outstanding
, aa.outlet_top
, aa.dropping_date
, aa.invoice_date 
, '' as payment_date
, '' as cashier_receipt_date
, '' as bank_deposit_date
, aa.dropping_number 
, aa.invoice_number
, '' as cashier_receipt_number
, '' as bank_deposit_number
, aa.netsales_rbp as nilai_rbp_pembebanan_ar
, now() as generated_time
from (
select 
d.depo_id 
, (select dp.depo_code from depo dp where dp.depo_id= d.depo_id) as entity_code
, (select dp.depo_name from depo dp where dp.depo_id= d.depo_id) as entity_name
, (select p.plant_name from depo dp join plant p on p.plant_id =dp.plant_id where dp.depo_id= d.depo_id) as plant_name 
, d.salesman_id 
, (select ul.nik from user_login ul where ul.user_id=d.salesman_id) as salesman_nik
, (select ul.name from user_login ul where ul.user_id=d.salesman_id) as salesman_name
, d.store_id as outlet_id
, (select s.store_code from store s where s.store_id = d.store_id) as outlet_code
, (select s.store_name from store s where s.store_id = d.store_id) as outlet_name
, (select st.store_type_name from store_type st join store s on s.store_type_id=st.store_type_id where s.store_id=d.store_id) as type_outlet
, d.dropping_number 
, d.dropping_date 
, (select t.tagihan_id from tagihan t where t.dropping_number=d.dropping_number) as tagihan_id
, (select t.invoice_number from tagihan t where t.dropping_number=d.dropping_number) as invoice_number 
, (select t.invoice_date from tagihan t where t.dropping_number=d.dropping_number) as invoice_date
, d.total_cbp as drop_cbp
, (select t.total_tagihan from tagihan t where t.dropping_number=d.dropping_number) as netsales_rbp
, IFNULL((select tl.tagihan_id from tagihan t join tagihan_lunas tl on tl.tagihan_id = t.tagihan_id where t.dropping_number = d.dropping_number and tl.tanggal_lunas is not null),0) as tanggal_lunas
# , 'Outstanding AR' as status_outstanding
, 'AR' as status_outstanding
, (select s.top_id from store s where s.store_id=d.store_id) as outlet_top
from dropping d 
where 0=0
and d.status_id = 2
having d.depo_id in (select dp.depo_id from depo dp where dp.owner_id=28)
# having depo_id in (302, 300)
and tanggal_lunas = 0
and netsales_rbp > 0
) as aa
UNION 
select 
aa.plant_name
, aa.entity_code
, aa.entity_name 
, 'SR' as salesman_brand_code
, 'Sari Roti' as salesman_brand_name
, aa.type_outlet
, aa.outlet_id
, aa.outlet_code
, aa.salesman_id
, aa.salesman_nik 
, aa.salesman_name 
, aa.drop_cbp
, aa.netsales_rbp
, aa.status_outstanding
, aa.outlet_top
, aa.dropping_date
, aa.invoice_date 
, aa.payment_date as payment_date
, '' as cashier_receipt_date
, '' as bank_deposit_date
, aa.dropping_number 
, aa.invoice_number
, '' as cashier_receipt_number
, '' as bank_deposit_number
, aa.netsales_rbp as nilai_rbp_pembebanan_ar
, now() as generated_time
from (
	select 
	d.dropping_id 
	, d.depo_id 
	, (select dp.depo_code from depo dp where dp.depo_id= d.depo_id) as entity_code
	, (select dp.depo_name from depo dp where dp.depo_id= d.depo_id) as entity_name
	, (select p.plant_name from depo dp join plant p on p.plant_id =dp.plant_id where dp.depo_id= d.depo_id) as plant_name
	, d.salesman_id 
	, (select ul.nik from user_login ul where ul.user_id=d.salesman_id) as salesman_nik
	, (select ul.name from user_login ul where ul.user_id=d.salesman_id) as salesman_name
	, d.store_id as outlet_id
	, (select s.store_code from store s where s.store_id = d.store_id) as outlet_code
	, (select s.store_name from store s where s.store_id = d.store_id) as outlet_name
	, (select st.store_type_name from store_type st join store s on s.store_type_id=st.store_type_id where s.store_id=d.store_id) as type_outlet
	, d.dropping_date 
	, d.dropping_number 
	, (select t.tagihan_id from tagihan t where t.dropping_number=d.dropping_number) as tagihan_id
	, (select t.invoice_number from tagihan t where t.dropping_number=d.dropping_number) as invoice_number 
	, (select t.invoice_date from tagihan t where t.dropping_number=d.dropping_number) as invoice_date
	, (select t.status_id from tagihan t where t.dropping_number= d.dropping_number) as invoice_status
	, (select d2.owner_id from depo d2 where d2.depo_id=d.depo_id) owner_id
	, (select t.total_tagihan from tagihan t where t.dropping_number= d.dropping_number) as netsales_rbp
	, d.total_cbp as drop_cbp
	# , 'Outstanding Penerimaan Kasir' as status_outstanding
    , 'PK' as status_outstanding
	, (select s.top_id from store s where s.store_id=d.store_id) as outlet_top
	, (select tl.tanggal_lunas from tagihan t join tagihan_lunas tl on tl.tagihan_id = t.tagihan_id where t.dropping_number = d.dropping_number and tl.tanggal_lunas is not null) as payment_date
	from dropping d 
	where 0=0
	and d.dropping_id >= 17413417
	and d.status_id = 3
	or d.dropping_id in (8145485,	8154882,	8461790,	8553025,	8553053,	8553256,	8553274,	8576887,	8576900,	8576912,	8576928,	8576937,	8576946,	8576957,	8576967,	8576997,	8577261,	8577262,	8577266,	8582581,	8623168,	8667346,	8672785,	8672786,	8672794,	8682930,	8694074,	8694102,	8701529,	8737531,	8747452,	8750871,	8758259,	8766448,	8766714,	8766737,	8766742,	8766756,	8766760,	8766770,	8775194,	8824030,	8824040,	8824055,	8824065,	8824079,	8824120,	8824127,	8824135,	8824184,	8824199,	8826927,	8883410,	9141501,	9141570,	9380240,	9488035,	9991327,	9997840,	10004977,	10013643,	10027044,	10034520,	10050967,	10085324,	10099142,	10140417,	10156908,	10175751,	10184806,	10194646,	10213770,	10239412,	10263534,	10265138,	10274701,	10276277,	10283657,	10299904,	10314327,	10327135,	10349486,	10361333,	10367600,	10382756,	10390275,	10404878,	10419355,	10428842,	10437864,	10440882,	10445976,	10446063,	10459546,	10476177,	10486702,	10495194,	10495529,	10504828,	10520574,	10534017,	10541823,	10541827,	10552084,	10566526,	10568778,	10591784,	10609161,	10638838,	10663423,	10666212,	10678750,	10691952,	10696633,	10734990,	10735011,	10765323,	10774285,	10815551,	10817725,	10817940,	10870544,	10870648,	10870674,	10899481,	10907696,	10907740,	10975800,	10980704,	10989475,	11010056,	11024257,	11042972,	11054118,	11069548,	11076835,	11089513,	11103189,	11134233,	11163625,	11170292,	11180664,	11202474,	11216896,	11241498,	11299258,	13674477,	13760313,	13760367,	13770941,	13796957,	13796964,	13796985,	13797016,	13850090,	15169739,	15739242,	15883438)
	having invoice_status = 3
	and owner_id = 28
    # and depo_id in (302, 300)
	and netsales_rbp > 0
) as aa
UNION
select aa.plant_name
, aa.entity_code
, aa.entity_name 
, 'SR' as salesman_brand_code
, 'Sari Roti' as salesman_brand_name
, aa.type_outlet
, aa.outlet_id
, aa.outlet_code
, aa.salesman_id
, aa.salesman_nik 
, aa.salesman_name 
, aa.drop_cbp
, aa.netsales_rbp
, aa.status_outstanding
, aa.outlet_top
, aa.dropping_date
, aa.invoice_date 
, aa.payment_date
, aa.cashier_receipt_date
, '' as bank_deposit_date
, aa.dropping_number 
, aa.invoice_number
, aa.cashier_receipt_number
, '' as bank_deposit_number
, aa.netsales_rbp as nilai_rbp_pembebanan_ar
, now() as generated_time
from (
select 
t.invoice_number
, t.dropping_number 
, t.dropping_date 
, t.invoice_date 
, bb.penerimaan_date as cashier_receipt_date
, t.total_dropping as drop_cbp
, t.total_tagihan as netsales_rbp
, t.total_tagihan as nilai_rbp_pembebanan_ar
, t.depo_id 
, (select dp.depo_code from depo dp where dp.depo_id= t.depo_id) as entity_code
 ,(select dp.depo_name from depo dp where dp.depo_id= t.depo_id) as entity_name
 ,(select p.plant_name from depo dp join plant p on p.plant_id =dp.plant_id where dp.depo_id= t.depo_id) as plant_name
, t.store_id as outlet_id
, (select s.store_code from store s where s.store_id = t.store_id) as outlet_code
, (select s.store_name from store s where s.store_id = t.store_id) as outlet_name
, (select st.store_type_name from store_type st join store s on s.store_type_id=st.store_type_id where s.store_id=t.store_id) as type_outlet
, t.salesman_id 
, (select ul.nik from user_login ul where ul.user_id=t.salesman_id) as salesman_nik
, (select ul.name from user_login ul where ul.user_id=t.salesman_id) as salesman_name
# , 'Outstanding Setoran Bank' as status_outstanding
, 'SB' as status_outstanding
, (select s.top_id from store s where s.store_id=t.store_id) as outlet_top
, (select tl.tanggal_lunas from tagihan_lunas tl where tl.tagihan_id=t.tagihan_id) as payment_date
, bb.penerimaan_kasir_number as cashier_receipt_number
from tagihan t 
join penerimaan_kasir_detail pkd on pkd.invoice_number = t.invoice_number 
join (
	select 
		pk.salesman_id 
		, pk.penerimaan_kasir_number 
		, pk.penerimaan_kasir_id 
		, pk.penerimaan_date
		from penerimaan_kasir pk  
		where 0=0
		and pk.status_id = 1
		having pk.salesman_id in (select ul.user_id from user_login ul join depo d on d.depo_id=ul.depo_id where d.owner_id=28)
        # having pk.salesman_id in (select ul.user_id from user_login ul join depo d on d.depo_id=ul.depo_id where d.depo_id in (302,300))
	) as bb on bb.penerimaan_kasir_id = pkd.penerimaan_id 
where 0=0 
and t.total_tagihan > 0
) as aa 
) as aaa
GROUP BY aaa.entity_code, aaa.status_outstanding
# where aaa.status_outstanding = 'Outstanding AR'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$exec2 = mysqli_query($conn, $sql) or die(mysqli_error($conn));
# echo mysqli_num_rows($exec);

$msg = "#02-Dist (AR INN) \n";
if(mysqli_num_rows($exec) > 0) {
  
  	$tempDepo = '';
    while($row = mysqli_fetch_array($exec2)) {
      	
      	if($tempDepo != $row['DEPO']){
        	$msg .= "\n";
          	$msg .= $row['entity_name']. "\n";
          	$msg .= $row['status']. ": ". number_format((int)$row['AMOUNT']). "\n";
        } else {
        	$msg .= $row['status']. ": ". number_format((int)$row['AMOUNT']). "\n";
        }
      
      	$tempDepo = $row['DEPO'];
      
	}
  	# $msg .= strlen($msg) . " char";
  	$config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
  
    # $ch = curl_init(); 

    // set url 
    # curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    # curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    # $output = curl_exec($ch); 

    // tutup curl 
    # curl_close($ch);      

    // menampilkan hasil curl
    
    # echo $output;
} 
