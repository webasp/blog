<?php
namespace app\Model;


use core\Model;

class Diaty extends Model
{
    public $table = 'diary';

    // 获取今天日记
    public function getDiary()
    {
        $today = time();
        $start_time = mktime('0','0','0',date('m',$today),date('d',$today),date('Y',$today));
        $end_time = mktime('23','59','59',date('m',$today),date('d',$today),date('Y',$today));
        $sql = sprintf("SELECT * FROM  `%s` where create_at between %s AND %s", $this->table,$start_time,$end_time);
        $sth = $this->dbHandle->prepare($sql);
        $sth->execute();
        $this->destroy();
        return $sth->fetch();
    }


}