<?php

namespace app\Controller\Admin;


class SettingsController extends BasicController
{
    public function index()
    {
        $this->display('admin/settings',null,false);
    }

    // 更新数据
    public function update()
    {

    }


}