<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/agent/agent-03-chatbot-get-order.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$order_number = $json[1];
#$cutdate = $json[2];

# Ambil data nomor order yang diketik di telegram
#$order_number = $json[1]; // "517-d1e880-9657"
#$sender = "-665646233r";
#$order_number = "517-d1e880-9657";



# Persiapan Script SQL, Ambil informasi header
    $sql = "

    select 
    aod.order_number 
    , d.depo_name 
    , aod.order_date 
    , aod.delivery_date 
    , if(aod.is_cashless = 0, 'COD', 'CASHLESS') payment_type
    , if(aod.is_active = 1, 'APPROVED', 'NOT ACTIVED') as payment_status
    , aod.nama_penerima
    , aod.no_telp 
    , CONCAT(REPLACE(LEFT(aod.no_telp , 1), '0', 'wa.me/62'), SUBSTRING(aod.no_telp , 2, CHAR_LENGTH(aod.no_telp))) as phone
    , CONCAT(REPLACE(LEFT(aoc.no_telp , 1), '0', 'wa.me/62'), SUBSTRING(aoc.no_telp , 2, CHAR_LENGTH(aoc.no_telp))) as phone2
    , aos.order_status_name 
    -- , aod.kelurahan 
    , format(aod.total_cbp, 0) as total_cbp
    , aod.alamat_pengiriman
    , aod.total_qty 
    , aod.total_product 
    , aod.notes
    , aod.created
    , aod.voucher
    , aod.modified 
    , ul.name 
    , ul.username
    , rp.photo_path
    , aot.order_tipe_name 
    , format(IFNULL(aod.delivery_fee, 0), 0) as delivery_fee 
    , format((aod.total_cbp + IFNULL(aod.delivery_fee, 0)), 0) as grand_total
    , apt.payment_type_name
    from agent_order_data aod
    left join agent_order_status aos on aos.order_status_id = aod.order_status_id 
    left join depo d on d.depo_id = aod.depo_id 
    left join user_login ul on ul.user_id = aod.assign_to
    left join agent_order_selling aos2 on aos2.order_number = aod.order_number 
    left join report_photo rp on rp.report_id = aos2.selling_number 
    left join agent_order_customer aoc on aoc.customer_id = aod.customer_id
    left join agent_order_tipe aot on aot.order_tipe_id = aod.order_tipe_id 
    left join agent_payment_type apt on apt.payment_type_id = aod.payment_type_id 
    where 0=0
    and aod.order_number like '%$order_number%'
    limit 5
    ;
    
    ";
    
# Persiapan Script SQL, Ambil informasi Detail Item
    $sql2 = "
    
       select aodd.order_id
       , p.short_name 
       , FORMAT(aodd.cbp, 0) as cbp 
       , aodd.qty 
       , FORMAT(aodd.total_cbp, 0) as total_cbp
       -- , aod.notes_vendor 
       from agent_order_data_detail aodd
       left join agent_order_data aod on aod.order_id = aodd.order_id
       left join product p on p.product_id = aodd.product_id 
       where 0=0
       and aod.order_number like '%$order_number%'
       -- and aod.order_number like '%sari-SO-20211228-5efc78-0425%'
       limit 20
       ;
    
    ";


# Lakukan Run Script SQL ke database agent
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$exec2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
// echo mysqli_num_rows($exec);


$fetch_query = function($query_result) {
    $data = array();
    while($row = mysqli_fetch_array($query_result)) {
	    $data[] = $row;
    }
    // file_get_contents($sendMsgArya . $dataChatIdSender . "&text=row57");
    return $data;
};

$data_query = $fetch_query($exec);
    // var_dump($data_query);
    
$data_query2 = $fetch_query($exec2);
    // var_dump($data_query);


$final_exec = function($data_query, $data_query2, $config) {
    if(! isset($data_query[0])) {
        exit();
    } else {
        $txt = "";
        
        foreach($data_query as $item) {
            // $depo = $item['depo_name'];
            // $user = $item['username'];
            $txt .=  $item['order_number'] . "\n" 
                    . "Status : " . $item['order_status_name'] . " | ". $item['modified'] . "\n \n"
                    . "Nama : " . $item['nama_penerima'] . "\n"
                    . "Depo : " . $item['depo_name'] . "\n"
                    . "Tipe Source : " . $item['order_tipe_name'] . "\n"
                    . "Tgl Order : " . $item['order_date'] . "\n" 
                    . "Tgl Kirim : " . $item['delivery_date'] . "\n" 
                    . "Chatbot to DMS : \n" . $item['created'] . "\n"
                    . "Payment Type : " . $item['payment_type'] . " - ". $item['payment_type_name'] . "\n"
                    . "Payment Status : " . $item['payment_status'] . "\n"
                    . "Phone Pemesan : \n" . $item['phone2'] . "\n" 
                    . "Phone Penerima : \n" . $item['phone'] . "\n" 
                    . "Voucher : " . $item['voucher'] . "\n"
                    . "Photo Kirim : \n" . $item['photo_path'] . "\n"
                    . "PIC : " . $item['name'] . " (" . $item['username'] . ") \n\n"
                    . "Addres : " . $item['alamat_pengiriman'] . "\n\n" 
                    . "Note : " . $item['notes'] . "\n\n" 
                    . "Total Qty : " . $item['total_qty'] . "\n"
                    . "Total CBP : " . $item['total_cbp'] . "\n"
                    . "Delivery Fee : " . $item['delivery_fee'] . "\n"
                    . "Grand Total : " . $item['grand_total'] . "\n \n"
                    ;
        }
        
        foreach($data_query2 as $item) {
            // $depo = $item['depo_name'];
            // $user = $item['username'];
            $txt .=   $item['short_name'] . " | @" 
                    . $item['cbp'] . " | "
                    . $item['qty'] . "Pcs | "
                    . $item['total_cbp'] . " \n"
                    ;
                // echo $date;
        }
        
        
        $config["whatsappSendMessage"]($config['key-wa-bas'],  $txt , $config['id-etrademode'], "true");
        #$ch = curl_init(); 

    	// set url 
  		# kirim $msg ke telegram
    	# curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=" .$sender. "&text=" . urlencode($txt));

    	// return the transfer as a string 
    	# curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    	// $output contains the output string 
    	# $output = curl_exec($ch); 

    	// tutup curl 
    	# curl_close($ch);      

    	// menampilkan hasil curl
    	// echo $output;
	}  
};

$final_exec($data_query, $data_query2, $config);