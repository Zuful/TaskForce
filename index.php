<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:10
 */
//TODO : Créer le contenu de la page utilisateurs
//TODO : Créer le contenu de la page paramètres
//TODO : Rendre fonctionnelle la modification d'une tâche
//TODO : Rendre fonctionnelle la suppression d'une tâche
//TODO : Rendre fonctionnelle la validation d'une tâche
//TODO : Organiser l'affichage des taches par ordre chronologique
//TODO : Faire en sorte que l'on puisse créer plusieur tâche à la suite dans avoir les informations de la précédente dans le formulaire (js pour vider après création)
//TODO : Ajouter une boite de dialogue qui apparait après création, modification ou suppression d'une tâche
//TODO : Modifier la requête d'affichage pour n'afficher que les résultat dont le status est à 0
//TODO : Ajouter une zone de recherche sur les listes
//TODO : Implémenter le widget datepicker pour les choix des dates

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
    echo $ctrl->getUi()->homePage();
?>
</body>
</html>