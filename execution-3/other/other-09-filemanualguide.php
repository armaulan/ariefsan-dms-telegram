<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-09-filemanualguide.php
require '../../00-01-conn-agent.php';
require '../../00-03-base-config.php';
$config = config();

# file_get_contents("https://api.telegram.org/bot5217628574:AAHpzjY9JAcvS5Dg67UxJdwFz0-EQ2tisUA/sendMessage?chat_id=474974312&text=03-01-ticket-entry.php-line13");

$json = json_decode($_POST['let2']);
$sender = $_POST['let1'];
$doc = $json[1];

# $inputuser = "apk_dist_inn";

$mapping = [
		"apk_dist_inn"   => "https://ariefsan.crewbasproject.my.id/telegram/PDF/apk_dist_inn.pdf",
  		"apk_dist_mitra" => "https://ariefsan.crewbasproject.my.id/telegram/PDF/apk_dist_mitra.pdf",
  		"open_lock_toko_tutup" => "https://ariefsan.crewbasproject.my.id/telegram/PDF/open_lock_toko_tutup.pdf",
  		"flag_sku" => "https://ariefsan.crewbasproject.my.id/telegram/PDF/flag_sku.pdf"

];




$config["whacenterSendGroupDoc"]($config['key-whacenter-1'],  "Crew BAS", "tes", $mapping[$doc] );

# "whacenterSendGroupDoc" => function ($key, $groupName, $message, $file) 






