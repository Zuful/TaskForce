<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 08/11/14
 * Time: 12:32
 */
namespace Helper;

class Crud {
    private $_pdo;
    private $_table;
    private $_limit;
    private $_defaultIdFieldName;
    private $_idFieldName;
    private $_elms;
    private $_orderBy;
    private $_strFields;//Enumeration of the fields in which the informations will be inserted
    private $_strBindedFields;//Enumeration of the fields that will be binded with the array of values
    private $_arrayBindedValues;//Array to be passed in parameter in the execute PDO statement (binding)

    public function __CONSTRUCT(\PDO $pdo){
        $this->_pdo = $pdo;
        $this->_limit = "LIMIT 1000";
        $this->_defaultIdFieldName = "id";
        $this->setDefaultIdFieldName();
    }
    //****************************************************************************************S.E.T.T.E.R.S**********************************************************************************
    public function setTable($table){
        $this->_table = $table;
    }
    public function setDefaultIdFieldName(){
        $this->_idFieldName = $this->_defaultIdFieldName;
    }
    public function setIdFieldName($idFieldName){
        $this->_idFieldName = $idFieldName;
    }

    public function setElms(array $elms){
        $this->_elms = $elms;
    }

    public function setLimit($limit){
        $this->_limit = $limit;
    }

    public function setOrderBy($orderBy){
        $this->_orderBy = $orderBy;
    }
    //****************************************************************************************C.R.U.D****************************************************************************************
    public function create(){
        //TODO -- Renvoyer une exception si l'attribut table n'est pas initialisé
        $this->_strBindInsert();
        $req = $this->_pdo->prepare("INSERT INTO " . $this->_table . "(" . $this->_strFields .") VALUES(" . $this->_strBindedFields .")");
        if($req->execute($this->_arrayBindedValues)){
            return true;
        }
        else{
            return false;
        }
    }

    public function read(array $where = null){
        //TODO -- Renvoyer une exception si les attributs table ou elms ne sont pas initialisé
        $sql = "SELECT " . $this->_strSelect() . " FROM " . $this->_table;
        $sql = (!is_null($where))?$sql . " " . $this->_strWhere($where):$sql;
        $sql = $sql . " " /*. $this->_orderBy*/ . $this->_limit;


        $req = $this->_pdo->prepare($sql);
        if(is_array($where)){
            $req->execute(array_values($where));
        }
        else{
            $req->execute($where);
        }
        $req->setFetchMode(\PDO::FETCH_ASSOC);
        if($req->rowCount() == 1){
            $res = $req->fetch();
        }
        else{
            $res = $req->fetchAll();
        }
        return $res;
    }
    public function update($id){
        if(!is_numeric($id)){return false;}//TODO -- Renvoyer une exception si l'id n'est pas un numérique
        $this->_strBindUpdate();//Setter for the $_strBindedFields and arrayBindedValues attributes
        $req = $this->_pdo->prepare("UPDATE " . $this->_table . " SET " . $this->_strBindedFields ." WHERE " . $this->_idFieldName ." = " . $id);
        if($req->execute($this->_arrayBindedValues)){
            return true;
        }
        else{
            return false;
        }
    }
    public function delete($id){
        if(!is_numeric($id)){return false;}//TODO -- Renvoyer une exception si l'id n'est pas un numérique
        $req = $this->_pdo->prepare("DELETE FROM " . $this->_table . " WHERE " . $this->_idFieldName ." = ?");

        if($req->execute(array($id))){
            return true;
        }
        else{
            return false;
        }
    }
    //****************************************************************************************H.E.L.P.E.R.S**********************************************************************************
    private function _strSelect(){
        $strToSelect = "";
        $i = 1;
        $length = count($this->_elms);
        foreach($this->_elms as $field){
            if($i < $length){
                $strToSelect .= "`" . $field . "`,";
            }
            else{
                if($field == "*"){
                    return $field;
                }
                $strToSelect .= "`" . $field . "`";
            }
            $i++;
        }
        return $strToSelect;
    }
    private function _strWhere(array $where){
        $strWhere = "WHERE ";
        $i = 0;
        foreach($where as $key => $value){
            if($i == 0){
                $strWhere .= "`" . $key . "`='" . $value . "'";
                $i++;
            }
            else{
                $strWhere .= " AND `" . $key . "`='" . $value . "'";
            }
        }
        return $strWhere;
    }
    private function _strBindInsert(){
        $strFields = "";//Enumeration of the fields in which the informations will be inserted
        $strBindedFields = "";
        $arrayBindedValues = array();
        $i = 1;
        $length = count($this->_elms);
        foreach($this->_elms as $key => $value){
            $binding = ":" . $key;
            $arrayBindedValues[$key] = $value;
            if($i < $length){
                $strFields .= "`" . $key . "`,";
                $strBindedFields .= $binding . ",";
            }
            else{
                $strFields .= "`" . $key . "`";
                $strBindedFields .= $binding;
            }
            $i++;
        }
        $this->_strFields = $strFields;
        $this->_strBindedFields = $strBindedFields;
        $this->_arrayBindedValues = $arrayBindedValues;
    }
    private function _strBindUpdate(){
        $strBindedFields = "";
        $arrayBindedValues = array();
        $i = 1;
        $length = count($this->_elms);
        foreach($this->_elms as $key => $value){
            $bind = ":" . $key;
            $arrayBindedValues[$bind] = $value;
            if($i < $length){
                $strBindedFields .= "`" . $key . "`=" . $bind . ",";
            }
            else{
                $strBindedFields .= "`" . $key . "`=" . $bind;
            }
            $i++;
        }
        $this->_strBindedFields = $strBindedFields;
        $this->_arrayBindedValues = $arrayBindedValues;
    }
    /*private function _check(){
        if(is_null($this->_table)){
            throw new \Exception("Error : table attribute cannot be null");
        }
        if(){
        }
        if(){
        }
    }*/
}