<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
        $data = Db::table('flower')->order('SelledNum desc')->paginate(5);
        $page=$data->render();
        $this->assign('page',$page);
        $this->assign('result',$data);
        return $this->fetch();
    }
}
