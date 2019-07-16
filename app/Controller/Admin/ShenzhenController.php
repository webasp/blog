<?php

namespace app\Controller\Admin;
use app\Model\Shanzhen;


class ShenzhenController extends BasicController
{

    public function index()
    {
        $start_time = strtotime('2013-10');
        $current_time = time();

        $i  = false;
        $data = [];
        while( $start_time < $current_time ) {
            $NewMonth = !$i ? date('Y-m', strtotime('-0 Month', $current_time)) : date('Y-m', strtotime('-1 Month', $current_time));
            $NewYesr = date('Y',strtotime($NewMonth));
            $current_time = strtotime($NewMonth);
            $i = true;
            $data[$NewYesr][$NewMonth] = date('m',strtotime($NewMonth));
        }
        $diaty = (new Shanzhen())->AllDiaryMonth();


        $this->assign('diaty',$diaty);
        $this->assign('data',$data);
        $this->display('admin/shenzhen');
    }

    // 保存
    public function save()
    {
        $data['title'] = $_POST['title'];
        $data['content'] = $_POST['content'];
        $data['create_at'] = strtotime($_POST['create_at']);
        if((new Shanzhen())->add($data)){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }


}