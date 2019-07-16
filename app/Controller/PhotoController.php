<?php


namespace app\Controller;


use app\Model\Photo;
use app\Model\PhotoCat;
use app\Model\PhotoItem;

class PhotoController extends BasicController
{
    public function index($cat_id = null)
    {
        $data = (new Photo())->PhotoList($cat_id);
        $cat = (new PhotoCat())->select();
        $this->assign('cat',$cat);
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display('home/photo');
    }

    public function show($id = null)
    {
        (new Photo())->where(['id'=>$id])->setInc('view',1);
        $data = (new Photo())->item($id);
        $PhotoItim = (new PhotoItem())->where(['photo_id'=>$id])->select();
        $this->assign('item',$PhotoItim);
        $this->assign('data',$data);
        $this->display('home/photo-show');
    }

}