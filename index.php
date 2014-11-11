<?php
ini_set('display_errors', 'On'); // sometimes it's needed when overridden to Off
error_reporting(E_ALL);
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:10
 */
//TODO : Faire une page de création de compte utilisateur
session_start();
include_once(dirname(__FILE__) . "/include/bdd.php");
include_once(dirname(__FILE__) . "/class/helper/Account.php");
include_once(dirname(__FILE__) . "/class/model/ModelTaskForce.php");
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Helper\Account;
use Model\ModelUser;
use Controller\MainController;

if(isset($_POST["posted"]) && $_POST["posted"] == 1){
    $account = new Account();
    $userInfos = $account->signIn("users", array("email" => $_POST["email"], "password" => $_POST["password"]), true);
    $user = new ModelUser();
    $user->hydrate($userInfos);
    $_SESSION["user"] = serialize($user);
}
elseif(isset($_SESSION["user"])){
    $user = unserialize($_SESSION["user"]);
}
else{
    header("Location:signIn.php");
}

$ctrl = new MainController($user);
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
<!--
    <form action="index.php?action=createTask" method="post">
        Tache : <br>
        <input type="text" name="name">
        <br><br>

        Importance : <br>
        <select name="importance">
            <option value="Basse">Basse</option>
            <option value="Moyenne">Moyenne</option>
            <option value="Elevee">Elevee</option>
        </select>
        <br><br>

        Date de fin : <br>
        <input type="text" name="due_date">
        <br><br>

        <input type="hidden" value="0" name="status">
        <input type="hidden" value="<?php echo date("d/m/Y", time());?>" name="creation_date">
        <input type="hidden" value="1" name="posted">
        <input type="submit" value="Envoyer">
    </form>
-->
</body>
</html>