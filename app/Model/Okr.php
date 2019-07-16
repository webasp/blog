<?php
/**
 * User: dengyun
 * Date: 2018/4/15 22:47
 */

namespace app\Model;


use core\Model;

class Okr extends Model
{
    public $table = 'okr';

    // 获取年份
    public function getYear()
    {
        $MAX = $this->MAX('end_at');
        $MIN = $this->MIN('start_at');
        $Y1 = date('Y',$MAX);
        $Y2 = date('Y',$MIN);
        $sum = $Y1-$Y2;
        if($sum >= 1){
            for($i = 0;$i<=$sum;$i++){
                $Year[$i] = $Y2++;
            }
            return $Year;
        }
        return false;
    }

    // 获取任务
    public function getOkr($Year)
    {

        $min = mktime(0,0,0,1,1,$Year);
        $max = mktime(23,59,59,1,0,$Year+1);


        $map = ['start_at'=>['>='=>$min],'end_at'=>['<='=>$max],'type'=>['!='=>3]];


        $okr = $this->where($map)->select();


        if($okr){
            $idarr = [];
            foreach ($okr as $val) {$idarr[] = $val['id'];}
            $idstr = implode(",", $idarr);
        }

        $idstr = isset($idstr)?$idstr:'';
        $map2 = ['okr_id'=>['IN'=>$idstr]];
        $okrItem = (new OkrItem())->where($map2)->order('id')->select();



        $item =[];
        foreach ($okrItem as $key=>$value){
            $item[$value['okr_id']][] = $value;
        }

        $data = [];
        foreach ($okr as $value){
            $value['item'] = empty($item[$value['id']])?'':$item[$value['id']];
            $data[] = $value;
        }
        //print_r($data);


        $result = [];
        $season = ceil((date('n'))/3);
        while($season > 0) {
            $minT =  mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
            $maxT = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));

            foreach ($data as $value){
                if($value['start_at'] >= $minT and $value['end_at'] <= $maxT ){
                    $result[$season][] = $value;
                }

            }
            $season--;
        }


        return $result;

    }


    // 年度计划
    public function getYearOkr($Year)
    {

        $min = mktime(0,0,0,1,1,$Year);
        $max = mktime(23,59,59,1,0,$Year+1);



        $map = ['start_at'=>['>='=>$min],'end_at'=>['<='=>$max],'type'=>'2'];


        $okr = $this->where($map)->select();
        if($okr){
            $idarr = [];
            foreach ($okr as $val) {$idarr[] = $val['id'];}
            $idstr = implode(",", $idarr);
        }

        $idstr = isset($idstr)?$idstr:'';
        $map2 = ['okr_id'=>['IN'=>$idstr]];
        $okrItem = (new OkrItem())->where($map2)->order('id')->select();

        $item =[];
        foreach ($okrItem as $key=>$value){
            $item[$value['okr_id']][] = $value;
        }

        $data = [];
        foreach ($okr as $value){
            $value['item'] = empty($item[$value['id']])?'':$item[$value['id']];
            $data[] = $value;
        }

        return $data;

    }


}