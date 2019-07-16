<?php

namespace app\Controller\Admin;



use app\Model\PlanItemReplay;

class PlanItemReplayController extends BasicController
{
    // 显示备注
    public function index($plan_id,$pid = null)
    {
        $map['plan_id'] = $plan_id;
        if($pid){
            $map['pid'] = $pid;
        }
        $result = (new PlanItemReplay())->where($map)->order('create_at DESC')->select();
        $this->assign('data',$result);
        $this->display('admin/plan-item-replay',null,false);
    }

    // 添加项目任务
    public function add()
    {
        $data['pid'] = $_POST['pid'];
        $data['plan_id'] = $_POST['plan_id'];
        $data['content'] = $_POST['content'];
        $data['create_at'] = time();
        $result = (new PlanItemReplay())->add($data);
        if($result){
            $msg['plan_id'] =  $data['plan_id'];
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
        $data = (new PlanItemReplay())->where(['id'=>$id])->find();
        $result = (new PlanItemReplay())->del($id);
        if($result){
            $msg['plan_id'] = $data['plan_id'];
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