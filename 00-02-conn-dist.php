<?php
date_default_timezone_set('Asia/jakarta');

$servername = "103.103.192.190";
#$servername = "103.93.53.15";
$username = "dms_bascds_dist";
$password = "St7an@cds24";
$dbname = "sariroti_dms";
$port = "3306";

# Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

# Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}