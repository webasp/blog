<?php

namespace app\Controller\Admin;

use core\Config;
use app\Model\Diaty;
use core\Calendar;

class DiaryController extends BasicController
{
    // 首页
    public function index()
    {
        $this->assign('token',$this->getToken());
        $this->display('admin/diary-index',null,false);

     }
    // 最近 30 天日志
    public function getDiaryList()
    {
        $result = (new Diaty())->order('id desc')->limit(30)->select();
        $this->assign('data',$result);
        $this->assign('url',Config::get('qiniu_url'));
        $this->display('admin/diary-item',null,false);
    }


    // 添加页面
    public function add($id = null)
    {
        echo $id;
    }

    // 修改页面
    public function edit($id = null)
    {
        echo $id;
    }

    // 保存数据
    public function save()
    {
        $data['type'] = $_POST['type'];
        $data['create_at'] = time();
        $data['image'] = $_POST['image'];
        $data['content'] = $_POST['content'];

        if(!empty($_POST['id'])){
            $id = $_POST['id'];
            $result = (new Diaty())->update($id,$data);
        }else{
            $result = (new Diaty())->add($data);
            $id = $result;
        }

        $msg['id'] = $id;

        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);

    }

    // 更新数据
    public function update($id = null)
    {
        $a = (new Diaty())->where(['id'=>$id])->find();
        if($a['status']){
            $data['status'] = 0;
           
        }else{
           $data['status'] = 1;
        }
        $result = (new Diaty())->update($id,$data); 

        if($result){
            $msg['status'] = 1;
            $msg['info'] = '设置成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '设置失败!';
        }
        $this->json($msg);
        
    }

    // 删除数据
    public function del($id = null)
    {
          if($id){
            $data['status'] = 0;
           
        }else{
           $data['status'] = 1;
        }
        $result = (new Diaty())->where(['id'=>$id])->del();

        if($result){
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }
}