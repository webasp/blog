<?php


namespace app\Controller;



use app\Model\TaskCount;
use core\Config;

class MotionController extends BasicController
{
    public function index($week = null)
    {

        if(isset($week)){
            $week = $week;
        }else{
          $week = intval(date("W"));
        }
        if($week == date("W")){
            $active = true;
        }else{
            $active = false;
        }

        $year = date("Y");
        $motion = Config::get('motion');
        $today = ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'];
        $weeks = date("W", mktime(0, 0, 0, 12, 28, date("Y")));
        $Totalweek = '<h3> '.$year.'年 <span onclick="layer.closeAll();" class="fr">关闭</span> </h3>';
        $Totalweek .= '<div class="Totalweek">';

        for ($i=1; $i <= $weeks; $i++) {
            if($week == $i){
                $Totalweek.= '<a class="active">'.'当前周'.'</a>';
            } elseif (date("W") == $i){
                $Totalweek.= '<a href="/motion/'.$i.'">'.'本周'.'</a>';
            }else{
                $Totalweek.= '<a href="/motion/'.$i.'">'.'第'.$i.'周'.'</a>';
            }
        }
        $Totalweek.= '</div>';

        if ($week > $weeks || $week <= 0)
        {
            $week = 1;
        }

        if ($week < 10)
        {
            $week = '0' . $week;
        }


        $timestamp['start'] = strtotime($year . 'W' . $week);
        $timestamp['end'] = strtotime('+1 week -1 day', $timestamp['start']);

        $str = $year . '年 第' . $week . '周 '.date("m-d",$timestamp['start']) .' ~ '. date("m-d", $timestamp['end']);

        for ($i=1; $i <= 7; $i++) {
            $onetime = mktime(0,0,0,date("m",$timestamp['start']),date("d",$timestamp['start'])-date("w",$timestamp['start'])+$i,date("Y",$timestamp['start']));
            $endtime = mktime(23,59,59,date("m",$timestamp['start']),date("d",$timestamp['start'])-date("w",$timestamp['start'])+$i,date("Y",$timestamp['start']));

            $onedate = date("Y-m-d H:i:s",$onetime);
            $enddate = date("Y-m-d H:i:s",$endtime);

            $data[$i]['today'] = date("d");
            $data[$i]['day'] = date("d",$endtime);
            $data[$i]['week'] = $today[date("w",$endtime)];
            $data[$i]['data'] = (new TaskCount())->getkeepMotion($onedate,$enddate);
        }

        $sumKeep = (new TaskCount())->sumKeep();

        $this->assign('keep',$sumKeep);
        $this->assign('active',$active);
        $this->assign('totalweek',$Totalweek);
        $this->assign('str',$str);
        $this->assign('motion',$motion);
        $this->assign('data',$data);



        $this->display('home/motion');
    }

}