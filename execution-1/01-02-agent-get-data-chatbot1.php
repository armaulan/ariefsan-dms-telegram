<?
// https://ariefsan.basproject.online/telegram/execution-1/01-02-agent-get-data-chatbot1.php

# Buat Connection ke database agent
require '../00-01-conn-agent.php';

#Persiapan Data, yang dikirim oleh webhook gate
$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);

#Pemisahan data untuk parameter condition
$tanggal = $json[1]; // ambil data tanggal yang diinput dari telegram
// $tanggal = "2022-05-30"; // ambil data tanggal yang diinput dari telegram

# Persiapan Script SQL
$sql = "
      select 
      c.plant_name as NAMA_PLANT,
      COUNT(DISTINCT a.order_id) as TOTAL_ORDER
      /* (select COUNT(DISTINCT a1.order_id) from agent_order_data a1 where a1.order_date=a.order_date 
      and a1.is_active=a.is_active
      and a1.order_status_id<>'4') AS SUBTOTAL */
      from agent_order_data a
      left join depo b on b.depo_id=a.depo_id
      left join plant c on c.plant_id=b.plant_id
      where 0=0
      and a.order_date = '$tanggal'
      and a.is_active = '1'
      and a.order_status_id <> '4'
      and c.plant_id in (1,2,3,4,5,6,7,8,9,10,11,12,13,14)
      GROUP BY c.plant_name ASC
      order by total_order desc
";



# Lakukan Run Script SQL ke database agent
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
// echo mysqli_num_rows($exec);

# Siapkan dahulu variable kosong untuk menampung pesan feedback ke telegram
$msg = "Order Only Active/Success Payment di DMS ".$tanggal." \n \n";

# Siapkan dahulu variable kosong untuk Total Order dari semua plant
$totalOrder = 0;


# Jika hasil run script SQL punya row 1 atau lebih maka
if(mysqli_num_rows($exec) > 0) {
    
  	# setiap row hasil run script sql, kita masukan/tampung dahulu ke variable $msg, untuk siap2 dikirim ke telegarm
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .=  $row['NAMA_PLANT'] . " : " . $row['TOTAL_ORDER'] . "\n";
		$totalOrder = $totalOrder + $row['TOTAL_ORDER'];
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
    echo $output;
} 
