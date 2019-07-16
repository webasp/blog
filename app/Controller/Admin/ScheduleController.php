<?php

namespace app\Controller\Admin;

use core\Controller;
use app\Model\Task;
use app\Model\TaskCat;


class ScheduleController extends Controller
{
    private $className = [
        '1'=>'important',
        '2'=>'notice',
        '3'=>'work',
        '4'=>'study',
        '5'=>'hobby',
        '6'=>'culture',
        '7'=>'fitness',
        '8'=>'daily',
        '9'=>'sleep',
        '10'=>'waste'
    ];

    public function index()
        {
                $this->display('admin/schedule',null,false);
        }
        // 获取今天日程
        public function getTask()
        {

            $className = $this->className;
            $start_time = $_POST['start']-8*3600;
            $end_time = $_POST['end']-8*3600;

            $map = ['start_at'=>['>='=>$start_time],'end_at'=>['<='=>$end_time]];
            $result = (new Task())->where($map)->select();

            $data = [];
            foreach($result as $key=>$value){
                $complete = '';
                if($value['complete_at'] != 0){
                    $complete = 'complete';
                }
                $data[$key]['type'] = $value['level'];
                $data[$key]['id'] = $value['id'];
                $data[$key]['title'] = $value['title'];
                $data[$key]['start'] = date("Y-m-d H:i:s", $value['start_at']);
                $data[$key]['end'] = date("Y-m-d H:i:s", $value['end_at']);
                $data[$key]['className'] = $className[$value['level']].' '.$complete;
                $data[$key]['complete'] = $value['complete_at'];
            }
            return $this->json($data);
        }

        // 添加日程记录
        public function add()
        {

             $className = array_keys($this->className,$_POST['className']);
            $data['title'] = trim($_POST['title']);
            $data['create_at'] = time();
            $data['start_at'] = strtotime($_POST['start']);
            if(isset($_POST['end'])){
                $data['end_at'] = strtotime($_POST['end']);
            }else{
                $data['end_at'] = ($data['start_at']+3600);
            }


            $data['level'] = $className[0];




            if($id = (new Task())->add($data)){
                $info['status'] = 1;
                $info['id'] = $id;
                $info['title'] = $data['title'];
                $info['info'] = '添加成功';
                $info['start'] = date('Y-m-d H:i',$data['start_at']);
                $info['end'] = date('Y-m-d H:i',$data['end_at']);
                $info['className'] = $_POST['className'];
            }else{
                $info['status'] = 0;
                $info['info'] = '添加失败';
            }

            $this->json($info);

        }

        // 修改
        public function edit()
        {
            if(!$id = $_POST['id']){
                return false;
            }

            $r = (new Task())->where(['id'=>$id])->find();


            if(isset($_POST['title'])){
                $data['title'] = $_POST['title'];
            }

            if(isset($_POST['start'])){
                $data['start_at'] = strtotime($_POST['start']);
            }

            if(isset($_POST['end'])){
                $data['end_at'] = strtotime($_POST['end']);
            }

            if(isset($_POST['complete'])){
                $data['complete_at'] = strtotime($_POST['complete']);
            }


            if((new Task())->update($id,$data)){
                $info['status'] = 1;
                $info['info'] = '修改成功';
            }else{
                $info['status'] = 0;
                $info['info'] = '修改失败';
            }

            $this->json($info);

        }

        // 删除
        public function del()
        {
            if((new Task())->del($_POST['id'])){
                $info['status'] = 1;
                $info['info'] = '删除成功';
            }else{
                $info['status'] = 0;
                $info['info'] = '删除失败';
            }
            $this->json($info);
        }

        // 获取cat
        public function getCat()
        {
            $data = (new TaskCat())->select();
            $this->json($data);
        }

        // 添加 cat
        public function addCat()
        {

            $data['title'] = trim($_POST['title']);
            $data['class'] = trim($_POST['class']);

            if($id = (new TaskCat())->add($data)){
                $info['status'] = 1;
                $info['id'] = $id;
                $info['title'] = $data['title'];
                $info['class'] = $data['class'];
                $info['info'] = '添加成功';
                }else{
                $info['status'] = 0;
                $info['info'] = '添加失败';
            }

            $this->json($info);
        }

        // 删除 cat
         public function delCat()
         {
             if(!isset($_POST['id'])){
                 exit('ID 错误');
                 return false;
             }
             if((new TaskCat())->del($_POST['id'])){
                 $info['status'] = 1;
                 $info['info'] = '删除成功';
             }else{
                 $info['status'] = 0;
                 $info['info'] = '删除失败';
             }
             $this->json($info);
         }

}