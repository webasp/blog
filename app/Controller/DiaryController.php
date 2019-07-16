<?php


namespace app\Controller;




use app\Model\Diaty;
use core\Config;

class DiaryController extends BasicController
{
    public function index()
    {
        $diary = (new Diaty())->where(['status'=>1])->order('create_at Desc')->paginate('30');
        $this->assign('url',Config::get('qiniu_url'));
        $this->assign('diary',$diary['data']);
        $this->assign('page',$diary['page']);
        $this->display('home/diary');
    }


}