<?php
/**
 * Created by PhpStorm.
 * User: Saeed
 * Date: 07/03/2017
 * Time: 08:49
 */

function userLoggedIn(){
    if (empty($_SESSION['email'])){
        header ("Location: login.php");
        exit();
    }
}