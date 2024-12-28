<?php
date_default_timezone_set('Asia/jakarta');
# https://tlgrm.iccgt.my.id/ariefsan/telegram/execution-3/ticket/00-connection.php

$servername = "localhost";
$username = "iccgtmyi_root";
$password = "@Dms2024";
$dbname = "iccgtmyi_ticket";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected";
}

?>