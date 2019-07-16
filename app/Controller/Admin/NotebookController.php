<?php

namespace app\Controller\Admin;


use app\Model\Notebook;
use app\Model\NotebookItem;

class NotebookController extends BasicController
{
    public function index()
    {
        $book = (new Notebook())->select();
        $this->assign('token',$this->getToken());
        $this->assign('book',$book);
        $this->display('admin/notebook',null,false);
    }
    // 添加页面
    public function add()
    {
        $data['thumb'] = $_POST['thumb'];
        $data['title'] = $_POST['title'];
        $data['description'] = $_POST['description'];
        $data['create_at'] = date('Y-m-d H:i:s',time());
        $result = (new Notebook())->add($data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '添加成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '添加失败!';
        }
        $this->json($msg);
    }

    // 修改封面
    public function cover($id = null)
    {
        $book = (new Notebook())->where(['id'=>$id])->find();
        $item = (new NotebookItem())->where(['n_id'=>$id])->select();
        $this->assign('token',$this->getToken());
        $this->assign('book',$book);
        $this->assign('item',$item);
        $this->display('admin/notebook-cover',null,false);
    }

    // 更新封面
    public function UpdateCover($id = null)
    {
        $data['thumb'] = $_POST['thumb'];
        $data['title'] = $_POST['title'];
        $data['publish'] = $_POST['publish'];
        $data['description'] = $_POST['description'];
        $result = (new Notebook())->update($id,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '修改成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '修改失败!';
        }
        $this->json($msg);
    }

    // 修改页面
    public function edit($id = null,$active=null)
    {
        $book = (new Notebook())->where(['id'=>$id])->find();
        $item = (new NotebookItem())->where(['n_id'=>$id])->order('sort ASC')->select();
        $data = (new NotebookItem())->where(['id'=>$active])->find();
        $this->assign('book',$book);
        $this->assign('item',$item);
		$this->assign('token',$this->getToken());
        $this->assign('data',$data);
        $this->assign('active',$active);
        $this->display('admin/notebook-edit',null,false);
    }

    // 创建笔记
    public function createNotes($id = null)
    {
       $data['title'] = $_POST['title'];
       $data['sort'] = $_POST['sort'];
       $data['n_id'] = $id;
        $data['create_at'] = date('Y-m-d H:i:s',time());
       $result = (new NotebookItem())->add($data);
        if($result){
            (new Notebook())->where(['id'=>$id])->setInc('count',1);
            $msg['status'] = 1;
            $msg['info'] = '创建成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '创建失败!';
        }
        $this->json($msg);
    }



    // 更新数据
    public function update($id = null,$active=null)
    {
        $data['content'] = $_POST['content'];
        $result = (new NotebookItem())->update($active,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '保存成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '保存失败!';
        }
        $this->json($msg);
    }

    // 更新标题信息
    public function noteUpdate($id = null,$active=null)
    {
        $data['title'] = $_POST['title'];
        $data['id'] = $_POST['id'];
        $data['sort'] = $_POST['sort'];
        $result = (new NotebookItem())->update($active,$data);
        if($result){
            $msg['status'] = 1;
            $msg['info'] = '修改成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '修改失败!';
        }
        $this->json($msg);
    }

    // 删除数据
    public function noteDel($id = null,$active=null)
    {
        $result = (new NotebookItem())->del($active);
        if($result){
            (new Notebook())->where(['id'=>$id])->setDec('count',1);
            $msg['status'] = 1;
            $msg['info'] = '删除成功!';
        }else{
            $msg['status'] = 0;
            $msg['info'] = '删除失败!';
        }
        $this->json($msg);
    }

    // 删除Book
    public function bookDel($id = null)
    {
        $result = (new Notebook())->del($id);
        (new NotebookItem())->where(['n_id'=>$id])->del();
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