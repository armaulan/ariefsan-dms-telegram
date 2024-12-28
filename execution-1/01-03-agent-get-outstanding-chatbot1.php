<?
// https://ariefsan.basproject.online/telegram/execution-1/01-03-agent-get-outstanding-chatbot1.php

# Buat Connection ke database agent
require '../00-01-conn-agent.php';

#Persiapan Data, yang dikirim oleh webhook gate
$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$end_date = date('Y-m-d');

#Pemisahan data untuk parameter condition
$plant = strtolower($json[1]); // ambil plant dari message telegram

$plantMapping = array(
  
  "ckd" => "Cikande",
  "cbt" => "Cibitung",
  "pwk" => "Purwakarta",
  "smg" => "Semarang",
  "psr" => "Pasuruan",
  "cku" => "Cikarang u",
  "ckw" => "Cikarang w",
  "plb" => "Palembang",
  "mdn" => "Medan",
  "mks" => "Makassar",
  "bjm" => "Banjarmasin"

);

$plantSql = $plantMapping[$plant];

# Persiapan Script SQL
$sql = "
select 
p.plant_name 
, d.depo_name 
, dt.tanggal as delivery_date
, IFNULL(aa.countCreate, 0) as order_create
, IFNULL(bb.countAllocated, 0) as order_allocated
, IFNULL(aa.countCreate, 0) + IFNULL(bb.countAllocated, 0) as total_outstanding
from depo d 
join dates dt 
join plant p on p.plant_id = d.plant_id 
left join (select d.depo_id 
		, aod.delivery_date
		, count(aod.order_id) as countCreate
		from agent_order_data aod 
		join depo d on d.depo_id = aod.depo_id 
		join plant p on p.plant_id = d.plant_id 
		where 0=0
		and aod.is_active = 1
		and aod.delivery_date <= '$end_date'
		-- and aod.depo_id = 349
		and p.plant_name = '$plantSql'
		and aod.order_status_id in (1)
		group by d.depo_id, aod.delivery_date  ) aa on aa.depo_id = d.depo_id and aa.delivery_date = dt.tanggal
left join (select d.depo_id 
		, aod.delivery_date
		, count(aod.order_id) as countAllocated
		from agent_order_data aod 
		join depo d on d.depo_id = aod.depo_id 
		join plant p on p.plant_id = d.plant_id 
		where 0=0
		and aod.is_active = 1
		and aod.delivery_date <= '$end_date'
		-- and aod.depo_id = 349
		and p.plant_name = '$plantSql'
		and aod.order_status_id in (2)
		group by d.depo_id, aod.delivery_date  ) bb on bb.depo_id = d.depo_id and bb.delivery_date = dt.tanggal
where 0=0
and d.owner_id = 28
and p.plant_name = '$plantSql'
and dt.tanggal in (select 
distinct aod.delivery_date from agent_order_data aod
join depo d on d.depo_id = aod.depo_id 
join plant p on p.plant_id = d.plant_id 
where 0=0
and p.plant_name = '$plantSql'
and aod.order_status_id  in (1,2)
and aod.is_active = 1
and aod.delivery_date <= '$plantSql')
having total_outstanding <> 0
order by dt.tanggal DESC 

";



# Lakukan Run Script SQL ke database agent
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
// echo mysqli_num_rows($exec);

# Siapkan dahulu variable kosong untuk menampung pesan feedback ke telegram
$msg = "";

# Siapkan dahulu variable kosong untuk Total Order dari semua plant
$totalOrder = 0;


# Jika hasil run script SQL punya row 1 atau lebih maka
if(mysqli_num_rows($exec) > 0) {
    
  	# setiap row hasil run script sql, kita masukan/tampung dahulu ke variable $msg, untuk siap2 dikirim ke telegarm
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .=  $row['delivery_date'] . " (Tanggal Kirim) \n" .
          		 "" . substr($row['depo_name'], 6) . "\n" .
          		 "Crt : " . $row['order_create'] . ", " .
          		 "Allo: " . $row['order_allocated'] . "\n \n"
          		
          		
          
          ;
		$totalOrder = $totalOrder + $row['total_outstanding'];
      } 

	$msg .= "Total " . $totalOrder;
  
    $ch = curl_init(); 

    // set url 
  	# kirim $msg ke telegram
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    // echo $output;
}  else {

    	    $ch = curl_init(); 

          // set url 
          # kirim $msg ke telegram
          curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" . $sender . "&text=" . urlencode("No Outstanding Found"));

          // return the transfer as a string 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

          // $output contains the output string 
          $output = curl_exec($ch); 

          // tutup curl 
          curl_close($ch);      

          // menampilkan hasil curl
          exit();
      

}
