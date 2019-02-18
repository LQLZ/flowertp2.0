<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Myorder;
use app\index\model\Shoplist as ShoplistModel;
use think\Request;
class Shoplist extends Controller{
    public function index(){
        $orderID=input('get.orderID/d');
        $shoplists=Db::table('showshoplist')->where('orderID',$orderID)->select();
        $this->assign('shoplists',$shoplists);
        return $this->fetch('shoplist');
    }
    public function update(Request $request){
        $orderID=input('post.orderID/d');
        $shoplists=ShoplistModel::where('orderID',$orderID)->select();
        foreach ($shoplists as $shoplist){
            $SLID=$shoplist->SLID;
            $shoplist->email=session('eamil');
            $pjcontent='pjcontent'.$SLID;
            $pjstar='pjstar'.$SLID;
            $shoplist->pjcontent=$request->param($pjcontent);
            $shoplist->pjstar=$request->param($pjstar);
            $shoplist->pjtime=date('Y-m-d H:i:s');
            $shoplist->pjip=$request->ip();
            $shoplist->save();
        }
        $order=Myorder::get($orderID);
        $order->ddzt='已评价';
        $order->save();
        $this->redirect('order/showorder');
    }
}