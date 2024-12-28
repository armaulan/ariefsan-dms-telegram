<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/03-dist-09-IciphanStore.php
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

$sql = "select
d.depo_code 
, d.depo_name 
, s.store_id 
, s.store_code 
, s.store_name 
, s.created 
, if(s.is_active= 1, 'Aktif','Not Aktif') as `Status Toko`
, TIMEDIFF(CURRENT_TIMESTAMP(), s.created) timediff
from store s  
left join depo d on d.depo_id = s.depo_id 
where 0=0
and s.store_type_id in (845, 846, 847, 848, 849, 850, 860)
having timediff < '8:00:00'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);

$msg = "*Store Ichipan dan Uenak* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['depo_code'] . "\n" .
                "Nama Depo:" . $row['depo_name'] . "\n" .
                "Store ID: " . $row['store_id'] . "\n" .
          		"Store Code: " . $row['store_code'] . "\n" .
          		"Store Name: " . $row['store_name'] . "\n" .
          		"Status Toko: " . $row['Status Toko'] . "\n" .
          		"Created Date: " . $row['created'] . "\n \n" 
                ;
      }
    
    # menampilkan hasil curl
  	# $config["whacenterSendMessage"]($config['key-whacenter-1'], '085' , $msg )
    # $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], 'Crew BAS', $msg );
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-crew'], "true");
  	  
} else {
    file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=no data");
}
