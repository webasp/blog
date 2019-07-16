<?php

namespace app\Controller\Admin;


use app\Model\ProjectTask;
use app\Model\ProjectTaskItem;

class ProjectTaskController extends BasicController
{
    // 显示项目任务列表
    public function index($id)
    {
        $data = (new ProjectTask())->where(['id'=>$id])->find();
        $item = (new ProjectTaskItem())->where(['pid'=>$id])->order('complete_at,id')->select();
        $progress = (new ProjectTask())->progress($id);
        $this->assign('item',$item);
        $this->assign('pid',$id);
        $this->assign('data',$data);
        $this->assign('progress',$progress);
        $this->display('admin/project-task-list',null,false);
    }

    // 添加项目任务
    public function add()
    {
        $data['title'] = $_POST['title'];
        $data['pid'] = $_POST['pid'];
        $data['create_at'] = time();
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $data['content'] = $_POST['content'];
        $result = (new ProjectTaskItem())->add($data);
        if($result){
            $msg['pid'] = $data['pid'];
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
        $data = (new ProjectTaskItem())->where(['id'=>$id])->find();
        $data['start_at'] = date('Y-m-d\TH:i',$data['start_at']);
        $data['end_at'] = date('Y-m-d\TH:i',$data['end_at']);
        $this->assign('data',$data);
        $this->display('admin/edit-porject-item',null,false);
        exit();
    }
    // 保存修改
    public function update($id)
    {
        $data['title'] = $_POST['title'];
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $data['content'] = $_POST['content'];
        $result = (new ProjectTaskItem())->update($id,$data);
        if($result){
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
        $result = (new ProjectTaskItem())->del($id);
        if($result){
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
    public function cancel($pid,$id){
        $data['complete_at'] = 'null';
        $result = (new ProjectTaskItem())->update($id,$data);
        if($result){
            $msg['pid'] = $pid;
            $msg['status'] = 1;
            $msg['info'] = '取消成功!';
        }else{
            $msg['pid'] = $pid;
            $msg['status'] = 0;
            $msg['info'] = '取消失败!';
        }
        $this->json($msg);
    }

}