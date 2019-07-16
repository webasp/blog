<?php

namespace app\Model;


use core\Model;

class Motion extends Model
{
    public $table = 'motion';


    // 获取运动记录
    public function getMotion($onedate,$enddate)
    {
        $sql = sprintf("SELECT * FROM $this->table  WHERE create_at > %s AND create_at < %s",$onedate,$enddate);
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }

    // 统计月份
    public function countMotionMonth()
    {
        $sql = "SELECT MIN(create_at) as create_at FROM {$this->table}";
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        $data1 = $sth->fetch();
        $sql = "SELECT MAX(create_at) as create_at FROM {$this->table}";
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        $data2 = $sth->fetch();


        $ToStartMonth = $data1['create_at'];
        $ToEndMonth = strtotime('-1month',$data2['create_at']);

        $i  = false;
        while( $ToStartMonth < $ToEndMonth ) {
            $NewMonth = !$i ? date('Y-m', strtotime('+0 Month', $ToStartMonth)) : date('Y-m', strtotime('+1 Month', $ToStartMonth));
            $ToStartMonth = strtotime( $NewMonth );
            $i = true;
            $time[] = $NewMonth;
        }

        return $time;
    }



}