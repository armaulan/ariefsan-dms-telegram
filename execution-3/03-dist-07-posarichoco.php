<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-dist-07-posarichoco.php
exit();
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
# $nomorGR = $json[1];


$sql = "select p2.plant_name as `plant`
, d.depo_code as `depo code`
, d.depo_name as `depo name`
, po.po_number as `po number`
, po.created as `create po`
, po.selling_date as `tanggal selling`
, pod.product_id as `product id`
, p.product_code as `product code`
, p.product_name as `product name`
, pod.quantity as `quantity`
, if(po.sent_sap=1,'SENT','NOT SENT') as `status po`
from purchase_order po 
join purchase_order_detail pod on po.po_id = pod.po_id 
join product p on p.product_id = pod.product_id 
join depo d on d.depo_id = po.depo_id 
join plant p2 on p2.plant_id = po.plant_id 
where pod.product_id in ('215','216','217','218')
and po.selling_date > '2023-06-12'
and pod.quantity > 1
and po.sent_sap = 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*02-Dist (Order Sari Choco)* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Plant: " . $row['plant'] . "\n" .
          		"Kode Depo: " . $row['depo code'] . "\n" .
                "Nama Depo:" . $row['depo name'] . "\n" .
                "PO Number: " . $row['po number'] . "\n" .
          		"Create PO:" . $row['create po'] . "\n" .
          		"Tanggal Selling:" . $row['tanggal selling'] . "\n" .
          		"Status PO: " . $row['status po'] . "\n \n" 
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
  	# $config["whacenterSendMessage"]($config['key-whacenter-1'], '085' , $msg )
    $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], 'Crew BAS', $msg );
    # $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");
  	# $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");  
    # echo $output;
  
  	} 
