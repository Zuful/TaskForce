<?php
/**
* Created by PhpStorm.
* User: Yam's
* Date: 18/10/14
* Time: 01:10
*/
session_start();
if(isset($_SESSION["user"])){
    header("Location:index.php");
}

include_once(dirname(__FILE__) . "/include/bdd.php");
include_once(dirname(__FILE__) . "/class/model/ModelTaskForce.php");
include_once(dirname(__FILE__) . "/class/metier/UserInterface.php");

use Model\ModelUser;
use Controller\Controller;

$user = new ModelUser();
$ui = new \Metier\UserInterface($user);
?>
<!doctype html>
<html lang="fr">
<head>
    <?php echo $ui->getHead(); ?>
</head>
<body>
    <?php echo $ui->signInPage(); ?>
</body>
</html>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       