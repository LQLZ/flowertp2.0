<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Flower;
use app\index\model\Shoplist;
class Showflower extends Controller{
    public function index(){
        $flowers = Flower::order('SelledNum desc')->paginate(5);
        $page = $flowers->render();
        $this->assign('page', $page);
        $this->assign('flowers', $flowers);
        return $this->fetch();
    }
    
    public function clsflower(){
        $pname = input('get.pname');
        $flowers1 = Flower::order('SelledNum desc')->paginate(5);
        if($pname == 'fclass'){
            $pvalue = input('get.pvalue');
            $flowers = Flower::where('fclass', $pvalue)->order('SelledNum desc')->paginate(5);
            $flowers1=$flowers;
            
        }
        if($pname == 'fclass1'){
            $pvalue = input('get.pvalue');
            $flowers = Flower::where('fclass1', $pvalue)->order('SelledNum desc')->paginate(5);
            $flowers1=$flowers;
        }
        if($pname == 'tejia'){
            $pvalue = input('get.pvalue');
            $flowers = Flower::where('tejia', $pvalue)->order('SelledNum desc')->paginate(5);
            $flowers1=$flowers;
        }
        if($pname == 'yourprice'){
            $pvalue1 = input('get.pvalue1/d');
            $pvalue2 = input('get.pvalue2/d');
            $flowers = Flower::where('yourprice >'.$pvalue1.'AND yourprice <'.$pvalue2)->order('SelledNum desc')->paginate(5);
            $flowers1=$flowers;
        }
        $page=$flowers1->render();
        $this->assign('page',$page);
        $this->assign('result', $flowers1);
        return $this->fetch('index');
    }
    public function flowerdetail(){
        $flowerID = input('get.flowerID');
        $flower=Flower::get($flowerID);
        $this->assign('flower',$flower);
        $shoplists=Shoplist::where('flowerID',$flowerID)->where('pjstar is not null')->select();
        $this->assign('shoplists',$shoplists);
        return $this->fetch();
    }
}