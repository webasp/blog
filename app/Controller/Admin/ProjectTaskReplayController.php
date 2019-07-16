<?php

namespace app\Controller\Admin;


use app\Model\ProjectTaskItem;
use app\Model\ProjectTaskReplay;

class ProjectTaskReplayController extends BasicController
{
    // 显示备注
    public function index($fid,$pid = null)
    {
        $map['fid'] = $fid;
        if($pid){
            $map['pid'] = $pid;
        }
        $result = (new ProjectTaskReplay())->where($map)->order('create_at DESC')->select();
        $this->assign('data',$result);
        $this->display('admin/project-item-replay',null,false);
    }

    // 添加项目任务
    public function add()
    {
        $data['fid'] = $_POST['fid'];
        $data['pid'] = $_POST['pid'];
        $data['content'] = $_POST['content'];
        $data['create_at'] = time();
        $result = (new ProjectTaskReplay())->add($data);
        if($result){
            $msg['fid'] =  $data['fid'];
            $msg['pid'] =  $data['pid'];
            $msg['status'] = 1;
            $msg['info'] = '添加成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);
        exit();
    }

    // 删除
    public function del($id){
        $data = (new ProjectTaskReplay())->where(['id'=>$id])->find();
        $result = (new ProjectTaskReplay())->del($id);
        if($result){
            $msg['fid'] = $data['fid'];
            $msg['pid'] = $data['pid'];
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }


}