<?php
/**
 * Created by PhpStorm.
 * User: Yam's
 * Date: 02/11/14
 * Time: 12:28
 */
session_start();
session_destroy();
header("Location:signIn.php");