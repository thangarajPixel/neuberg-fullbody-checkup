<?php
ob_start();

if($_SERVER['HTTP_HOST'] == '192.168.0.60'){
$serverName = "localhost";
$userName = "root";
$passWord = "";
$dataBase = "pixelstudio_db";	
}else{

	
$servername = "localhost";
$username = "phpmyadmin";
$password = 'Neuberg@1243';
$database = 'neuberg_campaigns_db';

}


$connect = mysqli_connect($serverName,$userName,$passWord,$dataBase);


	
	
ob_flush();
?>