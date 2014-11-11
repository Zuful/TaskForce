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
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Model\ModelUser;
use Controller\MainController;

$user = new ModelUser();
$ctrl = new MainController($user);
?>
<!doctype html>
<html lang="fr">
<head>
    <?php echo $ctrl->getUi()->getHead(); ?>
</head>
<body>
    <?php echo $ctrl->getUi()->signInPage(); ?>
</body>
</html>