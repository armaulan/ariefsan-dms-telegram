<?
// https://ariefsan.basproject.online/telegram/execution-2/00-01-agent-download-1.php
require '../00-01-conn-agent.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
#$json = json_decode($_POST['let2']);
#$bulan = $json[1];
#$sort = $json[2];

$sql = "
	select 'akbar' as `cowo_ganteng`
    , 'devia' as `ibu_pintar`
    , 'sahadi' as `ibu_baik`
    , 'nicoll' as `ibu_creative`
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
#echo mysqli_num_rows($exec);

$msg = "";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .=  $row['cowo_ganteng'] . ";" .
          			$row['ibu_pintar'] . ";" .
          			$row['ibu_baik'] . ";" .
          			$row['ibu_creative'] . "; \n" 
          
          ;
      }
  
  	# Taruh hasil query ke folder
  	file_put_contents("download/test-12345.csv", $msg);
    
  	$filePath = 'download/test-12345.csv';

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
