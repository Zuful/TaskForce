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
//TODO : Faire en sorte que l'on puisse créer plusieur tâche à la suite sans avoir les informations de la précédente dans le formulaire (js pour vider après création?)
//TODO : Ajouter une boite de dialogue qui apparait après création, modification ou suppression d'une tâche
//TODO : Ajouter une boite de confirmation qui apparait avant suppression d'une tâche (ne pas oublier le menu contextuel)
//TODO : Implémenter le widget datepicker pour les choix des dates
//TODO : Veiller à ce que dans un formulaire tout les champs obligatoires soient remplit
//TODO : Mettre TOUT les edit, delete et done en bouton-liens (avec variable GET)
//TODO : Ajouter un switch pour le filtre par status
//TODO : Faire en sorte qu'après un edit, delete ou done on puisse continuer a utiliser le menu contextuel (retirer les variables url)
//TODO : Revoir le design générale (formulaires, menu latéral, menu contextuel)
//TODO : Faire des redirections lorsqu'on est mal connecté ou qu'on cherche à editer, supprimer ou marquer comme fait une tâche sur laquelle on a pas de droits (aucun message d'erreur php ne doit être vu)

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
    <?php echo $ctrl->getUi()->homePage(); ?>
</body>
</html>