<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 02/11/14
 * Time: 12:28
 */
include_once(dirname(__FILE__) . "/include/bdd.php");
include_once(dirname(__FILE__) . "/class/helper/Helper.php");
include_once(dirname(__FILE__) . "/class/model/ModelTaskForce.php");

$helper = new \Helper\Helper();
$signUp = $helper->signUp();

if($signUp){
    $userInfos = $helper->signIn();//TODO : Effectuer une vérification de l'adresse mail et rediriger sur la page d'accueil après création du compte

    if($userInfos){
        $user = new \Model\ModelUser();
        $user->hydrate($userInfos);
        $_SESSION["user"] = serialize($user);

        header("Location:index.php");
    }

}
?>

<!doctype html>
<html lang="fr">
<head>
    <title>Inscription</title>
</head>
<body>
    <nav>

    </nav>

    <form action="signUp.php" method="POST">
        nom : <br>
        <input type="text" name="name">
        <br><br>

        prenom : <br>
        <input type="text" name="firstname">
        <br><br>

        pseudo : <br>
        <input type="text" name="pseudo">
        <br><br>

        email : <br>
        <input type="text" name="email">
        <br><br>

        Mot de passe : <br>
        <input type="password" name="password">
        <br><br>

        <!-- TODO : Ajouter la confirmation du password -->
        <input type="hidden" value="<?php echo time(); ?>" name="creation_date">
        <input type="hidden" value="1" name="posted">
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>