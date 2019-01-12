<?php
//Connect to Database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'io_db';
$db = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
