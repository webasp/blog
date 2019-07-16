<?php

namespace app\Controller\Admin;


use app\Model\Photo;
use app\Model\PhotoCat;
use app\Model\PhotoItem;

class PhotoController extends BasicController
{

    public function index()
    {
       $data = (new Photo())->AdminPhotoList();
       $this->assign('photo',$data['data']);
       $this->assign('page',$data['page']);
       $this->display('admin/photo');
    }

    // 添加页面
    public function add()
    {
        $cat = (new PhotoCat())->select();
        $this->assign('token',$this->getToken());
        $this->assign('cat',$cat);
        $this->display('admin/photo-add');
    }

    // 保存数据
    public function save()
    {

        $data['title'] = $_POST['title'];
        $data['cat_id'] = $_POST['cat'];
        $data['thumb'] = $_POST['thumb'];
        $data['create_time'] = date('Y-m-d H:i:s',time());
        $data['description'] = $_POST['description'];
        $result = (new Photo())->add($data);

        if($result){
            $msg['id'] = $result;
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

    // 修改页面
    public function edit($id)
    {
        $data = (new Photo())->where(['id'=>$id])->find();
        $photo = (new PhotoItem())->where(['photo_id'=>$id])->select();
        $cat = (new PhotoCat())->select();

        $this->assign('token',$this->getToken());
        $this->assign('cat',$cat);
        $this->assign('photo',$photo);
        $this->assign('data',$data);
        $this->display('admin/photo-edit');
    }

    // 更新数据
    public function update()
    {
        $id = $_POST['id'];
        $data['title'] = $_POST['title'];
        $data['thumb'] = $_POST['thumb'];
        $data['cat_id'] = $_POST['cat'];
        $data['description'] = $_POST['description'];
        $result = (new Photo())->update($id,$data);

        if($result){
            $msg['id'] = $id;
            $msg['status'] = 1;
            $msg['info'] = '修改成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '修改败!';
        }
        $this->json($msg);
    }

    // 更新状态
    public function status($id)
    {
        $result = (new Photo())->field('id,status')->where(['id'=>$id])->find();
        if($result['status'] == 0){
            (new Photo())->update($id,['status'=>'1']);
            $age['id'] = $id;
            $age['status'] = 1;
            $age['info'] = '显示';
        }else{
            (new Photo())->update($id,['status'=>'0']);
            $age['id'] = $id;
            $age['status'] = 0;
            $age['info'] = '隐藏';
        }
        return $this->json($age);
    }

    // 添加照片
    public function photo_item_add($id = null)
    {
        $data['title'] = $_POST['title'];
        $data['images'] = $_POST['photo'];
        $data['photo_id'] = $id;
        $data['create_time'] = date('Y-m-d H:i:s',time());
        $result = (new PhotoItem())->add($data);
        (new Photo())->where(['id'=>$id])->setInc('pin',1);

        if($result){
            $msg['id'] = $result;
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

    //删除照片
    public function photo_item_del($id = null)
    {
        $data = (new PhotoItem())->where(['id'=>$id])->find();
        $result = (new PhotoItem())->del($id);
        (new Photo())->where(['id'=>$data['photo_id']])->setDec('pin',1);

        if($result){
            $msg['id'] = $data['photo_id'];
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }
}