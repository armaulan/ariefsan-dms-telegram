<?
// https://ariefsan.basproject.online/telegram/execution-1/02-06-opendrop.php
require '../00-02-conn-dist.php';

#$sender = $_POST['let1'];
#$url = $_POST['let3'];
#$json = json_decode($_POST['let2']);
#$bulan = $json[1];
#$sort = $json[2];

/*
$bulan = $_POST['bulan'];
$sort = $_POST['sort'];
*/


$sql = "select 
b.depo_code,
b.depo_name as `NAMA DEPO`,
a.user_id as `ID DELMAN`,
a.username as `USERNAME`,
c.salesman_type_name as `TYPE SALESMAN`
 from user_login a
join depo b on b.depo_id=a.depo_id
join salesman_type c on c.salesman_type_id=a.salesman_type_id
where a.is_button_show=1
and b.owner_id=28
and b.depo_code <> 70002203
and c.salesman_type_id in ('1','2','6')
limit 50
";

$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "#02-Dist (User Button Show  Plus Minus) \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "User ID:" . $row['ID DELMAN'] . "\n" .
				"Username:" . $row['USERNAME'] . "\n" .
				"Salesman Type:" . $row['TYPE SALESMAN'] . "\n \n" 
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
