<?php


namespace app\Controller;


use app\Model\Archives;
use app\Model\Cat;

class BookController extends BasicController
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