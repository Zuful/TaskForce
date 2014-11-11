<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 11/11/14
 * Time: 10:39
 */

namespace Controller;

//include_once(dirname(__FILE__) . "/../model/ModelTaskForce.php");
include_once(dirname(__FILE__) . "/../metier/UserInterface.php");

use Metier\UserInterface;
use Model\ModelUser;

class MainController {
    private $_ui;
    private $_user;
    private $_isSignedIn;

    public function __CONSTRUCT(ModelUser $user){
        $this->_user = $user;
        $this->_ui = new UserInterface($user);
        $this->_isSignedIn = (is_null($user->id))?false:true;
    }

    public function getUi(){
        return $this->_ui;
    }
} 