<?
// https://ariefsan.basproject.online/telegram/execution-1/02-11-SetupPromoSCK.php
require '../00-02-conn-dist.php';


$tanggal_akhir = date('Y-m-d');


$sql = "
select d.depo_code as `KODE DEPO`
, d.depo_name as `NAMA DEPO`
, a.store_id as `STORE ID`
, a.store_code as `STORE CODE`
, a.store_name as `STORE NAME`
, b.store_type_name as `TYPE STORE`
, format((select avg(ds.discount_value) from discount_store ds 
	 where ds.depo_id = a.depo_id
	 and ds.store_id = a.store_id),2) as `DISCOUNT TOKO`
, (select dsp.discount_value from discount_store_promo dsp 
	 where dsp.store_id = a.store_id 
	 and product_id = 137) as `ADDITIONALPROMOSCK`
, e.payment_type_name as `PAYMENT TYPE`
, case when c.status_return = 1 then 'RETURNABLE' else 'NON RETURNABLE' end as `STATUS RETURN`
, case when a.is_credit_limit_active = 1 then 'AKTIF' else 'NOT ACTIVE' end as `STATUS CREDIT LIMIT`
, concat('Rp. ',format(a.credit_limit,0)) as `CREDIT LIMIT`
, f.top_name as `TOP`
, case when a.is_max_faktur_active = 1 then 'AKTIF' else 'NOT ACTIVE' end as `STATUS MAX FAKTUR`
, a.max_faktur as `MAX FAKTUR`
, case when a.is_dropping_block_by_tagihan = 1 then 'AKTIF' else 'TIDAK AKTIF' end as `STATUS DROPPING BY JWK`
, a.created as `TANGGAL CREATED`
, a.modified as `MODIFIED`
, a.last_visited as `TANGGAL TERAKHIR KUNJUNGAN`
from store a
join store_type b on b.store_type_id = a.store_type_id
join outlet_store_kpi c on c.store_type_id = a.store_type_id
join depo d on d.depo_id = a.depo_id
join payment_type e on e.payment_type_id = a.payment_type_id
join term_of_payment f on f.top_id = a.top_id
where 0=0 
and b.store_type_id in ('757','758','759','760')
and d.depo_id in (select po.depo_id from purchase_order po where po.selling_date > CURDATE())
and d.plant_id not in ('2','11')
and d.owner_id = 28
and a.is_active = 1
having ADDITIONALPROMOSCK is null
limit 50
";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-DIST (Not Aktif Dropping Promo SCK) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo :" . $row['KODE DEPO'] . "\n" .
          		"NAMA DEPO :" . $row['NAMA DEPO'] . "\n" .
          		"Store Code :" . $row['STORE CODE'] . "\n" .
          		"Store Name :" . $row['STORE NAME'] . "\n".
          		"Type Store :" . $row['TYPE STORE'] . "\n" .
          		"Create Store :" . $row['CREATED'] . "\n" .
          		"Modified Store :" . $row['MODIFIED'] . "\n" .
          		"Kunjungan Terakhir:" . $row['UPDATED'] . "\n \n"
                    ;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendMessage?chat_id=-1001542755544&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output;
} 

