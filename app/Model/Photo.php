<?php
namespace app\Model;


use core\Model;

class Photo extends Model
{
    public $table = 'photo';

    public function PhotoList($cat_id = null)
    {

        $map = [];
        $map['photo.status'] = 1;
        if($cat_id){
            $map['photo.cat_id'] = $cat_id;
        }

        $data = $this->field('
            photo.id,
            photo.cat_id,
            photo.thumb,
            photo.create_time,
            photo.view,
            photo.review,
            photo.title,
            photo.description,
            photo.status,
            photo_cat.title as cat_title,
            photo.pin')
            ->join('photo_cat',['photo.cat_id','=','photo_cat.id'],'left')
            ->where($map)
            ->paginate();
        return $data;
    }


    public function AdminPhotoList($cat_id = null)
    {

        $data = $this->field('
            photo.id,
            photo.cat_id,
            photo.thumb,
            photo.create_time,
            photo.view,
            photo.review,
            photo.title,
            photo.description,
            photo.status,
            photo_cat.title as cat_title,
            photo.pin')
            ->join('photo_cat',['photo.cat_id','=','photo_cat.id'],'left')
            ->paginate();
        return $data;
    }

    public function item($id){
        $data = $this->field('
        photo.id,
        photo.cat_id,
        photo.thumb,
        photo.create_time,
        photo.view,
        photo.review,
        photo.title,
        photo.description,
        photo_cat.title as cat_title')
            ->join('photo_cat',['photo.cat_id','=','photo_cat.id'],'left')
            ->where(['photo.id'=>$id])
            ->find();
        return $data;
    }
}