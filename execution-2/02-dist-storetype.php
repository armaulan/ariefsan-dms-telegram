<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-2/02-dist-storetype.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
$json = json_decode($_POST['let2']);
$value = $json[1];


/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select sf.function_id as `FUNCTION_ID`
, sf.function_name as  `FUNCTION_NAME`
, sm.mainstore_id as `MAINSTORE_ID`
, sm.mainstore_name as `MAINSTORE_NAME`
, sc.classification_id as `CLASS_ID`
, sc.classification_name as `CLASS_NAME`
, stc.store_type_id as `STYPE_ID`
, st.store_type_name as `STYPE_NAME`
from store_type_classification stc 
join store_type st on st.store_type_id = stc.store_type_id 
join store_classification sc on sc.classification_id = stc.classification_id 
join store_mainstore sm on sm.mainstore_id = sc.mainstore_id 
join store_function sf on sf.function_id = st.function_id 
where 0=0
and st.store_type_name like '%$value%'
or sc.classification_name like '%$value%'
or sm.mainstore_name like '%$value%'
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#Atribute Store DIST \n \n";
if(mysqli_num_rows($exec) > 0) {
  
  while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "SAP ID : " . $row['FUNCTION_ID'] . "\n" .
          		"SAP NAME : " . $row['FUNCTION_NAME'] . "\n" .
          		"MAIN ID : " . $row['MAINSTORE_ID'] . "\n" .
          		"MAIN NAME : " . $row['MAINSTORE_NAME'] . "\n" .
          		"CLASS ID : " . $row['CLASS_ID'] . "\n" .
          		"CLASS NAME : " . $row['CLASS_NAME'] . "\n" .
          		"TYPE ID : " . $row['STYPE_ID'] . "\n" .
          		"TYPE NAME : " . $row['STYPE_NAME'] . "\n \n" 
				;
      }
    
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot5525370392:AAHIJPDWE5bckP1J8V0d4ilWpNwvRv9OG0o/sendMessage?chat_id=-1001542755544&text=" . urlencode($msg));

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // menampilkan hasil curl
    echo $output; 
} 
