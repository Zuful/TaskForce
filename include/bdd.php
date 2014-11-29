<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 02:23
 */

$host = "localhost";
$dbUser = "root";
$password = "";
$database = "taskforce";

$dsn = "mysql:host=" . $host . ";dbname=" . $database;

try{
    $GLOBALS["pdo"] = new PDO($dsn, $dbUser, $password);
    $GLOBALS["pdo"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}