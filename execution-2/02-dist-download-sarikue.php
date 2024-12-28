<?
// https://ariefsan.basproject.online/telegram/execution-2/02-dist-download-sarikue.php
exit();
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
#$json = json_decode($_POST['let2']);
#$bulan = $json[1];
#$sort = $json[2];

$tanggal_awal = date('Y-m-d', strtotime('-1 days'));
$today= date('Y-m-d');
$tanggal_po = date('Y-m-d', strtotime('2 days'));

$sql = "
	select 
upper(f.plant_name) as `NAMA PLANT`
, e.depo_code as `KODE DEPO`
, e.depo_name as `NAMA DEPO`
, a.suggest_order_date as `TANGGAL JUAL`
, b2.status_name as `ESTIMATION STATUS`
, case when c.sent_sap = 1 then 'SENT SAP' else 'NOT SENT SAP' end as `STATUS SENT SAP`
, ifnull(c.po_number,0) as `NOMOR PO`
-- , d.store_id as `STORE ID`
-- , d.store_name as `STORE NAME`
, g.product_code as `KODE PRODUCT` 
, g.product_name as `NAMA PRODUCT`
, g.short_name as `SHORT NAME`
, ifnull(sum(b.quantity),0) as `QTY PO`
, c.created as `CREATED TIME PO`
, ( select ifnull(sum(grd.qty_received),0) from goods_received gr 
		join goods_received_detail grd on grd.gr_id = gr.gr_id
		where gr.depo_id = a.depo_id
		and gr.received_date = a.suggest_order_date
		and gr.po_id = c.po_id
		and grd.product_id = b.product_id) as `QTY GR`
, (select ifnull(sum(bad.qty_taken),0) from allocation_salesman ba 
	 join allocation_salesman_detail bad on bad.allocation_id = ba.allocation_id
	 where ba.allocation_id > 7437180
	 and ba.po_number = c.po_number
	 and ba.estimate_order_number= a.estimate_order_number
	 and ba.depo_id = a.depo_id
	 and bad.salesman_id = a.salesman_id
	 and bad.product_id = b.product_id) as `QTY ALOKASI`
, (select ifnull(sum(tgd.qty_drop),0) from tagihan tg
	 join tagihan_detail tgd on tgd.tagihan_id = tg.tagihan_id
	 where tg.depo_id = a.depo_id
	 and tg.salesman_id = a.salesman_id
	 and tg.invoice_date = a.suggest_order_date
	 and tgd.product_id = b.product_id
	 and tg.status_id in ('2','3','4')) as `QTY DROP`
, (select ifnull(sum(tgd.qty_return_good),0) from tagihan tg
	 join tagihan_detail tgd on tgd.tagihan_id = tg.tagihan_id
	 where tg.depo_id = a.depo_id
	 and tg.salesman_id = a.salesman_id
	 and tg.invoice_date = a.suggest_order_date
	 and tgd.product_id = b.product_id
	 and tg.status_id in ('2','3','4')) as `QTY RETUR BAIK`
, (select ifnull(sum(tgd.qty_return_bs),0) from tagihan tg
	 join tagihan_detail tgd on tgd.tagihan_id = tg.tagihan_id
	 where tg.depo_id = a.depo_id
	 and tg.salesman_id = a.salesman_id
	 and tg.invoice_date = a.suggest_order_date
	 and tgd.product_id = b.product_id
	 and tg.status_id in ('2','3','4')) as `QTY RETUR BS`
from estimate_order a
left join estimate_order_detail b on b.estimate_order_id = a.estimate_order_id
left join estimate_order_status b2 on b2.status_id = a.status_id
left join purchase_order_estimate poe on poe.estimate_order_id = a.estimate_order_id 
join purchase_order c on c.po_id = poe.purchase_order_id 
left join purchase_order_status c3 on c3.status_id = c.status_id
left join store d on d.store_id = b.store_id
left join user_login d2 on d2.user_id = a.salesman_id
left join depo e on e.depo_id = a.depo_id 
left join plant f on f.plant_id = e.plant_id
left join product g on g.product_id = b.product_id
where 0=0
-- and a.estimate_order_id > 1230162
and e.owner_id <> 28
and a.suggest_order_date BETWEEN  '$tanggal_awal' and '$tanggal_po'
-- and d.store_type_id in ('732','733')
and b.product_id in ('118','72','109')
and a.status_id = 3
and c.sent_sap = 1
and b.quantity > 0
GROUP BY a.depo_id, a.suggest_order_date, b.product_id, c.po_id
ORDER BY a.depo_id,b.product_id
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
#echo mysqli_num_rows($exec);


$msg = "NAMA PLANT;KODE DEPO;NAMA DEPO;TANGGAL JUAL;ESTIMATION STATUS;STATUS SENT SAP;NOMOR PO;KODE PRODUCT;NAMA PRODUCT;SHORT NAME;QTY PO;CREATED TIME PO;QTY GR;QTY ALOKASI;QTY DROP;QTY RETUR BAIK; QTY RETUR BS \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .=  $row['NAMA PLANT'] . ";" .
          			$row['KODE DEPO'] . ";" .
          			$row['NAMA DEPO'] . ";" .
          			$row['TANGGAL JUAL'] . ";". 
          			$row['ESTIMATION STATUS'] . ";" .
          			$row['STATUS SENT SAP'] . ";'" .
          			$row['NOMOR PO'] . ";'" .
          			$row['KODE PRODUCT'] . ";" .
          			$row['NAMA PRODUCT'] . ";" .
          			$row['SHORT NAME'] . ";".
          			$row['QTY PO'] . ";" .
          			$row['CREATED TIME PO'] . ";" .
          			$row['QTY GR'] . ";" .
          			$row['QTY ALOKASI'] . ";" .
          			$row['QTY DROP'] . ";" .
          			$row['QTY RETUR BAIK'] . ";" .
          			$row['QTY RETUR BS'] . "; \n"
          ;
      }
  
  	# Taruh hasil query ke folder
  	file_put_contents("download/SARIKUE_PROGRAM_MITRA.csv", $msg);
    
  	$filePath = 'download/SARIKUE_PROGRAM_MITRA.csv';

    // Create CURL object
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendDocument?chat_id=-1001542755544");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);  

    // Create CURLFile
    $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
    $cFile = new CURLFile($filePath, $finfo);

    // Add CURLFile to CURL request
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "document" => $cFile
    ]);

    // Call
    $result = curl_exec($ch);
	
  	// Close
    curl_close($ch);
 

} 
