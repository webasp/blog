<?php

namespace app\Model;


use core\Model;

class ProjectTask extends Model
{
    public $table = 'project_task';

    // 查询所有项目任务
    public function getProjectTask()
    {
        $task = $this->select();
        $data = [];
        foreach ($task as $key => $value){
            $data[$key]['id'] = $value['id'];
            $data[$key]['title'] = $value['title'];
            $data[$key]['progress'] = $this->progress($value['id']);
            $data[$key]['item'] =  $this->getProjectTaskItem($value['id']);
        }
        return $data;
    }

    // 计算进度
    public function progress($id)
    {
        $sum = (new ProjectTaskItem())->where(['pid'=>$id])->count();
        $row = (new ProjectTaskItem())->where(['pid'=>$id,'complete_at'=>['<>'=>null]])->count();
        if($row > 0){
            $progress = round( $row/$sum * 100 ,2);
        }else{
            $progress = 0;
        }
        return $progress;

    }

    // 项目item
    public function getProjectTaskItem($id)
    {
        $data = (new ProjectTaskItem())->where(['pid'=>$id])->order('complete_at,id')->limit(5)->select();
        return $data;
    }

}