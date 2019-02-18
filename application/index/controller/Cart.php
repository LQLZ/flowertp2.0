<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Cart as CartModel;
class Cart extends Controller
{
    public function index()
    {
        if (empty(session('email')))
        {
            $this->error('请先登录','login/index');
        }
        $rs = Db::table('vcart')->where('email',session('email'))->select();
        if (!empty($rs)){
            $this->assign('result',$rs);
            return $this->fetch();
        }else{
            $this->error("购物车为空","index/index");
        }
    }
    public function addCart()
    {
        if(empty(session('email'))){
            $this->error('请先登录!','login/index');
        }
        $param = input('get.');
        if (empty($param['flowerID'])){
            $this->error('请选择商品');
        }
        $cart=CartModel::where('email',session('email'))->where('flowerID',$param['flowerID'])->find();
        if (empty($cart)){
            $CartN=new CartModel();
            $CartN->email=session('email');
            $CartN->flowerID=$param['flowerID'];
            $CartN->num=1;
            $CartN->save();
        }else {
            $cart->num=$cart->num+1;
            $cart->save();
        }
       /*$rs = Db::table('cart')->where('email',session('email'))->where('flowerID',$param['flowerID'])->find();
       if(empty($rs)){
           $result = Db::execute("insert into cart(cartID,email,flowerID,num) values(null,'" . session('email') . "','" .$param['flowerID']. "',1)");
           dump($result);
       }else{
           $result=Db::execute("update cart set num=num+1 where email='" .session('email'). "' and flowerID='" . $param['flowerID'] . "'");
           dump($result);
       }
       */
       $this->redirect(url('cart/index'));
    }
    public function clearCart(){
        $result=Db::execute("delete from cart where email='" .session('email'). "'");
        dump($result);
        return redirect("cart/index");
    }
    public function deleteCart()
     {
//         $param = input('get.cartID');
//         $result=Db::execute("delete from cart where cartID=" .$param);
//         dump($result);
//         return redirect("cart/index");
         $cartID = input('get.cartID/d');
         $cart = CartModel::get($cartID);
         $result = $cart->delete();
         return $result ? '删除成功！' : '删除失败！';
    }
    public function updateCart(){
//         $param = input('get.');
//         $result=Db::execute("update cart set num=".$param['num']." where cartID=" .$param['cartID']);
//         dump($result);
//         return redirect("cart/index");
        $cartID = input('get.cartID/d');
        $cart = CartModel::get($cartID);
        $cart->num = input('get.num/d');
        $result = $cart->save();
        return $result ? '修改成功！' : '修改失败！';
    }
}