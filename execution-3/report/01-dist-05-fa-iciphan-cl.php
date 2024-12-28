<?php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-05-fa-iciphan-cl.php
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/fast-excel-writer/src/autoload.php';
require_once '../../library/fast-excel-helper/src/autoload.php';
use \avadim\FastExcelWriter\Excel;
$config = config();

$sql = "select 
*
from (
select 
'AR' as grouping
, 'TAGIHAN' as remarks
, CASE 
	when s.store_name like '%panjunan%' then 'PANJUNAN'
	when s.store_name like '%ramasurya%' then 'RAMASURYA'
	when s.store_name like '%panca pilar%' then 'PANCAPILAR'
	END as entity
, s.store_name
, t.invoice_number as doc
, t.invoice_date as tanggal
, sum(t.total_tagihan) as amount
from tagihan t 
join (select s.store_id
	from store s 
	where 0=0
	and s.store_id >= 460592
	and (s.store_name like '%panjunan%' or s.store_name like '%RAMASURYA%' or s.store_name like '%panca pilar%')
	) as a on a.store_id = t.store_id
left join store s on s.store_id = t.store_id 
where t.status_id = 2
group by entity, doc
union
select 
'AR' as grouping
, 'DROPPING' as remarks
, CASE 
	when s.store_name like '%panjunan%' then 'PANJUNAN'
	when s.store_name like '%ramasurya%' then 'RAMASURYA'
	when s.store_name like '%panca pilar%' then 'PANCAPILAR'
	END as entity
, s.store_name 
, d.dropping_number as doc
, d.dropping_date as tanggal
, sum(d.total_rbp) as amount
from dropping d 
join (select s.store_id
	from store s 
	where 0=0
	and s.store_id >= 460592
	and (s.store_name like '%panjunan%' or s.store_name like '%RAMASURYA%' or s.store_name like '%panca pilar%')
	) as a on a.store_id = d.store_id
left join store s on s.store_id = d.store_id 
where d.status_id = 1
group by entity, doc
union
SELECT 
'PO' as grouping
, 'PO' as remarks
, CASE 
	when s.store_name like '%panjunan%' then 'PANJUNAN'
	when s.store_name like '%ramasurya%' then 'RAMASURYA'
	when s.store_name like '%panca pilar%' then 'PANCAPILAR'
	END as entity
, s.store_name
, aa.po_number 
, aa.suggest_order_date as tanggal
, sum(rbp) as amount
from (
select 
eod.store_id 
, po.po_number 
, eod.product_id 
, eod.quantity 
, eod.price_per_pcs
, eod.price 
, eod.store_discount_amount 
, eod.price_per_pcs * eod.store_discount_amount as total_discount
, eod.price - (eod.price_per_pcs * eod.store_discount_amount) as rbp
, eo.suggest_order_date
, CURRENT_DATE() as curdate 
, IFNULL(gr.gr_number,0) as gr
, IFNULL((select IF(d2.dropping_id > 0, 1, 0) from dropping d2 where d2.dropping_date = CURDATE() and d2.store_id = eod.store_id limit 1),0) as is_dropped
from estimate_order eo
join estimate_order_detail eod on eod.estimate_order_id = eo.estimate_order_id
join (select d.depo_id  from depo d where d.depo_name like '%dc ro%') as bb on bb.depo_id = eo.depo_id 
join (select s.store_id
	from store s 
	where 0=0
	and s.store_id >= 460592
	and (s.store_name like '%panjunan%' or s.store_name like '%RAMASURYA%' or s.store_name like '%panca pilar%')
	) as a on a.store_id = eod.store_id
join purchase_order_estimate poe on poe.estimate_order_id = eo.estimate_order_id 
join purchase_order po on po.po_id = poe.purchase_order_id 
left join goods_received gr on gr.po_id = po.po_id 
where 0=0
and eo.estimate_order_id >= 2740000
and eo.suggest_order_date >= CURRENT_DATE()
and eo.status_id = 3
and eod.quantity > 0
having suggest_order_date > curdate
or (suggest_order_date = curdate and is_dropped = 0)
) as aa
left join store s on s.store_id = aa.store_id
group by aa.store_id , po_number
) as aaa 
order by aaa.entity, aaa.grouping, aaa.tanggal, grouping
;";

# Create a connection
$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

# fetch data entity
$entityArray = [];

# fetch main data , and put into object
$entityObj = [];

# Data iteration and write into excel
if(mysqli_num_rows($exec) > 0) {
    
    $tempEntity = "";
    
	while($row= mysqli_fetch_assoc($exec)) {
	    
	    if($tempEntity == ""){
	        
	        # Put data entity into temporary
	        $tempEntity = $row["entity"];
	        
	        # Pop data entity into array
	        $entityArray[] = $row["entity"];
	        
	        # Create key entity into object variable
	        $entityObj[$row["entity"]] = [];
	        
	        # If row data looping is in AE
	        if($row["grouping"] == "AR") {
	            
	            $entityObj[$row["entity"]]["AR"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["AR_COUNT"] = 1;
	            $entityObj[$row["entity"]]["PO_COUNT"] = 0;
	            
	        } else if ($row["grouping"] == "PO"){
	            $entityObj[$row["entity"]]["PO"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["AR_COUNT"] = 0;
	            $entityObj[$row["entity"]]["PO_COUNT"] = 1;
	        }
	            
	        
	    } else if ($tempEntity == $row["entity"]){
	        
	        if($row["grouping"] == "AR") {
	            $entityObj[$row["entity"]]["AR"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["AR_COUNT"] ++;
	            
	        } else if ($row["grouping"] == "PO"){
	            $entityObj[$row["entity"]]["PO"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["PO_COUNT"] ++;
	        }
	        
	    } else {
	        $tempEntity = $row["entity"];
	        $entityArray[] = $row["entity"];
	        $entityObj[$row["entity"]] = [];
	        
	        if($row["grouping"] == "AR") {
	            $entityObj[$row["entity"]]["AR"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["AR_COUNT"] = 1;
	            $entityObj[$row["entity"]]["PO_COUNT"] = 0;
	            
	        } else if ($row["grouping"] == "PO"){
	            $entityObj[$row["entity"]]["PO"][] = [$row["doc"], $row["store_name"], $row["tanggal"], $row["amount"]];
	            $entityObj[$row["entity"]]["AR_COUNT"] = 0;
	            $entityObj[$row["entity"]]["PO_COUNT"] = 1;
	        }
	        
	    }
	}
}


$excel = Excel::create($entityArray);

$count = 0;
foreach($entityArray as $item) {
    
    $sheet = $excel->sheet($item);
    $sheet->setColWidth(['A', 'B', 'C', 'D', 'E'], 'auto');
    $sheet->setColFormat('D', '#,##0');
    $sheet->setDefaultFontSize(9);
    
    $style = [
        'font-color' => '#FFFFFF',
        'font-style' => 'bold',
        'fill-color' => '#000066'
    ];
    
    # Header untuk judul entity
    $sheet->writeTo('A1', $item)->setStyle('A1', $style);
    $waktu = date("Y-m-d H:i:s");
    $sheet->writeTo('B1', $waktu)->setStyle('B1', $style);
    
    # Jika AR Ada
    if(isset($entityObj[$item]['AR'])){
        # Tulis sub judul menjadi AR
        $sheet->writeTo('A3', 'AR');
        
        # Apply Data AR
        $sheet->writeArrayTo('A4', $entityObj[$item]['AR']);
        
        # Prepare cell for summary
        $cellArStart = "D4";
        $cellArEnd = $entityObj[$item]['AR_COUNT'] + 3;
        $cellArResult = $cellArEnd + 1;
        
        $sheet->writeTo("D$cellArResult", "=SUM($cellArStart:D$cellArEnd)")->applyFontStyleBold();
        
        if(isset($entityObj[$item]['PO'])){
            $cellPoHeader = $cellArResult + 2;
            $cellPoLine = $cellPoHeader + 1;       
            $sheet->writeTo("A$cellPoHeader", 'PO');
            $sheet->writeArrayTo("A$cellPoLine", $entityObj[$item]['PO']);
            
            # Prepare cell for summary
            $cellPoStart = "D$cellPoLine";
            $cellPoEnd = $entityObj[$item]['PO_COUNT'] + $cellPoLine - 1;
            $cellPoResult = $cellPoEnd + 1;
            
            $sheet->writeTo("D$cellPoResult", "=SUM($cellPoStart:D$cellPoEnd)")->applyFontStyleBold();
            
            $cellTotalResult = $cellPoResult + 2;
            
            $sheet->writeTo("C$cellTotalResult", "TOTAL");
            $sheet->writeTo("D$cellTotalResult", "=SUM(D$cellArResult+D$cellPoResult)")->applyFontStyleBold();
        
        }
        
        
    } else if(isset($entityObj[$item]['PO'])){
        
        $sheet->writeTo('A3', 'PO'); 
        
        # Apply Data PO
        $sheet->writeArrayTo('A4', $entityObj[$item]['PO']);
        
        # Prepare cell for summary
        $cellPoStart = "D4";
        $cellPoEnd = $entityObj[$item]['PO_COUNT'] + 3;
        $cellPoResult = $cellPoEnd + 1;
        
        $sheet->writeTo("D$cellPoResult", "=SUM($cellPoStart:D$cellPoEnd)")->applyFontStyleBold();
    }
    
    
}

# $excel->save("iciphan.xlsx");

# Preparing excel file directory
$filename = 'download/' . substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_ichipan.xlsx";

# Save file excel into file directory
$excel->save($filename);

# Send file throught whatsapp
if (0 == 0) {
  $config["whatsappSendMessage"]($config['key-wa-bas'],  "CL Ichipan", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
  $config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "My Lovely", "", "https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/". $filename);
  #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "", "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $zipname);
}

# Delete file in directory
unlink($filename);

exit();







# Create Excel file
# $excel = Excel::create(['DATA', 'BUDI']);

# Create a sheet
# $sheet = $excel->sheet('DATA');

# $sheet->writeRow(["test", 123]);

# $excel->save("test.xlsx");