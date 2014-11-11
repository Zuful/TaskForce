<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 18/10/14
 * Time: 03:46
 */

namespace Helper;

include_once(dirname(__FILE__) . "/Crud.php");

class Helper {
    private $_pdo;
    private $_crud;

    public function __CONSTRUCT(){
        $this->_pdo = $GLOBALS["pdo"];
        $this->_crud = new Crud($this->_pdo);
    }

    public function hydrate(array $water, array $dry, $output = false){
        foreach($water as $key => $val){
            $dry[$key] = $val;
        }

        if($output){
            return $dry;
        }

    }
} 