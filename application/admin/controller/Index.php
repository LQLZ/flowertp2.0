<?php
namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        if(empty(session('admin'))){
            $this->redirect('请先登录','adminlogin/adminlogin');
        }
        return $this->fetch();
    }
}
