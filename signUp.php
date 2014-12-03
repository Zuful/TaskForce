<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 02/11/14
 * Time: 12:28
 */
session_start();
include_once(dirname(__FILE__) . "/include/bdd.php");
include_once(dirname(__FILE__) . "/class/model/ModelTaskForce.php");
include_once(dirname(__FILE__) . "/class/metier/UserInterface.php");
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Model\ModelUser;
use Metier\UserInterface;

if(isset($_POST["action"])){
    $ctrl = new \Controller\Controller();
    $ctrl->action($_POST["action"]);
}

$user = new ModelUser();
$ui = new UserInterface($user);
?>
<!doctype html>
<html lang="fr">
<head>
    <?php echo $ui->getHead(); ?>
</head>
<body>
    <?php echo $ui->signUpPage(); ?>
</body>
</html>