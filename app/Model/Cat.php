<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/23
 * Time: 9:04
 */

namespace app\Model;


use core\Model;

class Cat extends Model
{
    public $table = 'cat';

    public function getCat()
    {
        $data = [];
        foreach ($this->select() as $value){
            $count = $this->table('article')->where(['cat'=>$value['id']])->where(['status'=>1])->count();
            $value['count'] = $count;
            $data[] = $value;
        }
        return $data;
    }
}