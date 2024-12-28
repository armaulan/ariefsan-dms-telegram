<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-02-cekstore.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$json = json_decode($_POST['let2']);
$value = $json[1];
#$value = '1437432359';


$sql = "select d.depo_code as `KODE DEPO`
, d.depo_name as `NAMA DEPO`
, a.store_id as `STORE ID`
, a.store_code as `STORE CODE`
, a.store_name as `STORE NAME`
, a.owner_no_ktp as `OWNER KTP`
, b.store_type_name as `TYPE STORE`
, case when a.is_active = 1 then 'AKTIF' else 'NOT ACTIVE' end as `STATUS AKTIF TOKO`
, g.classification_name as `STORE CLASSIFICATION NAME`
, h.function_name as `SAP RECON GROUP`
, format((select avg(ds.discount_value) from discount_store ds 
	 where ds.depo_id = a.depo_id
	 and ds.store_id = a.store_id),2) as `DISCOUNT TOKO`
, (select dsp.discount_value from discount_store_promo dsp 
	 where dsp.store_id = a.store_id 
	 and product_id = 137
     order by dsp.created desc limit 1) as `ADDITIONAL PROMO`
, e.payment_type_name as `PAYMENT TYPE`
, case when c.status_return = 1 then 'RETURNABLE' else 'NON RETURNABLE' end as `STATUS RETURN`
, case when a.is_credit_limit_active = 1 then 'AKTIF' else 'NOT ACTIVE' end as `STATUS CREDIT LIMIT`
, concat('Rp. ',format(a.credit_limit,0)) as `CREDIT LIMIT`
, f.top_name as `TOP`
, case when a.is_max_faktur_active = 1 then 'AKTIF' else 'NOT ACTIVE' end as `STATUS MAX FAKTUR`
, a.max_faktur as `MAX FAKTUR`
, case when a.is_dropping_block_by_tagihan = 1 then 'AKTIF' else 'TIDAK AKTIF' end as `STATUS DROPPING BY JWK`
, a.created as `TANGGAL CREATED`
, a.last_visited as `TANGGAL TERAKHIR KUNJUNGAN`
,(select concat('Rp. ',format(sum(dr.total_cbp),0)) from dropping dr
		where dr.store_id=a.store_id
		and year(dr.dropping_date)= year(CURRENT_DATE())
		and month(dr.dropping_date)= month(CURRENT_DATE())) as `DROPPING MTD`
, (select concat('Rp. ',format(sum(t.total_pure_sales),0)) from tagihan t
		where t.store_id=a.store_id
		and year(t.invoice_date)= year(CURRENT_DATE())
		and month(t.invoice_date)= month(CURRENT_DATE())) as `NET SALES MTD`
, ul.user_id as `USER ID`
, ul.name as `NAMA DELMAN`
, concat(if(jp.monday=1,'SENIN',''),
if(jp.tuesday=1,', SELASA',''),
if(jp.wednesday=1,', RABU',''),
if(jp.thursday=1,', KAMIS',''),
if(jp.friday=1,', JUMAT',''),
if(jp.saturday=1,', SABTU',''),
if(jp.sunday=1,', MINGGU','')) as `JWK`,
if(a.is_pkp= 1, 'PKP','NON PKP') as `STATUS PKP`,
a.npwp as `NO NPWP`, a.npwp_address as `ALAMAT PKP`
from store a
left join store_type b on b.store_type_id = a.store_type_id
left join outlet_store_kpi c on c.store_type_id = a.store_type_id
left join depo d on d.depo_id = a.depo_id
left join payment_type e on e.payment_type_id = a.payment_type_id
left join term_of_payment f on f.top_id = a.top_id
left join store_classification g on g.classification_id = a.classification_id
left join store_function h on h.function_id = b.function_id
join journey_plan jp on jp.store_id = a.store_id 
join user_login ul on ul.user_id = jp.user_id 
where 0=0 
and jp.is_active = 1
and a.store_id = '$value'
or a.store_code = '$value';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Status Store Dist) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo : " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo : \n" . $row['NAMA DEPO'] . "\n \n \n" .
                "Store ID: " . $row['STORE ID'] . "\n" .
                "Store Code: " . $row['STORE CODE'] . "\n" .
          		"Store Name: \n" . $row['STORE NAME'] . "\n \n" .
          		"User ID: \n" . $row['USER ID'] . "\n" .
          		"Nama Hawker: \n" . $row['NAMA DELMAN'] . "\n" .
          		"JWK: \n" . $row['JWK'] . "\n \n" .
          		"Nomor KTP: \n" . $row['OWNER KTP'] . "\n" .
          		"Status PKP: \n" . $row['STATUS PKP'] . "\n" .
          		"No NPWP: \n" . $row['NO NPWP'] . "\n" .
          		"Alamat PKP: \n" . $row['ALAMAT PKP'] . "\n" .
          		"Type Store: " . $row['TYPE STORE'] . "\n" .
          		"Store Classification Name: \n" . $row['STORE CLASSIFICATION NAME'] . "\n" .
          		"SAP RECON GROUP: \n" . $row['SAP RECON GROUP'] . "\n" .
          		"Status Aktif Toko: " . $row['STATUS AKTIF TOKO'] . "\n" .
                "Discount Toko: " . $row['DISCOUNT TOKO'] . "% \n" .
          		"Add DiscPromo SCK: " . $row['ADDITIONAL PROMO'] . "% \n" .
          		"Payment Type: " . $row['PAYMENT TYPE'] . "\n" .
                "Status Return : " . $row['STATUS RETURN'] . "\n" .
                "Status Credit Limit : " . $row['STATUS CREDIT LIMIT'] . "\n" .
                "Nominal Credit Limit: " . $row['CREDIT LIMIT'] . "\n" .
                "TOP: " . $row['TOP'] . "\n" .
          		"Status Max Faktur : " . $row['STATUS MAX FAKTUR'] . "\n" .
                "Max Faktur : " . $row['MAX FAKTUR'] . "\n" .
          		"Status Dropping by JWK : " . $row['STATUS DROPPING BY JWK'] . "\n".
          		"Created : " . $row['TANGGAL CREATED'] . "\n".
          		"Kunjungan Terakhir : " . $row['TANGGAL TERAKHIR KUNJUNGAN'] . "\n".
          		"Dropping MTD : " . $row['DROPPING MTD'] . "\n".
          		"Net Sales MTD : " . $row['NET SALES MTD'] . "\n \n \n"
                    ;
      }

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
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
    # echo $output;
} 
