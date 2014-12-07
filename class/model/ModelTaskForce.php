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
    public $password;
    public $active;
    public $creation_date;
    public $last_connexion;

    public function hydrate(array $infos){//TODO : Voir dans quelle mesure on peut rendre cette méthode universelle (parcourire tout les attribut d'une classe)
        $this->id = (isset($infos["id"]))?$infos["id"]:null;
        $this->pseudo = (isset($infos["pseudo"]))?$infos["pseudo"]:null;
        $this->name = (isset($infos["name"]))?$infos["name"]:null;
        $this->firstname = (isset($infos["firstname"]))?$infos["firstname"]:null;
        $this->email = (isset($infos["email"]))?$infos["email"]:null;
        $this->password = (isset($infos["password"]))?$infos["password"]:null;
        $this->active = (isset($infos["active"]))?$infos["active"]:null;
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

    public function hydrate(array $infos){
        $this->id = (isset($infos["id"]))?$infos["id"]:null;
        $this->id_task_creator = (isset($infos["id_task_creator"]))?$infos["id_task_creator"]:null;
        $this->id_task_executor = (isset($infos["id_task_executor"]))?$infos["id_task_executor"]:null;
        $this->name = (isset($infos["name"]))?$infos["name"]:null;
        $this->description = (isset($infos["description"]))?$infos["description"]:null;
        $this->importance = (isset($infos["importance"]))?$infos["importance"]:null;
        $this->status = (isset($infos["status"]))?$infos["status"]:null;
        $this->due_date = (isset($infos["due_date"]))?$infos["due_date"]:null;
        $this->execution_date = (isset($infos["execution_date"]))?$infos["execution_date"]:null;
        $this->creation_date = (isset($infos["creation_date"]))?$infos["creation_date"]:null;
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
    public $windowTheme;
    public $firstOptionTheme;
    public $secondOptionTheme;
}

class ModelContextualMenu{
    public $idTag;
    public $liMenu;
    public $theme;
}

class ModelAjaxLoadFiles{
    public $confirmDelete;
    public $taskMenu;
}

class ModelPanel{
    public $dataPosition;
    public $dataDisplay;
    public $panelContent;
    public $theme;
}

class ModelParameters{
    public $avatar;
    public $theme;
    public $defaultTaskListQuery;
}

class ModelFilters{
    public $search;
    public $importance;
    public $chronology;
    public $status;
}