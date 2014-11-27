<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:10
 */
//TODO : Créer le contenu de la page utilisateurs (Liste contacts, Ajouter contacts)
//TODO : Créer le contenu de la page paramètres (Affichages par défaut)
//TODO : Après suppression d'une tâche si on fait "précédent" renvoyer sur la page d'index (en gros si on trouve pas l'id de la tache)
//TODO : Faire en sorte que l'on puisse créer plusieur tâche à la suite sans avoir les informations de la précédente dans le formulaire (js pour vider après création)
//TODO : Ajouter une boite de dialogue qui apparait après création, modification ou suppression d'une tâche
//TODO : Ajouter une boite de confirmation qui apparait avant suppression d'une tâche (ne pas oublier le menu contextuel)
//TODO : Implémenter le widget datepicker pour les choix des dates
//TODO : Ajouter un menu latéral à gauche permettant de réaliser les actions ci-dessous
    //TODO : Ajouter le filtrage à la récupération des taches (Ajax->getTasks(array $filters))
    //TODO : Ajouter une zone de recherche sur les listes
//TODO : Veiller à ce que dans un formulaire tout les champs obligatoires soient remplit
//TODO : Pour toute actions (edit, delete, done) s'assurer que le user a bien les droits
//TODO : Mettre TOUT les edit, delete et done en bouton-liens (avec variable GET)
//TODO : Faire en sorte qu'après un edit, delete ou done on puisse continuer a utiliser le menu contextuel (retirer les variables url)

session_start();
include_once(dirname(__FILE__) . "/class/controller/Controller.php");

use Controller\Controller;

$ctrl = new Controller();
if(isset($_GET["action"])){
    if($ctrl->action($_GET["action"])){

        //header("Location : index.php");
        //Afficher un message de confirmation
    }
    else{

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