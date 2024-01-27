<?php 
require 'base.php' ;
include_once 'dbconfig.php';

session_start();
session_unset();
session_destroy();

header("Location: ./index.php");
    exit();
    
?>