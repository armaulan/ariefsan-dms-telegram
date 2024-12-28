<?
# Testing trigger ke telegram, cek apakah file ini berhasil di hit oleh wablas
# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-02-ticket-showlist.php");

# https://ariefsan.crewbasproject.my.id/telegram/execution-3/ticket/03-02-ticket-showlist.php
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/ticket/03-02-ticket-showlist.php
# require '../00-02-conn-dist.php';
require '00-connection.php';
require '../../00-03-base-config.php';
require '00-func-user.php';
$config = config();

$json = json_decode($_POST['let2']);
$channel = $json[1];
#$channel = "agent";

$setLang = "SET lc_time_names = 'id_ID';";

$query = "SELECT t.ticket_id
, IFNULL((select u.name from `user` u where u.user_id=t.requester),'Unknown') as requester
, t.ticket_date
, t.ticket_created
, UPPER(DAYNAME(t.ticket_date)) as dayname
, MONTHNAME(t.ticket_date) as monthname
, YEAR(t.ticket_date) as yearname
, CONCAT(UPPER(DAYNAME(t.ticket_date)) , ', ', DAY(ticket_date), ' ', MONTHNAME(t.ticket_date), ' ' , YEAR(t.ticket_date)) dayticket
, CONCAT(HOUR(t.ticket_created),':', MINUTE(t.ticket_created)) as timecreated
, t.detail
, CASE
	WHEN t.status= 1 THEN 'NOTYET'
    WHEN t.status= 2 THEN 'DONE'
    END AS ticket_status
, if(t.status=2, CONCAT(HOUR(t.done_time), ':', MINUTE(t.done_time)), '') as done_time
, if(t.status=2, TIMESTAMPDIFF(MINUTE, t.ticket_created, t.done_time), '') as processing_time
, if(t.status=2, TIMESTAMPDIFF(MINUTE, t.done_time, now()), '') as current_interval
# , t.pic_vendor
, (select u.name from `user`u where u.user_id = t.pic_vendor) as pic_vendor
FROM `ticket` t
where 0=0 
and t.channel = '$channel'
and t.status = 1
or 0=0 
and t.channel = '$channel'
and t.status = 2 
and date(t.done_time) = CURRENT_DATE 
and TIMESTAMPDIFF(MINUTE, t.done_time, now() ) < 5
order by t.ticket_date, t.ticket_id; ";

$lang_exec = mysqli_query($conn, $setLang) or die(mysqli_error($conn));
$query_exec = mysqli_query($conn, $query) or die(mysqli_error($conn));

$msg = "*LIST SUPPORT ". strtoupper($channel). "*\n";
if(mysqli_num_rows($query_exec) > 0) {
  	$temp = "";
    $count = 1;
    while($row = mysqli_fetch_array($query_exec)) {
        
		if($temp != $row['dayticket'] ) {
        	$msg .= "\n". "*". $row['dayticket']. "*";
            $temp = $row['dayticket'];
        } 
      	
      	$msg .= "\n". $count. ". ". $row['detail']." ";
      	$msg .= "*(ID:". $row['ticket_id']. ")* ";
        $msg .= "*(". $row['requester']. " ". $row['timecreated']. ")* ";
      
        if($row['ticket_status'] == "DONE") {
        	$msg .= "*(". $row['pic_vendor']. " ". $row['done_time']. ")* ";
        }
        
      	$msg .= "*". $row['ticket_status']. "* \n";
        $count ++;

      
      }
}

		$msg .= "\n \n";
		$msg .= "*MOHON REPLY ALL JIKA ADA REQUEST BY EMAIL DARI TIM SARIROTI, SELAIN DIBUAT DONE* \n"; 
        $msg .= "*TERIMAKASIH* \n \n" ;
        $msg .= "*Mohon dibantu tim data @6285719021023, @6285963118476, @6285645323095, @6289531804106, @6281290001380* \n" ;
        $msg .= "*Mohon dicek lagi requestnya @6282120792880, @6282131009200, @6281269688008, @6281287912408, @6281227986740, @6281291095083* \n" ;

# echo nl2br($msg);
$msg .= "\n*Please Input and Update LogSupport on wa.me/6282114691982*";
# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-fa'], "true");

if($channel == 'agent') {
	$config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-agent'], "true");
	# $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, "6282180603613", "false");
  	# $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], "Koordinasi DMS Agent", $msg);
} elseif ($channel == 'dist') {
	$config["whatsappSendMessage"]($config['key-wa-bas'],  $msg, $config['id-wa-group-dist'], "true");
  	# $config["whacenterSendGroupMessage"]($config['key-whacenter-1'], "Kordinasi DMS Distributor", $msg);
}
  	
