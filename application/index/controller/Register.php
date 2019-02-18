<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Register extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function doregister()
    {
        $param = input('post.');
        if(empty($param['email'])){
            $this->error('email不能为空');
        }
        if(empty($param['passw1'])){
            $this->error('密码不能为空');
        }
        if(empty($param['passw2'])){
            $this->error('确认密码不能为空');
        }
        if ($param['passw1'] != $param['passw2']){
            $this->error('两次密码不一致');
        }
        $rs=Db::table('member')->where('email',$param['email'])->find();
        if ($rs)
        {
            $this->error('该用户已被注册');
        }else {
            $result = Db::execute("insert into member(email,password,jifen,ye) values('" . $param['email'] . "','" .$param['passw1'] . "',0,0)");
            dump($result);
            $this->redirect(url('index/index'));
        }
    }
        
}