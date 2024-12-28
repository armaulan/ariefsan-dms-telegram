<?php
date_default_timezone_set('Asia/jakarta');
# https://ariefsan.crewbasproject.my.id/telegram/00-01-conn-agent.php

# $servername = "103.103.192.192";
# $servername = "103.7.226.75";
# $servername = "103.7.226.75";
$servername = "103.103.192.192";
$username = "dms_bascds_agent";
$password = "St7an@cds24";
$dbname = "sariroti_dms_agent";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "Connected";
}

?>