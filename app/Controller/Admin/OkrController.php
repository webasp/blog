<?php

namespace app\Controller\Admin;




use app\Model\Okr;
use app\Model\OkrItem;

class OkrController extends BasicController
{
    // 显示项目任务列表
    public function index($type = 1)
    {
        $result = (new Okr())->where(['type'=>$type,'complete_at'=>0])->order('start_at')->select();

        $data = [];
        foreach($result as $value) {
            $value['count'] = (new OkrItem())->where(['okr_id'=>$value['id']])->count();
            $data[] = $value;
        }
        $this->assign('okr',$data);
        $this->display('admin/okr-task',null,false);
    }

    // 查看计划
    public function getOkr($id)
    {
        $okr = (new Okr())->where(['id'=>$id])->find();
        $okrTtem = (new OkrItem())->where(['okr_id'=>$okr['id']])->select();
        $this->assign('okr',$okr);
        $this->assign('item',$okrTtem);
        $this->display('admin/okr',null,false);
    }

    // 添加项目任务
    public function add()
    {
        $data['title'] = $_POST['title'];
        $data['type'] = $_POST['type'];
        $data['create_at'] = time();
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $result = (new Okr())->add($data);
        if($result){
            $msg['type'] = $data['type'];
            $msg['status'] = 1;
            $msg['info'] = '添加成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);
        exit();
    }

    // 修改项目窗口
    public function edit($id)
    {
        $data = (new Okr())->where(['id'=>$id])->find();
        $data['start_at'] = date('Y-m-d',$data['start_at']);
        $data['end_at'] = date('Y-m-d',$data['end_at']);
        $this->assign('data',$data);
        $this->display('admin/edit-okr',null,false);
        exit();
    }

    // 保存修改
    public function update($id)
    {
        $data['title'] = $_POST['title'];
        $data['type'] = $_POST['type'];
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $result = (new Okr())->update($id,$data);
        if($result){
            $msg['type'] = $data['type'];
            $msg['status'] = 1;
            $msg['info'] = '修改成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);
        exit();
    }

    // 删除
    public function del($id){
        $data = (new Okr())->where(['id'=>$id])->find();
        $result = (new Okr())->del($id);
        (new okrItem())->where(['okr_id'=>$id])->del();
        if($result){
            $msg['type'] = $data['type'];
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }

    // 完成项目任务
    public function complete($pid,$id){
        $data['complete_at'] = time();
        $result = (new ProjectTaskItem())->update($id,$data);
        if($result){
            $msg['pid'] = $pid;
            $msg['status'] = 1;
            $msg['info'] = '成功!';
        }else{
            $msg['pid'] = $pid;
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);
    }

    // 取消
    public function cancel($id){
        $data['complete_at'] = time();
        $result = (new Okr())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '操作成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '操作失败!';
        }
        $this->json($msg);
    }

}