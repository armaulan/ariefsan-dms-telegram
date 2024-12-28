<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-06-gsheets-dmstimeline.php
#require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# $ch = curl_init();

# https://quickchart.io/chart?c={type:bar,data:{labels:[2012,2012,2014,2015,2016],datasets:[{label:Users,data:[120,60,50,180,120]}]}}

/*
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "https://quickchart.io/chart?c={type:%27bar%27,data:{labels:[2012,2012,2014,2015,%202016],datasets:[{label:%27Users%27,data:[120,60,50,180,120]}]}}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
$out = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);


$fp = fopen('data.jpg', 'w');
fwrite($fp, $out);
fclose($fp);
*/ 


# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

#$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$day = (int)$json[1];
#$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

#$sql = "";
#$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));

#$dateData = "";
#$stockData = "";
# $dataMessage = "depo_code;depo_name;store_id;store_code;store_name;top_id;jwk;last_drop\n";

/*
if(mysqli_num_rows($exec) > 0) {
    
	while($row= mysqli_fetch_array($exec)) {
      	if($dateData == ""){
        	$dateData .= $row['tanggal'];
        } else {
        	$dateData .= ",";
          	$dateData .= $row['tanggal'];
        }
      
      	if($stockData == ""){
        	$stockData .= $row['amount'];
        } else {
        	$stockData .= ",";
          	$stockData .= $row['amount'];
        }      
      
      	#$dataMessage .= $row['depo_code']. ";";
      	#$dataMessage .= $row['depo_name']. ";";
      	#$dataMessage .= $row['store_id']. ";";
      	#$dataMessage .= $row['store_code']. ";";
      	#$dataMessage .= $row['store_name']. ";";
      	#$dataMessage .= $row['top_id']. ";";
      	#$dataMessage .= $row['jwk']. ";";
      	#$dataMessage .= $row['last_drop']. "\n";
      
      	# Prepare Product untuk menggunakan product_code, sebagai bantuan untuk sortir nanti
		#$customProd = $row['product_code']. "-". $row['short_name'];
      
      	# Masukkan list semua product kedalam variable dataHeader
      	#array_push($dataHeader, $customProd);
        
      	#$tempDataRow = $row['store_id']. "$". $row['store_name'];
      	# Masukkan store_id kedalam object dataBody, dengan sifat unique/distinct
      	#$dataBody += [ $tempDataRow  => array() ];
      	
      	# Setelah toko sdh di insert di object, maka toko ini juga diinsert array (multidimensi)
      	#$dataBody[$tempDataRow] += [$customProd => array($row['qty_estimasi'], $row['qty_dropping'], $row['qty_sold'], $row['qty_bs']) ];
      
      }
}*/
  
# print($dateData);
# print("<br>");
# print($stockData);
# $filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_topjwkissue.txt";

# print($msd);
# $writeResult = file_put_contents("download/". $filename, $dataMessage);

# if ($writeResult != false) {
  #$config["whatsappSendMessage"]($config['key-wa-bas'],  "Filter Warung TOP 7, JWK Lebih dari 1", $config['id-wa-group-fa'], "true");
  #$config["whatsappSendDocs"]($config['key-wa-bas'],  $config['id-wa-group-fa'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/report/download/". $filename,  "true");
#}

# $ch = curl_init();

# https://quickchart.io/chart?c={type:'bar',data:{labels:[2012,2012,2014,2015,2016],datasets:[{label:'Users',data:[120,60,50,180,120]}]}}

# $body = "https://quickchart.io/chart?c={type:'bar',data:{labels:[$dateData],datasets:[{label:'Users',data:[$stockData]}]}}";
# print($body);


$ch = curl_init();
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, "https://screenshot.abstractapi.com/v1/?api_key=315819beaf6148c5803b7e69b15d9411&url=https://docs.google.com/spreadsheets/d/e/2PACX-1vQyMiLUt3QD_M6WODOfTbTAKdWz4IUX-mSGGhySb791epyHnW6Rh4jLLmJvsRGapG8oJYNpJFVav0h-/pubhtml?gid=1827227776&single=true&witdh=200");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HEADER, 0);

// grab URL and pass it to the browser
$out = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

$filename = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1 , 4). "_dmstimeln.png";

$fp = fopen("file-other/". $filename, 'w');
fwrite($fp, $out);
fclose($fp);

$config["whatsappSendImg"]($config['key-wa-bas'],  $config['id-wa-group-crew'], "https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/file-other/". $filename,  "true", "dms-timeline");




