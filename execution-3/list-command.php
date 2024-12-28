<?
// https://ariefsan.crewbasproject.my.id/telegram/execution-3/list-command.php
# require '../00-02-conn-dist.php';
exit();
require '../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=Pingline0933");


$sql = "";

$msg = " *List Command(Daftar Perintah) untuk Wa Bot* \n";
$msg .= " *Command Dist* \n";
$msg .= "1. dropping-dist <no-dropping> \n";
$msg .= "2. invoice-dist <no-invoice> \n";
$msg .= "3. pk-dist <nomor pk>\n";
$msg .= "4. store-dist <store_id/store code>\n";
$msg .= "5. notes-dist <kode depo> spasi <tanggal> \n";
$msg .= "6. fa-dist-rbp \n";
$msg .= " *Command Agent* \n";
$msg .= "1. notes-agent <kode depo> spasi <tanggal> \n";
$msg .= "2. gr-agent <nomor gr> \n";
$msg .= "3. fa-agent-rbp \n";
$msg .= " *Checking Server* \n ";
$msg .= "1. server-delay \n";


    $config["whatsappSendMessage"]($config['key-wa-bas'],  $msg , $config['id-wa-group-fa'], "true");
    $config["waFooter"]($config['key-wa-bas'],  array("List-Command") , $config['id-wa-group-fa'], "true");
    # echo $output;

