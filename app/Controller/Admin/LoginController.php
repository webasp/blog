<?php

namespace app\Controller\Admin;


use core\Controller;
use app\Model\Admin;

class LoginController extends Controller
{
    public function index()
    {
        session_start();
        if(!empty($_SESSION['admin'])){
            header("Location: /admin");
        }

        $this->display('admin/login');
    }

    // 登录
    public function login()
    {

        session_start();
        if(isset($_POST['password']) && isset($_POST['username'])){
            $username = $_POST['username'];
            $password = md5($_POST['password']);
            $result = (new Admin())->where(['username'=>$username,'password'=>$password])->find();
            $_SESSION['admin'] = $result;
        }

        if(!empty($_SESSION['admin'])){
            $msg['status'] = 1;
            $msg['info'] = '登录成功！';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '登录失败！';
        }
        $this->json($msg);
    }
}