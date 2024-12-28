<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/remindersupport.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();
#require '../00-02-conn-dist.php';


$sql = "
    select 1";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);

$msg = "*Intermezzo hari ini* \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= ""
                    ;
      }
  
    #random image 1  
  	#$randomImg = json_decode(file_get_contents("https://candaan-api.vercel.app/api/image/random"));
  	#$urlImg = $randomImg->data->url;
	#print($randomImg->data->url);
  
    #random image 2
  	$randomImg2 = json_decode(file_get_contents("https://api.thecatapi.com/v1/images/search"));
  	$urlImg2 = $randomImg2[0]->url;
	#print($randomImg->data->url);

    #random image 3
  	#$randomImg3 = file_get_contents("https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-08-random-image-kit.php");
  	#$urlImg3 = file_get_contents("https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-08-random-image-kit.php");
	#print($randomImg->data->url);
  
  	#random text 1
  	$randomquotes = json_decode(file_get_contents("https://candaan-api.vercel.app/api/text/random"));
  	$urlquotes = $randomquotes->data;
	#print($randomImg->data->url);
    
    #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'], 'Kordinasi DMS Distributor', $msg . "\n" . $urlquotes , $urlImg3 );
  	$config["whacenterSendGroupDoc"]($config['key-whacenter-1'], 'Koordinasi DMS Agent' , $msg . "\n" . $urlquotes , $urlImg2);
  
} 

