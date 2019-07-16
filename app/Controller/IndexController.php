<?php
namespace app\Controller;


use app\Model\Article;
use app\Model\Cat;
use app\Model\Tags;
use core\Parser;

class IndexController extends BasicController
{
    public function index()
    {
        $article = (new Article())->art();
        $this->assign('article',$article['data']);
        $this->assign('page',$article['page']);
        $this->display('home/index',['index'=>1]);
    }

    public function article($id){
        $parser = new Parser();
        $article = (new Article())->where(['id'=>$id])->find();
        $article['content'] = $parser->makeHtml($article['content']);
        $tags = (new Tags())->tags($id);
        $cat = (new Cat())->field('id,name')->where(['id'=>$article['cat']])->find();
        (new Article())->setInc('view',$id);
        $this->assign('cat',$cat);
        $this->assign('tags',$tags);
        $this->assign('article',$article);
        $this->display('home/article',['article'=>$id]);
    }


    public function search($w = '')
    {
        $w = urldecode($w);
        $art = (new Article())->field('article.id,article.cat,article.title,article.create_time,article.description,cat.name,cat.icon')
            ->join('cat',['cat.id','=','article.cat'])
            ->where(['title'=>['LIKE'=>'%'.$w.'%']])
            ->where(['status'=>1])
            ->select();
        $this->assign('w',$w);
        $this->assign('art',$art);
        $this->display('home/search');
    }
}