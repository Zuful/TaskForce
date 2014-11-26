<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:29
 */

namespace Model;


class ModelUser {
    public $id;
    public $pseudo;
    public $name;
    public $firstname;
    public $email;
    public $creation_date;
    public $last_connexion;

    public function hydrate(array $infos){//TODO : Voir dans quelle mesure on peut rendre cette méthode universelle (parcourire tout les attribut d'une classe)
        $this->id = (isset($infos["id"]))?$infos["id"]:null;
        $this->pseudo = (isset($infos["pseudo"]))?$infos["pseudo"]:null;
        $this->name = (isset($infos["name"]))?$infos["name"]:null;
        $this->firstname = (isset($infos["firstname"]))?$infos["firstname"]:null;
        $this->email = (isset($infos["email"]))?$infos["email"]:null;
        $this->creation_date = (isset($infos["creation_date"]))?$infos["creation_date"]:null;
        $this->last_connexion = (isset($infos["last_connexion"]))?$infos["last_connexion"]:null;
    }
}

class ModelTask {
    public $id;
    public $id_task_creator;
    public $id_task_executor;
    public $name;
    public $description;
    public $importance;
    public $status;
    public $due_date;
    public $execution_date;
    public $creation_date;

    public function hydrate(array $info){
        $this->id = (isset($info["id"]))?$info["id"]:null;
        $this->id_task_creator = (isset($info["id_task_creator"]))?$info["id_task_creator"]:null;
        $this->id_task_executor = (isset($info["id_task_executor"]))?$info["id_task_executor"]:null;
        $this->name = (isset($info["name"]))?$info["name"]:null;
        $this->description = (isset($info["description"]))?$info["description"]:null;
        $this->importance = (isset($info["importance"]))?$info["importance"]:null;
        $this->status = (isset($info["status"]))?$info["status"]:null;
        $this->due_date = (isset($info["due_date"]))?$info["due_date"]:null;
        $this->execution_date = (isset($info["execution_date"]))?$info["execution_date"]:null;
        $this->creation_date = (isset($info["creation_date"]))?$info["creation_date"]:null;
    }
}

class ModelUserAndTask {
    public $userId;
    public $taskId;

    public function hydrate(array $infos){
        foreach($infos as $info){
            $this->$userId = (isset($info["userId"]))?$info["userId"]:null;
            $this->taskId = (isset($info["taskId"]))?$info["taskId"]:null;
        }

        return $this;
    }
}

class ModelDoubleChoiceBox{
    public $idTag;
    public $title;
    public $mainMessage;
    public $subMessage;
    public $firstOption;
    public $secondOption;
}

class ModelContextualMenu{
    public $idTag;
    public $liMenu;
}

class ModelAjaxLoadFiles{
    public $confirmDelete;
    public $taskMenu;
}