<?php

namespace app\Controller\Admin;

use app\Model\Diaty;
use app\Model\Okr;
use app\Model\okrItem;
use app\Model\ProjectTaskReplay;
use app\Model\Task;
use app\Model\ProjectTask;
use app\Model\ProjectTaskItem;


class TaskController extends BasicController
{
    public function index()
    {
        $diaty = (new Diaty())->getDiary();
        $project = (new ProjectTask())->getProjectTask();
        $this->assign('diaty',$diaty);
        $this->assign('project',$project);
        $this->display('admin/task',null,false);
    }
    // 添加页面
    public function add()
    {
        $data['create_at'] = time();
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $data['title'] = $_POST['title'];
        $data['level'] = $_POST['level'];
        $result = (new Task())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }
    // 完成任务
    public function TodayComplete($id)
    {
        $data['complete_at'] = time();
        $result = (new Task())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '操作成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '操作失败!';
        }
        $this->json($msg);
    }
    // 编辑今日任务窗口
    public function TodayTaskEdit($id = null)
    {
        $data = (new Task())->where(['id'=>$id])->find();
        $data['start_at'] = date('Y-m-d\TH:i',$data['start_at']);
        $data['end_at'] = date('Y-m-d\TH:i',$data['end_at']);
        $this->assign('data',$data);
        $this->display('admin/task-item-edit',null,false);
        exit();
    }
    // 更新今日任务
    public function TodayTaskUpdate($id = null)
    {
      $data['title'] = $_POST['title'];
      $data['level'] = $_POST['level'];
      $data['start_at'] = strtotime($_POST['start_at']);
      $data['end_at'] = strtotime($_POST['end_at']);
      $result = (new Task())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '更新成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '更新失败!';
        }
        $this->json($msg);
        exit();
    }
    // 取消
    public function TodayTaskCancel($id = null)
    {
        $data['complete_at'] = '';
        $result = (new Task())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '取消成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '取消失败!';
        }
        $this->json($msg);
        exit();
    }
    // 删除
    public function TodayTaskDel($id = null)
    {
        $result = (new Task())->del($id);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
        exit();
    }
    // 创建项目任务
    public function projectAdd()
    {
        $data['title'] = $_POST['title'];
        $data['level'] = $_POST['level'];
        $data['create_at'] = time();
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $result = (new ProjectTask())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);

    }
    // 项目任务修改页面
    public function addProjectTask($id)
    {
        $data = (new ProjectTask())->where(['id'=>$id])->find();
        $data['start_at'] = date('Y-m-d\TH:i',$data['start_at']);
        $data['end_at'] = date('Y-m-d\TH:i',$data['end_at']);
        $this->assign('data',$data);
        $this->display('admin/project-task-edit',null,false);
    }
    // 删除项目任务
    public function delProjectTask($id){
        $del = (new ProjectTask())->where(['id'=>$id])->del();
        (new ProjectTaskItem())->where(['pid'=>$id])->del();
        (new ProjectTaskReplay())->where(['fid'=>$id])->del();
        if($del){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }
    // 更新项目
    public function updateProjectTask($id){
        $data['title'] = $_POST['title'];
        $data['level'] = $_POST['level'];
        $data['start_at'] = strtotime($_POST['start_at']);
        $data['end_at'] = strtotime($_POST['end_at']);
        $result = (new ProjectTask())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '更新成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '更新失败!';
        }
        $this->json($msg);
        exit();
    }
    // 项目 item 页面
    public function ProjectTaskItem($id)
    {
        $data = (new ProjectTask())->where(['id'=>$id])->find();
        $item = (new ProjectTaskItem())->where(['pid'=>$id])->select();
        $progress = (new ProjectTask())->progress($id);
        $this->assign('item',$item);
        $this->assign('pid',$id);
        $this->assign('data',$data);
        $this->assign('progress',$progress);
        $this->display('admin/task-item',null,false);
    }
    // 获取今日任务
    public function TodayTask($today = null)
    {
        $data = (new Task())->getTodayTask($today);
        $html = '<ul class="list-group" style="overflow:auto;max-height:320px;">';
        foreach ($data as $value){
            $html .= '<li class="list-group-item ';
            $html .= $value['complete_at']?'complete':'';
            $html .= '">';
            $html .= $value['title'];
            $html .= '<span class="editTask" data-target="updateTask" data-id="'.$value['id'].'"><i class="fa fa-pencil fa-fw"></i></span>';
            if(empty($value['complete_at'])){
                $html .= '<span class="btn btn-primary btn-xs pull-right" data-target="complete" data-id="'.$value['id'].'"> 完成 </span>';
                $html .= '<span class="pull-right time">';
                $html .= timediff($value['start_at'],$value['end_at']);
                $html .= '</span>';
            }else{
                $html .= '<span class="pull-right complete">';

                $html .= '</span>';
            }

            $html .= '</li>';
        }
        $html .= '</ul>';
        if(!$data){
            $html .= '<div class="task-empty"> 还没有添加任务 </div>';
        }
        exit($html);
    }
    public function TodayTimeline()
    {
        $data = (new Task())->getTodayTimeline();
        print_r($data);
    }
}