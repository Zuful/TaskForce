<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 12/11/14
 * Time: 23:38
 */
session_start();
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Controller\Controller;

$ctrl = new Controller();
if(!isset($_GET["id"])){
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo $ctrl->getUi()->getHead(); ?>
</head>
<body>
    <?php echo $ctrl->getUi()->seeTaskPage($_GET["id"]); ?>
</body>
</html>                                                                                                                                                                                                                                                                                                                                                                                                                                