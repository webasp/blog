<?php

namespace app\Model;


use core\Model;

class EventTaskTime extends Model
{
    public $table = 'task';

    // 获取每天日志记录
    public function getKeepTask($onedate,$enddate)
    {
       $sql = sprintf("SELECT * FROM $this->table  WHERE start_at >= %s AND start_at <= %s ORDER BY start_at",$onedate,$enddate);
       $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }

}