<?php
namespace app\Controller;

use app\Model\EventTaskTime;

class EventTaskTimeController extends BasicController
{
    private $className = [
        '1'=>'important',
        '2'=>'notice',
        '3'=>'work',
        '4'=>'study',
        '5'=>'hobby',
        '6'=>'culture',
        '7'=>'fitness',
        '8'=>'daily',
        '9'=>'sleep',
        '10'=>'waste'
    ];

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

        $today = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $weeks = date("W", mktime(0, 0, 0, 12, 28, date("Y")));
        $Totalweek = '<h3> '.$year.' 年 <span onclick="layer.closeAll();" class="fr">关闭</span> </h3>';
        $Totalweek .= '<div class="Totalweek">';

        for ($i=1; $i <= $weeks; $i++) {
            if($week == $i){
                $Totalweek.= '<a class="active"> 第'.$i.'周 </a>';
            } elseif (date("W") == $i){
                $Totalweek.= '<a href="/ett/'.$i.'">'.'当前周'.'</a>';
            }else{
                $Totalweek.= '<a href="/ett/'.$i.'">'.'第'.$i.'周 </a>';
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

        $str = 'Year '.$year . ' Week ' . $week . ' ( '.date("m-d",$timestamp['start']) .' ~ '. date("m-d", $timestamp['end']).' )';

        for ($i=1; $i <= 7; $i++) {
            $onetime = mktime(0,0,0,date("m",$timestamp['start']),date("d",$timestamp['start'])-date("w",$timestamp['start'])+$i,date("Y",$timestamp['start']));
            $endtime = mktime(23,59,59,date("m",$timestamp['start']),date("d",$timestamp['start'])-date("w",$timestamp['start'])+$i,date("Y",$timestamp['start']));


            $data[$i]['today'] = date("d");
            $data[$i]['day'] = date("d",$endtime);
            $data[$i]['week'] = $today[date("w",$endtime)];
            $data[$i]['data'] = (new EventTaskTime())->getKeepTask($onetime,$endtime);
        }

        $this->assign('className',$this->className);
        $this->assign('active',$active);
        $this->assign('totalweek',$Totalweek);
        $this->assign('str',$str);
        $this->assign('data',$data);
        $this->display('home/ett');
    }

    // 创建
    public function add()
    {
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        $data = (new EventTaskTime())->getKeepTask($start,$end);
        $this->assign('data',$data);
        $this->display('home/ett-add');
    }

    // 添加
    public function save()
    {
        $data['content'] = $_POST['content'];
        $data['minute'] = $_POST['minute'];
        $data['create_at'] = time();
        $result = (new EventTaskTime())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '添加成功';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '添加失败';
        }
        $this->json($msg);
    }

}