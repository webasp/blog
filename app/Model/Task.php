<?php

namespace app\Model;


use core\Model;

class Task extends Model
{
    public $table = 'task';


    // 获取今日任务
    public function getTodayTask($today = null)
    {
        if(!$today){
            $today = time();
        }else{
            $today = strtotime($today);
        }

        $start_time = mktime('0','0','0',date('m',$today),date('d',$today),date('Y',$today));
        $end_time = mktime('23','59','59',date('m',$today),date('d',$today),date('Y',$today));
        $map = ['start_at'=>['>='=>$start_time],'end_at'=>['<='=>$end_time]];
        return $this->where($map)->order('start_at')->select();

    }


    // 获取间轴内容
    public function getTodayTimeline($today = null)
    {
        if(!$today){$today = time();}
        $data =  $this->getTodayTask($today);

        $html = '';
        foreach($data as $key=>$value){
            $top = date('H.i',$value['start_at']) * 60;
            $height = date('H.i',$value['end_at']) - date('H.i',$value['start_at']);
            $html .= '<li class="taskitem" style="top:'.$top.'px;height:'.$height.'px">';
            $html .= '<p class=""> <span><b> '. $value['title'] .' </b> <i>'.date('H:i',$value['start_at']).'-'.date('H:i',$value['end_at']).'</i></span> <i class="tomato pull-right"></i> </p>';
            $html .= '<p><i class="tomato pull-right"></i></p>';
            $html .= '</li>';
        }
        return $html;
    }

}