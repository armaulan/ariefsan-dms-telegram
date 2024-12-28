<?
// https://ariefsan.basproject.online/telegram/execution-1/02-05-blockdropping.php
require '../00-02-conn-dist.php';


$tanggal_akhir = date('Y-m-d');


$sql = "
    select b.depo_code
    , b.depo_name
    , a.store_id
    , a.store_code
    , a.store_name as `NAMA STORE`
    , c.store_type_name
    , case when a.is_dropping_block_by_tagihan = 1 then 'AKTIF' else 'TIDAK AKTIF' end as aa
    , a.created as `CREATED`
    , a.max_faktur, a.first_transaction_date, a.created, a.modified as `UPDATED`
from store a
join depo b on b.depo_id=a.depo_id
join store_type c on c.store_type_id=a.store_type_id
where b.owner_id=28
and a.is_dropping_block_by_tagihan='0'
and a.is_active=1
and c.store_type_id in ('703',
'722','723','730','731','736','743','745','744','746','747','748',
'749','701','702','756','757','758','759','760','767','750','751','752','753','754',
'712','713','714','717')
and a.store_id not in ('267605',
'267628',
'350652',
'403333',
'334827',
'267659',
'267728',
'267635',
'268476',
'267654',
'267681',
'267599',
'398737',
'398741',
'398744',
'398746',
'398749',
'399899',
'402421',
'402422',
'404236',
'404237',
'402423',
'267551',
'401544',
'402105',
'267587',
'267507',
'187712',
'188104',
'267582',
'267674',
'267703',
'267992',
'268263',
'268367',
'317220',
'186934',
'267776',
'269586',
'271660',
'280845',
'284547',
'335270',
'339898',
'187804',
'267530',
'267536',
'267542',
'267545',
'267550',
'267556',
'267561',
'267651',
'382865',
'249423',
'237128',
'333114',
'267529',
'267660',
'317022',
'228254',
'267553',
'228147',
'228148',
'230378',
'267534',
'395641',
'409506',
'409724',
'267692',
'271382',
'406519',
'397519',
'397726',
'228248',
'249273',
'308757',
'250610',
'320446',
'250559',
'397520',
'349124',
'349125',
'349134',
'349135',
'349136',
'321541',
'349126',
'349133',
'349729',
'351823',
'250654',
'267520',
'228034',
'228050',
'268020',
'268082',
'268246',
'273670',
'321318',
'268937',
'269132',
'448025',
'267543',
'232388',
'382765')
limit 50
";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-DIST (Not Aktif Block Dropping By Tagihan) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nama Depo :" . $row['depo_name'] . "\n" .
          		"Store ID :" . $row['store_id'] . "\n" .
          		"Store Code :" . $row['store_code'] . "\n" .
          		"Store Name :" . $row['NAMA STORE'] . "\n".
          		"Type Store :" . $row['store_type_name'] . "\n" .
          		"Create Store :" . $row['CREATED'] . "\n" .
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

