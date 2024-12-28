<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/absensikepo.php
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();


$sql = "
    select 1";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*WAKTUNYA KEPO* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Absen Kepo \n "
                    ;
      }
  	
  	$randomImg = json_decode(file_get_contents("https://candaan-api.vercel.app/api/image/random"));
  	$urlImg = $randomImg->data->url;
	#print($randomImg->data->url);
  
  	#random image 2
  	$randomImg2 = json_decode(file_get_contents("https://api.thecatapi.com/v1/images/search"));
  	$urlImg2 = $randomImg2[0]->url;
	#print($randomImg->data->url);
  
   
    
  	 $config["whacentersenddoc"]($config['key-whacenter-1'], '085780421000' , $msg , 'https://ieiapps.epson.biz/kepo/Home' , $urlImg2 );
  	 $config["whacentersenddoc"]($config['key-whacenter-1'], '085695526925' , $msg , 'https://ieiapps.epson.biz/kepo/Home' , $urlImg );
} 

