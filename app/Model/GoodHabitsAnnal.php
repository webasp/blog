<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/22
 * Time: 21:38
 */

namespace app\Model;


use core\Model;

class GoodHabitsAnnal extends Model
{
    public $table = 'good_habits_annal';

    public function getHabitsAnnal()
    {
        return $this->select();
    }



}