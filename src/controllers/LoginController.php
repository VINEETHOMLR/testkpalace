<?php

namespace src\controllers;

use inc\Controller;
use inc\Root;
use src\lib\Router;
use src\lib\Helper as H;
use src\models\Login;

class LoginController extends Controller
{
    public function __construct(){  

        $this->layout  = 'login'; 
        $this->login   = (new Login);
    }

    public function actionIndex() {

        if(H::checkLogin()){
            $defaultController  = Root::params()['mvc']['defaults']['controller'];
            $defaultAction      = Root::params()['mvc']['defaults']['action'];
            Router::redirect([$defaultController,'']); 
            
        }
        
        return $this->render('login');
    } 

    public function actionLoginCheck(){

        $user=$this->cleanMe($_POST['username']);
        $pass=$this->cleanMe($_POST['password']);  

        if($user =="")
            return $this->sendMessage('error',Root::t('login', 'user_err'));
  
        if($pass =="")
            return $this->sendMessage('error',Root::t('login', 'pass_err')); 

        $login    = $this->login->login($user,$pass); 

        if($login=="true") 
            return $this->sendMessage('success',Root::t('login', 'suc_msg'));
        else
            return $this->sendMessage('error',Root::t('login', 'err')); 
    }
}