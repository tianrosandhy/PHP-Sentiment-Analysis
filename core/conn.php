<?php
session_start();
require_once('dbconfig.php');

try{
    $db = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$dbname.';charset=utf8', $dbuser, $dbpass);
}catch(\Exception $e){
    die('
        <strong>Database Error : </strong> '.$e->getMessage().'<br><br>
        Failed to connect to database. Make sure : <br>
        - You already import the database "skripsi.sql" to your MySQL database<br>
        - You set the right database connection (host, port, dbname, dbuser, dbpass) in "core/conn.php"
    ');
}

date_default_timezone_set("Asia/Jakarta");
set_time_limit(0);

require_once("core/function.php");
require_once("core/ClassLogin.php");
require_once("core/ClassNazief.php");
require_once("core/ClassAnalyze.php");

$login = new Login();
$naz = new Nazief();
$analyze = new Analyze();
