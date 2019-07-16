<?php

namespace app\Model;


use core\Model;

class People extends Model
{
    public $table = 'people';

    // 获取带格式的任务榜样
    public function getPeople()
    {
       $data = $this->select();

       foreach($data as $value){
           switch ($value['type']){
               case 1;

                   $people[1]['title'] = '近期榜样';
                   $people[1]['people'][] = $value;
                   break;
               case 2;
                   $people[2]['title'] = '长期榜样';
                   $people[2]['people'][] = $value;
                   break;
               case 3;
                   $people[3]['title'] = '终极榜样';
                   $people[3]['people'][] = $value;
                   break;
           }
       }
       return $people;
    }

}