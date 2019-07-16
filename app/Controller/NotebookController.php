<?php


namespace app\Controller;


use app\Model\Notebook;
use app\Model\NotebookItem;
use core\Parser;

class NotebookController extends BasicController
{
    // 笔记列表
    public function index()
    {
        $data = (new Notebook())->where(['publish'=>1])->order('id DESC')->paginate('15');
        $this->assign('book',$data['data']);
        $this->assign('page',$data['page']);
        $this->display('home/notebook',['notebook'=>1]);
    }

    // 封面
    public function cover($id)
    {

        $book = (new Notebook())->where(['id'=>$id])->find();
        $data = (new NotebookItem())->field('id,title,update_at')->where(['n_id'=>$id])->select();
        $this->assign('book',$book);
        $this->assign('data',$data);
        $this->display('home/notebook-item',['notebook-item'=>$id]);
    }

    // 详细内容
    public function detail($id)
    {
        $parser = new Parser();

        $data = (new NotebookItem())->field('notebook_item.id,notebook_item.n_id,notebook.title as n_title,notebook_item.title,notebook_item.content,notebook_item.create_at,notebook_item.update_at')->where(['notebook_item.id'=>$id])->join('notebook',['notebook_item.n_id'=>'notebook.id'])->find();
        $book = (new NotebookItem())->field('id,title,update_at')->where(['n_id'=>$data['n_id']])->select();
        $data['content'] = $parser->makeHtml($data['content']);
        $this->assign('data',$data);
        $this->assign('book',$book);
        $this->display('home/notebook-detail',['notebook-detail'=>$id]);
    }

}