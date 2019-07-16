<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/20
 * Time: 11:27
 */

namespace app\Controller;


use app\Model\Tags;

class TagsController extends BasicController
{
    public function index($tag = 'Reading Notes')
    {
        $tag = urldecode($tag);
        $tagModel = new Tags();
        $tags = $tagModel->order('click DESC')->select();
        $article = $tagModel->tagsAar($tag);
        $tagModel->where(['name'=>$tag])->setInc('click',1);
        $this->assign('tags',$tags);
        $this->assign('tag',$tag);
        $this->assign('article',$article['data']);
        $this->assign('page',$article['page']);
        $this->display('home/tags');

    }

}