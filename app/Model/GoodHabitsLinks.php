<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/22
 * Time: 21:38
 */

namespace app\Model;


use core\Model;

class GoodHabitsLinks extends Model
{
    public $table = 'good_habits_links';

    public function getHabitsLinks($Year = null)
    {

        $datas = $this->field('good_habits.title,good_habits.description as text,good_habits.status,good_habits_links.id,good_habits_links.week,good_habits_links.description,good_habits_links.status')
            ->join('good_habits',['good_habits.id','=','good_habits_links.good_habits_id'])
            ->where(['year'=>$Year])
            ->select();

        // 列出记录
        $HabitsAnnal = (new GoodHabitsAnnal())->getHabitsAnnal();
        $day = [];
        foreach ($HabitsAnnal as $value){
            $day[$value['good_habits_links_id']][$value['day']] = $value['correction'];
        }


        if(!$datas){
           return null;
        }else{
            $data = [];
            foreach($datas as $v){
                $v['day'] = isset($day[$v['id']])?$day[$v['id']]:'';
                $data[$v['week']][] = $v;
            }
            return $data;
        }


    }

}