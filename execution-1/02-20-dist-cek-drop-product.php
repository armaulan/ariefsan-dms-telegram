<?
// https://ariefsan.basproject.online/telegram/execution-1/02-20-dist-cek-drop-product.php
require '../00-02-conn-dist.php';
//cek-store id 408430


$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$product = $json[1];
$salesman = $json[2];
$tanggal = $json[3];


$sql = "select e.username as `USERNAME`, 
a.dropping_number as `DROPPING NUMBER`,
d.store_id as `STORE ID`,
d.store_code as `STORE CODE`,
d.store_name as `STORE NAME`, 
c.product_code as `KODE PRODUCT`, 
c.product_name as `NAMA PRODUCT`,
b.qty_estimate as `QTY ESTIMATE`,
b.qty_drop as `QTY DROP`,
format(b.amount_cbp,0) as `TOTAL CBP`,
format(b.amount_discount,0) as `DISCOUNT`,
format(b.amount_rbp,0) as `TOTAL RBP`,
b.created as `CREATED TIME`
from dropping a 
join dropping_detail b on b.dropping_id = a.dropping_id
join product c on c.product_id = b.product_id
join store d on d.store_id = a.store_id
join user_login e on e.user_id = a.salesman_id
where c.product_code='$product'
and e.username = '$salesman'
and a.dropping_date = '$tanggal';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (Dropping Product) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Drop Number : " . $row['DROPPING NUMBER'] . "\n".
          		"Store ID : " . $row['STORE ID'] . "\n".
          		"Store Name : " . $row['STORE NAME'] . "\n".
          		"Qty Estimasi : " . $row['QTY ESTIMATE'] . "\n".
          		"Qty Drop : " . $row['QTY DROP'] . "\n".
          		"Created : " . $row['CREATED TIME'] . "\n \n"
                    ;
      }
    
    $ch = curl_init(); 

    // set url 
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
