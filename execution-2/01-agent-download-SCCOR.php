<?
// https://ariefsan.basproject.online/telegram/execution-2/01-agent-download-SCCOR.php
exit();
require '../00-01-conn-agent.php';

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
, d2.user_id as `HAWKER ID`
, d2.name as `HAWKER NAME`
, g.product_code as `KODE PRODUCT` 
, g.product_name as `NAMA PRODUCT`
, g.short_name as `SHORT PRODUCT`
, ifnull(b.quantity,0) as `QTY PO`
, c.created as `CREATED TIME PO`
/*, (select ifnull(sum(isd.quantity),0) from inventory_salesman_detail isd
	 where isd.depo_id = a.depo_id
	 and isd.salesman_id = a.salesman_id
	 and isd.tanggal_transaksi = a.suggest_order_date
	 and isd.product_id = b.product_id
	 and isd.transaction_type like 'al%') as `QTY ALOKASI`*/
, (select ifnull(sum(bad.qty_taken),0) from allocation_salesman ba 
	 join allocation_salesman_detail bad on bad.allocation_id = ba.allocation_id
	 where 0=0
	 and ba.allocation_id > 2497724
	 and ba.po_number = c.po_number 
	 and ba.estimate_order_number = a.estimate_order_number
	 and ba.depo_id = a.depo_id
	 and bad.salesman_id = a.salesman_id
	 and bad.product_id = b.product_id
	 ) as `QTY ALOKASI`
, (select ifnull(sum(asd.qty_drop),0) from agent_selling as2 
	 join agent_selling_detail asd on asd.selling_id = as2.selling_id
	 where as2.depo_id= a.depo_id
	 and as2.user_id= a.salesman_id
	 and as2.tanggal_selling = a.suggest_order_date
	 and asd.product_id = b.product_id
	 and as2.status_id <> 5) as `QTY SELLING`
, (select ifnull(sum(rcrd.quantity),0) from report_convert_retur_header rcrh
	 join report_convert_retur_detail rcrd on rcrd.report_id = rcrh.report_id
	 join penerimaan_kasir pk on pk.penerimaan_kasir_number = rcrh.penerimaan_kasir_number
	 where rcrh.salesman_id= a.salesman_id
	 and rcrh.depo_id = a.depo_id
	 and rcrd.product_id = b.product_id
	 and pk.penerimaan_date = a.suggest_order_date) as `QTY RETURN`
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
and a.estimate_order_id > 9100100
and a.suggest_order_date BETWEEN  '$tanggal_awal' and '$tanggal_po'
and d2.salesman_type_id in ('6','7')
and b.product_id = 118
and a.status_id = 3
and c.sent_sap = 1
and b.quantity > 0
;
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
#echo mysqli_num_rows($exec);


$msg = "NAMA PLANT;KODE DEPO;NAMA DEPO;TANGGAL JUAL;ESTIMATION STATUS;STATUS SENT SAP;NOMOR PO;HAWKER ID;HAWKER NAME;KODE PRODUCT;NAMA PRODUCT;SHORT PRODUCT;QTY PO;CREATED TIME PO;QTY ALOKASI;QTY SELLING;QTY RETURN \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .=  $row['NAMA PLANT'] . ";" .
          			$row['KODE DEPO'] . ";" .
          			$row['NAMA DEPO'] . ";" .
          			$row['TANGGAL JUAL'] . ";". 
          			$row['ESTIMATION STATUS'] . ";" .
          			$row['STATUS SENT SAP'] . ";'" .
          			$row['NOMOR PO'] . ";'" .
          			$row['HAWKER ID'] . ";".
          			$row['HAWKER NAME'] . ";" .
          			$row['KODE PRODUCT'] . ";" .
          			$row['NAMA PRODUCT'] . ";" .
          			$row['SHORT PRODUCT'] . ";".
          			$row['QTY PO'] . ";" .
          			$row['CREATED TIME PO'] . ";" .
          			$row['QTY ALOKASI'] . ";" .
          			$row['QTY SELLING'] . ";" .
          			$row['QTY RETURN'] . "; \n"
          ;
      }
  
  	# Taruh hasil query ke folder
  	file_put_contents("download/SCCOR_PROGRAM_AGENT.csv", $msg);
    
  	$filePath = 'download/SCCOR_PROGRAM_AGENT.csv';

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
