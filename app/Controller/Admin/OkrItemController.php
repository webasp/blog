<?php

namespace app\Controller\Admin;




use app\Model\Okr;
use app\Model\OkrItem;

class OkrItemController extends BasicController
{
    // 显示项目任务列表
    public function index($type = 1)
    {
        $result = (new Okr())->where(['type'=>$type])->order('start_at')->select();
        $this->assign('okr',$result);
        $this->display('admin/okr-task',null,false);
    }

    // 查看计划窗口
    public function getOkr($id)
    {
        $okr = (new Okr())->where(['id'=>$id])->find();
        $okrTtem = (new okrItem())->where(['okr_id'=>$okr['id']])->select();
        $this->assign('okr',$okr);
        $this->assign('item',$okrTtem);
        $this->display('admin/okr',null,false);
    }

    // 查看计划窗口
    public function getOkrItemList($okr_id)
    {
        $okrTtem = (new okrItem())->where(['okr_id'=>$okr_id])->select();
        $this->assign('item',$okrTtem);
        $this->display('admin/okr-item-list',null,false);
    }

    // POST
    public function post()
    {
        $data['okr_id'] = $_POST['okr_id'];
        $data['content'] = $_POST['content'];
        if($id = $_POST['id']){
            $result = (new okrItem())->update($id,$data);
        }else{
            $data['create_at'] = time();
            $result = (new okrItem())->add($data);
        }

        if($result){
            $msg['okr_id'] = $data['okr_id'];
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);

    }

    // 完成项目任务
    public function complete(){

        $id = $_POST['item_id'];
        unset($_POST['item_id']);
        $result = (new OkrItem())->update($id,$_POST);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);
    }

    // 取消
    public function cancel($id){
        $data['complete_at'] = 'null';
        $Item = (new okrItem())->where(['id'=>$id])->find();
        $result = (new okrItem())->update($id,$data);
        if($result){
            $msg['okr_id'] = $Item['okr_id'];
            $msg['status'] = 1;
            $msg['info'] = '取消成功!';
        }else{
            $msg['okr_id'] = $data['okr_id'];
            $msg['status'] = 0;
            $msg['info'] = '取消失败!';
        }
        $this->json($msg);
    }

    // 删除
    public function del($id){
        $data = (new okrItem())->where(['id'=>$id])->find();
        $result = (new okrItem())->del($id);
        if($result){
            $msg['okr_id'] = $data['okr_id'];
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }

}