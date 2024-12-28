<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-1/AlertAbsensi.php
exit();
require '../00-02-conn-dist.php';


$tanggal_akhir = date('Y-m-d');


$sql = "
    select 1";


$exec = mysqli_query($conn, $sql) or die(mysqli_error($conn));
echo mysqli_num_rows($exec);


$msg = "*JANGAN LUPA ABSENSI CHECK IN CHECK OUT di DARWINBOX* \n \n";
if(mysqli_num_rows($exec) > 0) {
    
    while($row = mysqli_fetch_array($exec)) {
        
        $msg .= "Absen Check In Max Pukul 08.00 WIB.\n\nAbsen Check Out Min Pukul 17.00 WIB"
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

