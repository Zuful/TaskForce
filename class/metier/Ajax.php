<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 01:23
 */

namespace Metier;

include_once(dirname(__FILE__) . "/../helper/Crud.php");
include_once(dirname(__FILE__) . "/../model/ModelTaskForce.php");

use Helper\Crud;
use Model\ModelAjaxLoadFiles;
use Model\ModelFilters;
use Model\ModelUser;
use Model\ModelTask;

class Ajax {
    private $_user;
    private $_crud;
    private $_pdo;

    private $_queryTaskChronology;
    private $_queryTaskImportance;


    public function __CONSTRUCT(ModelUser $user){
        $this->_user = $user;
        $this->_pdo = ($GLOBALS["pdo"] instanceof \PDO)?$GLOBALS["pdo"]:null;
        $this->_crud = new Crud($this->_pdo);

        $filterImportanceArray = array(
            0 => "Tous",
            3 => "Importance élevée seulement",
            2 => "Importance moyenne seulement",
            1 => "Importance basse seulement"
        );

        $filterChronologyArray = array(
            5 => "Partant de la plus récente créaction",
            4 => "Partant de la plus ancienne créaction",
            7 => "Partant de la plus récente date limite",
            6 => "Partant de la plus ancienne date limite",
            9 => "Partant de la plus récente résolution",
            8 => "Partant de la plus ancienne résolution"
        );
    }

    private function _getQueryTaskByImportance($importance){

        $importance = ($importance != 0)?"AND importance = " . $importance:null;
        return $importance;
    }

    private function _getQueryTaskByChronology($chronologyType){
        $chronology = null;

        switch($chronologyType){
            case 4 :
                $chronology = "creation_date ASC";
                break;
            case 5 :
                $chronology = "creation_date DESC";
                break;
            case 6 :
                $chronology = "due_date ASC";
                break;
            case 7 :
                $chronology = "due_date DESC";
                break;
            case 8 :
                $chronology = "execution_date ASC";
                break;
            case 9 :
                $chronology = "execution_date DESC";
                break;
        }

        return "ORDER BY " . $chronology;
    }

    private function _getQueryTaskByStatus($status = null){
        $status = (!is_null($status))?$status:0;

        return "AND status = " . $status;
    }

    public function createTask(ModelTask $task){
        $elms = json_encode($task);
        $elms = json_decode($elms, true);

        $this->_crud->setTable("tasks");
        $this->_crud->setElms($elms);

        return $this->_crud->create();
    }


    /**
     * Returns the list of all of the tasks of the current user
     *
     * @param ModelFilters $filters
     * @return ModelTask[]
     */
    public function getTasks(ModelFilters $filters){
        $filterSearch = (!is_null($filters->search))?"AND (name LIKE '%" . $filters->search . "%' OR description LIKE '%". $filters->search ."%')":null;
        $filterImportance = (!is_null($filters->importance))?$this->_getQueryTaskByImportance($filters->importance):null;
        $filterChronology = (!is_null($filters->chronology))?$this->_getQueryTaskByChronology($filters->chronology):"ORDER BY creation_date DESC";
        $filterStatus = (!is_null($filters->status))?$this->_getQueryTaskByStatus($filters->status):null;

        $sql = "SELECT * FROM tasks AS T
                INNER JOIN users_and_tasks AS UAT
                ON T.id = UAT.task_id
                WHERE UAT.user_id = :user_id
                " . $filterSearch . "
                " . $filterStatus . "
                " . $filterImportance . "
                " . $filterChronology . "
                LIMIT 20";

        $req = $this->_pdo->prepare($sql);
        $req->execute(array(":user_id" => $this->_user->id));
        $req->setFetchMode(\PDO::FETCH_ASSOC);
        $res = $req->fetchAll();

        $list = array();

        foreach($res as $task){
            $modelTask = new ModelTask();
            $modelTask->hydrate($task);
            $list[] = $modelTask;
        }

        return $list;
    }

    /**
     * Returns the task of the current user matching the task id passed in parameter
     *
     * @param int $taskId
     * @return ModelTask
     */
    public function getTask($taskId){
        $sql = "SELECT * FROM tasks AS T INNER JOIN users_and_tasks AS UAT
                ON T.id = UAT.task_id
                WHERE UAT.user_id = :user_id
                AND T.id = :id";

        $req = $this->_pdo->prepare($sql);
        $req->execute(array(":user_id" => $this->_user->id, ":id" => $taskId));
        $req->setFetchMode(\PDO::FETCH_ASSOC);
        $arrayTask = $req->fetch();

        $task = new ModelTask();
        $task->hydrate($arrayTask);

        return $task;
    }

    public function updateTask(ModelTask $task){
        $elms = json_encode($task);
        $elms = json_decode($elms, true);

        $this->_crud->setTable("tasks");
        $this->_crud->setElms($elms);
        return $this->_crud->update($task->id);
    }

    public function deleteTask($id){
        //TODO : Mettre ces requettes dans une transaction
        $deleteUsersAndTask = $this->deleteUserAndTask($id);

        if($deleteUsersAndTask){
            $this->_crud->setTable("tasks");
            $deleteTask = $this->_crud->delete($id);

            if($deleteTask){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function createUserAndTask($userId, $taskId){
        $elms = array("user_id" => $userId, "task_id" => $taskId);

        $this->_crud->setTable("users_and_tasks");
        $this->_crud->setElms($elms);

        return $this->_crud->create();
    }

    public function deleteUserAndTask($taskId){
        $this->_crud->setTable("users_and_tasks");
        $this->_crud->setIdFieldName("task_id");

        $deleteUserAndTask = $this->_crud->delete($taskId);
        $this->_crud->setDefaultIdFieldName();

        if($deleteUserAndTask){
            return true;
        }
        else{
            return false;
        }
    }

    public function doneTask(ModelTask $task){
        $task->execution_date = time();
        $task->status = 1;
        $task->id_task_executor = $this->_user->id;

        $this->updateTask($task);
    }

    public function getLastInsertId(){
        return $this->_pdo->lastInsertId();
    }

    public function hasRight(){
        
    }
} 