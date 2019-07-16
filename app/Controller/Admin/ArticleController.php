<?php

namespace app\Controller\Admin;


use app\Model\Article;
use app\Model\Cat;
use app\Model\Tags;
use app\Model\TagsMap;

class ArticleController extends BasicController
{
    public function index()
    {
        $article = (new Article())->field('id,cat,title,thumb,create_time,update_time,view,review,status,description')->order('id DESC')->paginate('20');
        $this->assign('article',$article['data']);
        $this->assign('page',$article['page']);
        $this->display('admin/article',null,false);
    }
        // 添加页面
        public function add()
        {
            $cat = (new Cat())->select();
            $tags = (new Tags())->select();
            $this->assign('cat',$cat);
            $this->assign('tags',$tags);
            $this->assign('token',$this->getToken());
            $this->display('admin/article-add',null,false);
        }



        // 保存数据
        public function save()
        {
            $data['title'] = $_POST['title'];
            $data['cat'] = $_POST['cat'];
            $data['thumb'] = $_POST['thumb'];
            $data['description'] = $_POST['description'];
            $data['content'] = $_POST['content'];
            $tags = isset($_POST['tags']) ? $_POST['tags']: false;
            $result = (new Article())->add($data);
            if($result){
                $this->setTage($result,$tags);
                $msg['id'] = $result;
                $msg['status'] = 1;
                $msg['info'] = '保存成功!';
            }else{
                $msg['status'] = 0;
                $msg['info'] = '保存失败!';
            }
            $this->json($msg);
        }


        // 修改页面
        public function edit($id = null)
        {
            $cat = (new Cat())->select();
            $tags = (new Tags())->select();
            $data = (new Article())->where(['id'=>$id])->find();
            $artMap = (new TagsMap())->where(['article_id'=>$id])->select();
            $tag = [];
            foreach ($artMap as $value){
                $tag[] = $value['tag_id'];
            }
            $this->assign('tag', json_encode($tag));
            $this->assign('cat',$cat);
            $this->assign('tags',$tags);
            $this->assign('token',$this->getToken());
            $this->assign('data',$data);
            $this->display('admin/article-edit',null,false);
        }

        // 更新数据
        public function update($id = null)
        {
            $data['title'] = $_POST['title'];
            $data['thumb'] = $_POST['thumb'];
            $data['cat'] = $_POST['cat'];
            $data['description'] = $_POST['description'];
            $data['content'] = $_POST['content'];
            $result = (new Article())->update($id,$data);
            $tags = isset($_POST['tags']) ? $_POST['tags']: false;
            if($tags){$this->setTage($id,$tags);}
            if($result){
                $msg['id'] = $id;
                $msg['status'] = 1;
                $msg['info'] = '修改成功!';
            }else{
                $msg['status'] = 0;
                $msg['info'] = '修改败!';
            }
            $this->json($msg);
        }

        // 更新状态
        public function status($id)
        {
            $result = (new Article())->field('id,status')->where(['id'=>$id])->find();
            if($result['status'] == 0){
                (new Article())->update($id,['status'=>'1']);
                $age['id'] = $id;
                $age['status'] = 1;
                $age['info'] = '显示';
            }else{
                (new Article())->update($id,['status'=>'0']);
                $age['id'] = $id;
                $age['status'] = 0;
                $age['info'] = '隐藏';
            }
            return $this->json($age);
        }

        // 删除数据
        public function del($id = null)
        {
            echo $id;
        }

    // 写入标签
    public function setTage($article_id = null,$tags = [])
    {
        if($article_id && is_array($tags)){
            $data = [];
            foreach($tags as $key=>$value){
                $data[$key]['tag_id'] = $value;
                $data[$key]['article_id'] = $article_id;
            }

            $tags = (new TagsMap())->where(['article_id'=>$article_id])->select();
            if($tags){
                (new TagsMap())->where(['article_id'=>$article_id])->del();
                (new TagsMap())->addAll($data);
            }else{
                (new TagsMap())->addAll($data);
            }
        }
    }

}