<?php

namespace app\Controller\Admin;


class ItemController extends BasicController
{
    public function index()
    {
        $this->display('admin/item-index',null,false);
    }
    // 添加页面
    public function add($id = null)
    {
        echo $id;
    }

    // 修改页面
    public function edit($id = null)
    {
        echo $id;
    }

    // 保存数据
    public function save($id = null)
    {
        echo $id;
    }

    // 更新数据
    public function update($id = null)
    {
        echo $id;
    }

    // 删除数据
    public function del($id = null)
    {
        echo $id;
    }
}