<?php

namespace app\Model;


use core\Model;

class TaskCount extends Model
{
    public $table = 'task';

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
    private $classNames = [
        'important'=>'重要',
        'notice'=>'注意',
        'work'=>'工作',
        'study'=>'学习',
        'hobby'=>'爱好',
        'culture'=>'修养',
        'fitness'=>'健身',
        'daily'=>'日常',
        'sleep'=>'睡眠',
        'waste'=>'无意义'
    ];

    // 月份
    public function CountTaskNun()
    {
        $sql = "SELECT MIN(end_at) as end_at FROM {$this->table}";
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        $data1 = $sth->fetch();
        $sql = "SELECT MAX(end_at) as end_at FROM {$this->table}";
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        $data2 = $sth->fetch();


        $ToStartMonth = $data1['end_at'];
        $ToEndMonth = strtotime('-1month',$data2['end_at']);


        $i  = false;
        $time = [];
        while( $ToStartMonth < $ToEndMonth ) {
            $NewMonth = !$i ? date('Y-m', strtotime('+0 Month', $ToStartMonth)) : date('Y-m', strtotime('+1 Month', $ToStartMonth));
            $ToStartMonth = strtotime( $NewMonth );
            $i = true;
            $time[] = $NewMonth;
        }

        return $time;
    }

    // 统计任务
    public function CountTask($month)
    {
        $className = $this->className;
        $classNames = $this->classNames;
        if($month){$t = strtotime($month);}else{$t = time();}

        $start_at =mktime(0,0,0,date('m',$t),1,date('Y',$t));
        $end_at =mktime(23,59,59,date('m',$t),date('t',$t),date('Y',$t));



        $map = ['start_at'=>['>='=>$start_at],'end_at'=>['<='=>$end_at]];
        $data = (new Task())->where($map)->select();
        $item = [];
        $val = [];
        foreach ($data as $value){
            $times = date('Y-m-d',$value['start_at']);
            $level = $className[$value['level']];
            $item['title'] = $value['title'];
            $item['start_at'] = $value['start_at'];
            $item['end_at'] = $value['end_at'];
            $val[$times][$level][] = $item;


        }

        $ditem = [];
        $sitem = [];
        $timei = 0;
        foreach ($val as $k=>$v){
            foreach ($v as $k2=>$v2){
                foreach ($v2 as $v3){
                    $timei += $v3['end_at']-$v3['start_at'];





                }

                $h = floor(($timei % (3600*24)) / 3600);
                $m = floor((($timei % (3600*24)) % 3600) / 60);
                if($h!='0'){
                    if($m!='0'){
                        $str = $h.'小时'.$m.'分';
                    }else{
                        $str = $h.'小时';
                    }

                }else{
                    $str = $m.'分';
                }
                $r[$k.$k2] = [
                    $k,
                    $classNames[$k2],
                    $str,
                    $k2
                ];


                $timei = 0;

            }

        }





        return $r;
    }



}