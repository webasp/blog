<?php


namespace app\Controller;


use app\Model\Archives;

class ItemController extends BasicController
{
    public function index()
    {
        $data = (new Archives())->getArchives();
        $this->assign('data',$data);
        $this->display('archives');
    }

    public function show()
    {
        $this->display('archives');
    }

}