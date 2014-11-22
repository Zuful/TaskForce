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
use Model\ModelUser;
use Model\ModelTask;

class Ajax {
    private $_user;
    private $_crud;
    private $_pdo;

    public function __CONSTRUCT(ModelUser $user){
        $this->_user = $user;
        $this->_pdo = ($GLOBALS["pdo"] instanceof \PDO)?$GLOBALS["pdo"]:null;
        $this->_crud = new Crud($this->_pdo);
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
     * @param Array $filters
     * @return ModelTask[]
     */
    public function getTasks(array $filters = null){
        $sql = "SELECT * FROM tasks AS T INNER JOIN users_and_tasks AS UAT
                ON T.id = UAT.task_id
                WHERE UAT.user_id = :user_id
                AND status = 0
                ORDER BY id DESC
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
            $this->_crud->setIdFieldName("id");
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

        return $this->_crud->delete($taskId);
    }

    public function getLastInsertId(){
        return $this->_pdo->lastInsertId();
    }

} 