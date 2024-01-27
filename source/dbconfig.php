<?php

//Host ismimiz.
$HostName = "localhost";
$DatabaseName = "movie_db";
$HostUser = "root";
$HostPass = "password"; // host password can be changed 

$conn = mysqli_connect($HostName,$HostUser,$HostPass,$DatabaseName);
?>