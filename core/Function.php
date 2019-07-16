<?php
function zy($str){
    return $str;
}

//功能：计算两个时间戳之间相差的日时分秒
//$begin_time 开始时间戳
//$end_time 结束时间戳
function timediff($begin_time,$end_time)
{
    if($begin_time < $end_time){
        $starttime = $begin_time;
        $endtime = $end_time;
    }else{
        $starttime = $end_time;
        $endtime = $begin_time;
    }

    //计算天数
    $timediff = $endtime-$starttime;
    $days = intval($timediff/86400);
    //计算小时数
    $remain = $timediff%86400;
    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;
    $str = '';
    if($days){
        $str .= $days.'天';
    }
    if($hours){
        $str .= $hours.'小时';
    }
    if($mins){
        $str .= $mins.'分';
    }
    return $str;
}

// 分钟换成 时间
function minsToTime($mins){
    if(empty($mins)){ return ; }
    $hours = sprintf("%02d",floor($mins/60));
    $mins = sprintf("%02d",$mins%60);
    return $hours.':'.$mins;
}


// 
function diaryImg($str)
{
   return explode(',',$str);
}
