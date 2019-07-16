<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/22
 * Time: 21:38
 */

namespace app\Model;


use core\Model;

class GoodHabits extends Model
{
    public $table = 'good_habits';

    public function getHabits()
    {
        return $this->where(['status'=>1])->select();
    }



}


