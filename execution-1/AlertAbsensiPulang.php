<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/AlertAbsensiPulang.php
exit();
require '../00-02-conn-dist.php';
require '../00-03-base-config.php';
$config = config();
#require '../00-02-conn-dist.php';


$sql = "
    select 1";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*WAKTUNYA CHECK OUT di DARWINBOX* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Absen Check Out Min Pukul 17.00 WIB \n*https://hrsr.darwinbox.com*"
                    ;
      }
  
  	#$randomImg = json_decode(file_get_contents("https://candaan-api.vercel.app/api/image/random"));
  	#$urlImg = file_get_contents("https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-08-random-image-kit.php"); #$randomImg->data->url;
	#print($randomImg->data->url);
  
    #random image 2
  	$randomImg2 = json_decode(file_get_contents("https://api.thecatapi.com/v1/images/search"));
  	$urlImg2 = $randomImg2[0]->url;
	#print($randomImg->data->url);
    
    #$config["whacenterSendGroupDoc"]($config['key-whacenter-1'], 'DMS BAS - Fighter 2022', $msg , $urlImg );
  	$config["whacentersenddoc"]($config['key-whacenter-1'], '085718899167' , $msg , $urlImg2 );
  	$config["whacentersenddoc"]($config['key-whacenter-1'], '082225940064' , $msg , $urlImg2 );
  	$config["whacenterSendGroupDoc"]($config['key-whacenter-1'], 'Crew BAS' , $msg , $urlImg2 );
  	$config["whacenterSendGroupDoc"]($config['key-whacenter-1'], 'Etrade Mode' , $msg , $urlImg2 );
  
} 

