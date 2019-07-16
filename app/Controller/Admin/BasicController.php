<?php

namespace app\Controller\Admin;


use core\Controller;
use Qiniu\Auth;
use app\Model\Admin;

class BasicController extends Controller
{

    public function __construct()
    {

        session_start();
        if(empty($_SESSION['admin'])){
            header("Location: /login");
        }
    }


    // 获取七牛 token
    public function getToken()
    {
        require ROOT_PATH.'/qinui/autoload.php';

        // 用于签名的公钥和私钥
        $accessKey = 'rmCsWksvk6RuCqs0F7g_vbrwpsSW76uZiVjqL6lQ';
        $secretKey = 'KpLQHewntLjepii8D5DQGY34Rmv5v86LOciHrlyb';
        // 初始化签权对象
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'dengyun';
        return $auth->uploadToken($bucket);
    }

}