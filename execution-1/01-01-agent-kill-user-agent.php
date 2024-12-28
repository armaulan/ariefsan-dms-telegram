<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/01-01-agent-kill-user-agent.php
file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=$sender&text=". urlencode("Kill success"));

// include '../00-02-setting.php';
// $config = config();

$sender = $_POST['let1'];
$url = $_POST['let3'];
$json = json_decode( $_POST['let2']);
$user = $json[1];
// $user = "west";

$url="https://dms-agent.pitjarus.co/login/do_login"; 
$postinfo = "username=super.admin&password=Fightercds123";

$cookie_file_path = "cookie.txt";

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
//set the cookie the site has for certain features, this is optional
curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
/*curl_setopt($ch, CURLOPT_USERAGENT,
    "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7"); */
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_exec($ch);

//page with the content I want to grab
curl_setopt($ch, CURLOPT_URL, "https://dms-agent.pitjarus.co/master/entity/kill_user/kill_user/" . $user);
//do stuff with the info with DomDocument() etc
$html = curl_exec($ch);
curl_close($ch);
//echo $html;
$result = json_decode($html, true);

# row_delete
if($result['row_delete'] > 0) {
  		//  file_get_contents("https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=-665646233&text=". urlencode($user . " Berhasil di stop session, Silahkan informasi login ulang ke user"));
  		// echo "line 51";
  	     
  		$ch2 = curl_init(); 

    	// set url 
  		# kirim $msg ke telegram
    	curl_setopt($ch2, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=-1001652719542&text=" . urlencode( $user . " Berhasil di stop session, Silahkan informasi login ulang ke user"));

    	// return the transfer as a string 
    	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 

    	// $output contains the output string 
    	$output2 = curl_exec($ch2); 

    	// tutup curl 
    	curl_close($ch2);      

  
} else {
   // file_get_contents("https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=-665646233r&text=". urlencode($user . " User Sedang tidak Online, Bestiee"));
  // file_get_contents("https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=-665646233&text=". urlencode("Session Not Found"));
  		 // echo "line 72";
  	     $ch2 = curl_init(); 

    	// set url 
  		# kirim $msg ke telegram
    	curl_setopt($ch2, CURLOPT_URL, "https://api.telegram.org/bot5062853919:AAF9D-EKDga2S_IUJ6_hG5CHKziHM9xfN9c/sendMessage?chat_id=-1001652719542&text=" . urlencode( $user . " User Sedang tidak Online, Bestiee"));

    	// return the transfer as a string 
    	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 

    	// $output contains the output string 
    	$output2 = curl_exec($ch2); 
  		// echo $output2;

    	// tutup curl 
    	curl_close($ch2);   
} 

