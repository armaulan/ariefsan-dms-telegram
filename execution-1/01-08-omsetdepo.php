<?
// https://ariefsan.basproject.online/telegram/execution-1/01-08-omsetdepo.php
require '../00-01-conn-agent.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$bulan = $json[1];
$sort = $json[2];

/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select 
a.depo_code as `KODE DEPO`
, a.depo_name as `NAMA DEPO`
, concat('Rp. ',format(sum(b.total_cbp),0)) as `TOTAL OMSET DEPO`
, (select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=2 
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET HAWKER`
, (select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=5 
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET KURIR`
, (select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=8 
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET COMMUNITY`
,(select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=4
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET ROLE DN`
,(select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=6
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET PENJUALAN KARYAWAN`
, (select concat('Rp. ', format(sum(aa.total_cbp),0)) from agent_selling aa 
	 join user_login bb on bb.user_id=aa.user_id 
	 where bb.role_id=7 
	 and aa.depo_id=a.depo_id
	 and aa.tanggal_selling like '$bulan%') as `OMSET GUDANG CHATBOT`
, sum(b.total_cbp) as `OMSET2`
from depo a
left join agent_selling b on b.depo_id=a.depo_id
where 0=0
and a.owner_id=28
and b.tanggal_selling like '$bulan%'
GROUP BY a.depo_code
ORDER BY OMSET2 $sort
limit 10
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Omset Depo MTD) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo:\n" . $row['NAMA DEPO'] . "\n" .
                "Total Omset Depo:" . $row['TOTAL OMSET DEPO'] . "\n" .
				"Omset Hawker:" . $row['OMSET HAWKER'] . "\n" .
				"Omset Kurir:" . $row['OMSET KURIR'] . "\n" .
				"Omset Community:" . $row['OMSET COMMUNITY'] . "\n" .
				"Omset DN:" . $row['OMSET ROLE DN'] . "\n" .
				"Omset Penjualan Karyawan:" . $row['OMSET PENJUALAN KARYAWAN'] ."\n".
				"Omset Gudang Chatbot:" . $row['OMSET GUDANG CHATBOT'] . "\n \n \n"            
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
