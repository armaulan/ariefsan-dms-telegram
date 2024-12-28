<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-08-StoretypeStudent.php
exit();
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();

$sql = "select
p.plant_name as `plant`
, if(d.owner_id=28,'INN','MITRA') as `channel`
, d.depo_code as `depo code`
, d.depo_name as `depo name`
, s.store_id as `store id`
, s.store_code as `store code`
, s.store_name as `store name`
, st.store_type_name as `store type`
, sc.classification_name as `store class`
, IFNULL(s.create_date, '') as `created_date`
, IFNULL(s.last_visited, '') as `last_visited`
, (select max(po.selling_date) from purchase_order po where po.depo_id=d.depo_id) as `last_po_depo`
from store s
join depo d on d.depo_id = s.depo_id
join plant p on p.plant_id = d.plant_id
join store_type st on st.store_type_id = s.store_type_id
join store_classification sc on sc.classification_id = s.classification_id
where 0=0
and s.is_active = 1
and s.store_type_id in (720,721)
and d.owner_id not in (2,1,8,9);
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*02-Dist (Store Type Student)* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Plant: " . $row['plant'] . "\n" .
          		"Kode Depo: " . $row['depo code'] . "\n" .
                "Nama Depo:" . $row['depo name'] . "\n" .
                "Store ID: " . $row['store id'] . "\n" .
          		"Store Code: " . $row['store code'] . "\n" .
          		"Store Name: " . $row['store name'] . "\n" .
          		"Store Type:" . $row['store type'] . "\n" .
          		"Created Date: " . $row['created date'] . "\n \n" 
    ;
      }
    
     

    // menampilkan hasil curl
  	# $config["whacenterSendMessage"]($config['key-whacenter-1'], '085' , $msg )
    $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], 'Crew BAS', $msg );
    # $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");
  	  
  	} 
