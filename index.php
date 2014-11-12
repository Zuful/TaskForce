<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:10
 */
//TODO : Rendre actif les liens du menu dans le header
//TODO : Rendre fonctionnelle la création d'une tâche
//TODO : Rendre fonctionnelle la modification d'une tâche
//TODO : Rendre fonctionnelle la validation d'une tâche
session_start();
include_once(dirname(__FILE__) . "/include/bdd.php");
include_once(dirname(__FILE__) . "/class/helper/Account.php");
include_once(dirname(__FILE__) . "/class/model/ModelTaskForce.php");
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
    echo $ctrl->getUi()->homePage();
?>
</body>
</html>