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

class ArchivesController extends BasicController
{
    public function index()
    {
        $data = (new Archives())->getArchives();
        $this->assign('data',$data);
        $this->display('home/archives');
    }

}