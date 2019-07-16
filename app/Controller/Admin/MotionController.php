<?php

namespace app\Controller\Admin;


use app\Model\TaskCount;

class MotionController extends BasicController
{

    // 添加页面
    public function add()
    {
        $data['type'] = $_POST['type'];
        $data['number'] = $_POST['number'];
        $data['create_at'] = strtotime($_POST['create_at']);
        if((new TaskCount())->add($data)){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

}