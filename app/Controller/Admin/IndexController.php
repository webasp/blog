<?php

namespace app\Controller\Admin;
use app\Model\GoodHabits;
use app\Model\GoodHabitsAnnal;
use app\Model\GoodHabitsLinks;



class IndexController extends BasicController
{

    public function index($year = null)
    {

        $year = is_null($year)  ? date('Y') :$year;

        // 起始周
        $sweek = date('W',strtotime('2019-7-1'));
        // 当前 第几周
        $week = date('W');

        // 今年够用多少周
        $weeks = date("W", mktime(0, 0, 0, 12, 28, $year));

       // 坏习惯列表
        $Habits = (new GoodHabits())->getHabits();
        // 设置坏的习惯
        $HabitsLinks = (new GoodHabitsLinks())->getHabitsLinks($year);

        // 列出记录
        $this->assign('HabitsLinks',$HabitsLinks);
        $this->assign('Habits',$Habits);
        $this->assign('year',$year);
        $this->assign('week',$week);
        $this->assign('sweek',$sweek);
        $this->assign('weeks',$weeks);




    $this->display('admin/index');
    }

    // 添加坏习惯
    public function add()
    {
        $data['good_habits_id'] = $_POST['good_habits_id'];
        $data['description'] = $_POST['description'];
        $data['year'] = $_POST['year'];
        $data['week'] = $_POST['week'];

        $result = (new GoodHabitsLinks())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);

    }

    // 删除
    public function del($id)
    {
        $result = (new GoodHabitsLinks())->del($id);
        (new GoodHabitsAnnal())->where(['good_habits_links_id'=>$id])->del();
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
            exit;
    }

    // 添加
    public function addGoodHabits()
    {
        $data['title'] = $_POST['title'];
        $data['description'] = $_POST['description'];

        $result = (new GoodHabits())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

    // 删除
    public function delGoodHabits($id)
    {
        $result = (new GoodHabits())->del($id);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
        exit;
    }

    // 修改
    public function editGoodHabits()
    {
        $id = $_POST['id'];
        $data['title'] = $_POST['title'];
        $data['description'] = $_POST['description'];
        $data['status'] = $_POST['status'];
        $result = (new GoodHabits())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);

    }

    // 完成
    public function complete($id)
    {
        $data['status'] = 1;
        $result = (new GoodHabitsLinks())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '失败!';
        }
        $this->json($msg);

    }

    // annal
    public function annal()
    {

        $data['good_habits_links_id'] = $_POST['good_habits_links_id'];
        $data['day'] = $_POST['day'];
        $data['correction'] = $_POST['correction'];

        $result = (new GoodHabitsAnnal())->add($data);

        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

    // 退出
    public function logout()
    {
        unset($_SESSION['admin']);
        if(!isset($_SESSION['admin'])) {
            header("Location: /login");
        }
    }
}