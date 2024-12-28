<?php
# https://ariefsan.crewbasproject.my.id/telegram/execution-3/other/other-00-test.php
# require '../../00-02-conn-dist.php';
require '../../00-03-base-config.php';
$config = config();

$randomImg = json_decode(file_get_contents("https://candaan-api.vercel.app/api/image/random"));
print($randomImg->data->url);


#$json = json_decode($_POST['let2']);
#$sender = $_POST['let1'];
#$depocode = $json[1];
#$cutdate = $json[2];

#$depocode = '70002157';
#$cutdate = '2023-05-05';

# $sql = "";