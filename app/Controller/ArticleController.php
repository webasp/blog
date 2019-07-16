<?php
/**
 * Created by PhpStorm.
 * User: dengyun
 * Date: 2017/8/20
 * Time: 0:19
 */

namespace app\Controller;


use app\Model\Archives;
use app\Model\Cat;

class ArticleController extends BasicController
{
    public function index()
    {
        $data = (new Archives())->getArchives();
        $this->assign('data',$data);
        $this->display('home/article');
    }

    public function show()
    {
        $this->display('home/article');
    }

}