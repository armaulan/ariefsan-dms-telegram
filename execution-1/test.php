<?
// https://ariefsan.basproject.online/telegram/execution-1/test.php
require_once '../Library/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

$html= "<table> 
<tr style='border-bottom:1px solid black'>
<td> data 1 </td > 
<td> data 2 </td >

</tr>





</table>";





// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
?>