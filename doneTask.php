<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 12/11/14
 * Time: 23:39
 */
session_start();
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Controller\Controller;

$ctrl = new Controller();
if(!isset($_GET["taskId"])){
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo $ctrl->getUi()->getTaskHead(); ?>
</head>
<body>
    <?php echo $ctrl->getUi()->createTaskPage(); ?>
</body>
</html>