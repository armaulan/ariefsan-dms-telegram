<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/03-agent-01-settlementdaily.php
require '../00-01-conn-agent.php';
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");
# $hari_final = "-". $hari . " days";
# $tanggal_awal = date('Y-m-d', strtotime($hari_final));
# $tanggal_akhir = date('Y-m-d');
# $sender = $_POST['let1'];
# $url = $_POST['let3'];

$json = json_decode($_POST['let2']);
$nomorGR = $json[1];


$sql = "select
c.depo_code as `KODE DEPO`
, c.depo_name as `NAMA DEPO`
, b.po_number as `NOMOR PO`
, b.po_date as `TANGGAL PO`
, b.selling_date as `TANGGAL PENJUALAN di PO`
, a.gr_number as `NOMOR GR`
, a.received_date as `TANGGAL DN`
, a.selling_date as `TANGGAL JUAL di GR`
, d.delivery_number as `NOMOR DN`
, a.total_qty_po as `QTY PO`
, a.total_qty_actual as `QTY TERIMA`
, (select sum(asd.qty_allocation) from allocation_salesman als 
   join allocation_salesman_detail asd on als.allocation_id=asd.allocation_id
   where als.gr_number=a.gr_number) as `QTY ALLOCATION`
, (select sum(asd.qty_taken) from allocation_salesman als 
  join allocation_salesman_detail asd on als.allocation_id=asd.allocation_id
  where als.gr_number=a.gr_number) as `QTY TAKEN`
, allo2.allocated as `DETAIL TAKEN`
, concat('Rp. ',format(a.total_amount,0)) as `CBP GR`
, e.status_name as `STATUS GR`
, a.created as `TANGGAL CREATED`
from goods_received a
left join purchase_order b on b.po_id=a.po_id
join depo c on c.depo_id=a.depo_id
left join e_delivery_number d on d.po_number=b.po_number
join goods_received_status e on e.status_id=a.status_id
left join (select 
    aa.gr_number
    , GROUP_CONCAT(aa.allocated_summary) allocated
    from (
        select 
        as2.gr_number
        , asd.salesman_id 
        , CONCAT(' ', ul.username, ' (alo : ', sum(asd.qty_allocation), ', taken : ',  sum(asd.qty_taken), ') ') as  allocated_summary
        , sum(asd.qty_allocation) allocated
        , sum(asd.qty_taken) taken
        from allocation_salesman as2 
        join allocation_salesman_detail asd on asd.allocation_id = as2.allocation_id  
        join user_login ul on ul.user_id = asd.salesman_id 
        where 0=0
        and as2.gr_number = '$nomorGR'
        group by asd.salesman_id 
        having allocated <> 0
    ) as aa) as allo2 on allo2.gr_number = a.gr_number
where a.gr_number='$nomorGR';
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#01-Agent (Cek GR) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Kode Depo: " . $row['KODE DEPO'] . "\n" .
          		"Nama Depo: " . $row['NAMA DEPO'] . "\n \n" .
                "Nomor PO:\n" . $row['NOMOR PO'] . "\n" .
                "Tanggal PO: " . $row['TANGGAL PO'] . "\n" .
          		"Tanggal Penjualan PO:" . $row['TANGGAL PENJUALAN di PO'] . "\n \n" .
          		"Nomor GR:\n" . $row['NOMOR GR'] . "\n" .
                "Tanggal DN: " . $row['TANGGAL DN'] . "\n" .
          		"Tanggal Jual di GR: " . $row['TANGGAL JUAL di GR'] . "\n" .
                "Nomor DN: " . $row['NOMOR DN'] . "\n" .
                "Qty PO: " . $row['QTY PO'] . "\n" .
                "Qty Terima: " . $row['QTY TERIMA'] . "\n" .
          		"Qty Allocation: " . $row['QTY ALLOCATION'] . "\n" .
          		"Qty Taken: " . $row['QTY TAKEN'] . "\n" .
                "Value CBP:  " . $row['CBP GR'] . "\n" .
          		"Status GR: " . $row['STATUS GR'] . "\n" .
                "Tanggal Created : \n" . $row['TANGGAL CREATED'] . "\n \n" .
          		"Detail Allocated : \n" . $row['DETAIL TAKEN'] . "\n \n" 
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
    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");
  	# $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");  
    # echo $output;
} 
