<?php
#https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/report/01-dist-08-invoice.php
#file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=01-dist-06-sales-mo-inn.php");
require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
require_once '../../library/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
$config = config();

$dropping = "";
$invoicing = "";
$depo = "";
$salesman = "";
$tipe = "";
$store = "";
$totalQty = 0;
$totalNet = 0;
$totalnet2 = 0;
$ppn = 0;
$signPath = "";
$depoId = 0;
$dynamicProduct = "";
$invoice_number = "507215611022112910162252";

$sql = "select 
CONCAT(t.dropping_number, ' -- ',  t.dropping_date) as dropping
, CONCAT(t.invoice_number, ' -- ',  t.invoice_date) as invoice
, CONCAT(d.depo_code, ' -- ',  d.depo_name) as depo
, CONCAT(s.store_code, ' -- ',  s.store_name) as store
, ul.name 
, (select if(dgb.store_sales_type_id=1, 'Consigment', 'Non Consigment') from dropping_group_brand dgb join dropping d2 on d2.dropping_id=dgb.dropping_id where d2.dropping_number=t.dropping_number limit 1) tipe
, (select ts.signature_path from tagihan_signature ts where ts.invoice_number=t.invoice_number limit 1 ) as sign
, t.depo_id
from tagihan t
join depo d on d.depo_id = t.depo_id 
join user_login ul on ul.user_id = t.salesman_id  
join store s on s.store_id = t.store_id 
where 0=0
and t.invoice_number = '$invoice_number'
";

$sql2 = "select 
CONCAT(p.product_code, ' - ',  p.short_name  ) as product
, td.qty_drop - td.qty_return_bs  - td.qty_return_good as qty
, format(round(if(t.depo_id = 370, td.rbp , (td.rbp / 1.11)),0),0) as price
, format(round(if(t.depo_id = 370, (round((td.qty_drop - td.qty_return_bs  - td.qty_return_good) * td.rbp)) , (round((td.qty_drop - td.qty_return_bs  - td.qty_return_good) * td.rbp / 1.11,0))),0),0) as amount_price
, round(if(t.depo_id = 370, td.rbp , (td.rbp / 1.11)),0) as price2
, round(if(t.depo_id = 370, (round((td.qty_drop - td.qty_return_bs  - td.qty_return_good) * td.rbp)) , (round((td.qty_drop - td.qty_return_bs  - td.qty_return_good) * td.rbp / 1.11,0))),0) as amount_price2
from tagihan t 
join tagihan_detail td on td.tagihan_id = t.tagihan_id 
join product p on p.product_id = td.product_id 
where 0=0
and t.invoice_number = '$invoice_number'
having qty > 0
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$exec2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));

if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	$dropping = $row['dropping'];
      	$invoicing = $row['invoice'];
      	$depo = $row['depo'];
      	$salesman = $row['name'];
      	$tipe = $row['tipe'];
      	$store = $row['store'];
      	$signPath = $row['sign'];
      	$depoId = $row['depo_id'];
      }
}

if(mysqli_num_rows($exec2) > 0) {
    
    $num = 0;
	while($row= mysqli_fetch_array($exec2)) {
	    $product = $row['product'];
	    $qty = $row['qty'];
	    $price = $row['price'];
	    $amount_price = $row['amount_price'];
	    $num++;
	    $totalQty += $qty;
	    $totalNet += $row['amount_price2'];
	    
        $dynamicProduct .= "
        <tr>
            <td style='text-align: center;'>$num</td>
            <td>$product</td>
            <td style='text-align: center;'>$qty</td>
            <td style='text-align: right;'>$price</td>
            <td style='text-align: right;'>$amount_price</td>
        </tr>
        ";
        
      }
}

$html = "
<div>
<table border='0.5' style='width: 100%; border-collapse: collapse;' >
    <tr>
        <td style='color: darkblue; font-size: 20px;'><b>NOTA INVOICE</b></td>
        <td style='text-align: right;' ><b>PT. INDOSARI NIAGA NUSANTARA</b><br><b>900645920077000</b></td>
    </tr>
</table>
</div>
<br>
";

$html .= "
<div style='font-size: 13px;'>
<table border='0.5' style='width: 100%; border-collapse: collapse;' >
    <tr>
        <td>
            <table table border='0'>
                <tr>
                    <td>Dropping</td><td>: $dropping</td>
                </tr>
                <tr>
                    <td>Invoicing</td><td>: $invoicing</td>
                </tr>
                <tr>
                    <td>Depo</td><td>: $depo</td>
                </tr>
                <tr>
                    <td>Store</td><td>: $store</td>
                </tr>
                <tr>
                    <td>Salesman</td><td>: $salesman</td>
                </tr>
                <tr>
                    <td>Payment Type</td><td>: $tipe</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</div><br>
";

$html .= "
<div style='font-size: 13px;'>
<table border='0.5' style='width: 100%; border-collapse: collapse; padding: 3px;' >
    <tr>
        <th>No</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Amount Price</th>
    </tr>
";

$html .= $dynamicProduct;

if($depoId == 370) {
    $totalnet2 = number_format($totalNet, 0, '.', ',');
    $totalNet = number_format($totalNet, 0, '.', ',');
} else {
    $ppn = floor($totalNet * 0.11);
    $totalnet2 = number_format($ppn + $totalNet, 0, '.', ',');
    $totalNet = number_format($totalNet, 0, '.', ',');
    $ppn = number_format($ppn, 0, '.', ',');
}


$html .= "
    <tr>
        <td rowspan='3' colspan='3'><img src='$signPath' alt='' border=0 height=60></img></td>
        <td style='text-align: right;'>Total Amount</td>
        <td style='text-align: right;'>$totalNet</td>
    </tr>
    <tr>
        <td style='text-align: right;'>PPN</td>
        <td style='text-align: right;'>$ppn</td>
    </tr>
    <tr>
        <td style='text-align: right;'>Total Tagihan</td>
        <td style='text-align: right;'>$totalnet2</td>
    </tr>
    <tr>
        <td colspan='5'>Signature</td>
    </tr>
";


$html .= "</table></div>";

$options = new Options();
$options->set('defaultFont', 'Courier');
$options->set('enable_remote', 'true');

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'potrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($invoice_number. ".pdf");

#$output = $dompdf->output();

#file_put_contents('Brochure.pdf', $output);

exit();