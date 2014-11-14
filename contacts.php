<?php
/**
 * Created by PhpStorm.
 * User: Yamani
 * Date: 13/11/2014
 * Time: 09:03
 */
session_start();
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Controller\Controller;

$ctrl = new Controller();
if(isset($_GET["action"])){
    if($ctrl->action($_GET["action"])){
        //Afficher un message de confirmation
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <?php echo $ctrl->getUi()->getHead(); ?>
</head>
<body>
<?php
echo $ctrl->getUi()->contactsPage();
?>
</body>
</html>