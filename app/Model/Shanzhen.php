<?php
namespace app\Model;


use core\Model;

class Shanzhen extends Model
{
    public $table = 'shenzhen';

    // 获取所有日记
    public function AllDiaryMonth()
    {
        $result =  $this->select();
        $data = [];
        foreach ($result as $value){
            $month = date('Y-m',$value['create_at']);
            $data[$month][] = $value;
        }
        return $data;
    }
}