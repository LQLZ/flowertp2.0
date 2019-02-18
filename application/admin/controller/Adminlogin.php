<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
class Adminlogin extends Controller{
    public function adminlogin(){
        return $this->fetch();
    }
    public function dologin()
    {
        $username = input('post.username');
        if (empty($username)){
            $this->error('请输入用户名');
        }
        $password = input('post.password');
        if (empty($password)){
            $this->error('请输入密码');
        }
        $admin = Db::table('admin')->where('username',$username)->find();
        if (empty($admin)){
            $this->error('用户名不存在');
        }
        if ($admin['password']!= $password){
            $this->engine('密码错误');
        }
        session('admin',$username);
        session('authority',$admin['authority']);
        $this->redirect(url('index/index'));
        
    }
    public function logOut(){
        session('email',null);
        return redirect(url('index/index'));
    }
}