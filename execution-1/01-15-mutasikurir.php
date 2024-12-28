<?
// https://ariefsan.basproject.online/telegram/execution-1/01-15-mutasikurir.php
require '../00-01-conn-agent.php';


$tanggal_akhir = date('Y-m-d');


$sql = "
 select b.depo_code,
 b.depo_name as `NAMA DEPO`,
 a.user_id as `USER ID`,
 a.name as `NAMA HAWKER`,
 a.username as `USERNAME`,
 c.role_name as `ROLE HAWKER`,
case when a.mutasi_allowed = 1 then 'YA' else 'TIDAK' end as `IZIN MUTASI`,
(select GROUP_CONCAT(c1.role_name) from mutasi_lock a1
join user_login b1 on b1.user_id = a1.user_id
join user_role c1 on c1.role_id = a1.role_id 
where 0=0
and a1.user_id = a.user_id ) as `MUTASI KE`,
(select as2.tanggal_selling from agent_selling as2 where as2.depo_id = a.depo_id and as2.user_id = a.user_id order by as2.selling_id desc limit 1) as `LAST SELLING`  
from user_login a
join depo b on b.depo_id=a.depo_id 
join user_role c on c.role_id = a.role_id
where b.owner_id = 28
and c.role_id = 2
and a.mutasi_allowed = 1
and a.is_active = 1
and b.plant_id = 9
having `MUTASI KE` like '%KURIR,GUDANG CHATBOT%'
limit 50
";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-AGENT (OPEN MUTASI KURIR) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Nama Depo : " . $row['NAMA DEPO'] . "\n" .
          		"User ID : " . $row['USER ID'] . "\n" .
          		"Username : " . $row['USERNAME'] . "\n" .
          		"Nama Hawker :" . $row['NAMA HAWKER'] . "\n \n"
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

