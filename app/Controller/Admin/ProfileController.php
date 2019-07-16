<?php

namespace app\Controller\Admin;


class ProfileController extends BasicController
{
    public function index()
    {
        $this->display('admin/profile',null,false);
    }

    // 更新数据
    public function update()
    {

    }


}