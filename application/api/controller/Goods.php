<?php
namespace app\api\controller;
use think\Controller;

//商品
class Goods extends Controller
{
    //获取商品分类
    public function getCategory(){
        $list = db('categorys')->select();
        foreach($list as &$l){
            if(strpos($l['sImgUrl'],'http') === false){
                $l['sImgUrl'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($l['sImgUrl'],'.');
            }
        }
        $return = list_to_tree($list,0,'id','parentid','children');

        $this->result($return,10000,'请求成功','json');
    }

    //获取商品列表
    public function getGoodslist(){
        $categoryid = input('categoryid');
        $list = db('goods_list')->where(['pid'=>$categoryid,'iStatus'=>1])->select();
        foreach($list as &$l){
            $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].$l['sImgUrls'];
        }
        $this->result($list,10000,'请求成功','json');
    }

    //已发布商品列表
    public function publishlist(){
        $uid = empty(input('uid'))?1:input('uid');
        $list = db('goods_list')->where(['uid'=>$uid])->order('iAddTime DESC')->select();
        $return = [];
        $textArr = [1=>'待成交',2=>'已成交'];
        foreach($list as &$l){
            if(strpos($l['sImgUrls'],'http') === false){
                $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($l['sImgUrls'],'.');
            }
            $return[$l['iStatus']]['statusDesc'] = $textArr[$l['iStatus']];
            $return[$l['iStatus']]['status'] = $l['iStatus'];
            $return[$l['iStatus']]['goodsList'][] = $l;
        }
        $this->result($return,10000,'请求成功','json');
    }

    //商品图片上传
    public function goodsImgUp(){
        $path = $this->uploadFile('images/goods');
        $this->result(['savepath'=>$path],10000,'请求成功','json');
    }

    //商品发布
    public function goodspublish(){
        $imgArr = json_decode(input('imgs'),true);
        $data = [];
        $data['sGoodsName'] = input('goodsname');
        $data['sPrice'] = input('price');
        $data['sDescribe'] = input('descripe');
        $data['pid'] = input('categoryid');
        $data['uid'] = input('uid');
        $data['sImgUrls'] = $imgArr[0];
        $data['sImgList'] = json_encode($imgArr);
        $data['iAddTime'] = time();
        $res = db('goods_list')->insert($data);
        if($res){
            $this->result($res,10000,'请求成功','json');
        }
    }

    //图片上传接收
    public function uploadFile($path='avatarUrl'){
        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . $path);
            if($info){
                // 成功上传后 获取上传信息
                $filePath = $info->getSaveName(); 
            }else{
            }
        }
        $filePath = './'.$path.'/'.str_replace('\\','/',$filePath);
        return $filePath;
    }

    //获取商品分类
    public function getCategorylist(){
        $list = db('categorys')->where(['parentid'=>['GT',0]])->select();
        $this->result($list,10000,'请求成功','json');
    }

    //商品详情
    public function goodsDetail(){
        $id = input('goodsid');
        $res = db('goods_list')->find($id);
        $res['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($res['sImgUrls'],'.');
        $imgArr = json_decode($res['sImgList'],true);
        $imgUrl = [];
        if(!empty($imgArr)){
            foreach($imgArr as $img){
                $imgUrl[] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($img,'.');
            }
        }
        $res['images'] = $imgUrl;
        $this->result($res,10000,'请求成功','json');
    }

    //获取地址列表
    public function getAddress(){
        $uid = input('uid');
        $list = db('user_address')->where(['uid'=>$uid])->select();
        $this->result($list,10000,'请求成功','json');
    }

    //保存地址
    public function saveAddress(){
        $isEdit = input('isEdit');
        $id = input('id');
        $data['uid'] = input('uid');
        $data['address'] = input('address');
        $data['isDefault'] = input('isDefault')?1:0;
        if($data['isDefault'] == 1){
            db('user_address')->where(['uid'=>$data['uid']])->update(['isDefault'=>0]);
        }
        $data['name'] = input('username');
        $data['phoneNumber'] = input('phone');
        $data['detailAddress'] = input('detail_address');
        $data['iAddTime'] = time();
        if($isEdit == 'false'){
            $res = db('user_address')->insert($data);
        }else{
            $res = db('user_address')->where(['id'=>$id])->update($data);
        }
        $this->result([],10000,'请求成功','json');
    }

    //商品购买
    public function buyGoods(){
        $data['iGoodsId'] = input('goodsid');
        $data['uid'] = input('uid');
        $userinfo = db('user_list')->find($data['uid']);
        $data['sPrice'] = input('price');

        $balance = $userinfo['iIntegral'];
        if($balance < $data['sPrice']){
            $this->ajaxReturn(['status'=>false,'msg'=>'余额不足！']);
        }else{
            $save['iIntegral'] = $balance - $data['sPrice'];
            db('user_list')->where(['id'=>$data['uid']])->update($save);
        }
        $data['username'] = input('buyusername');
        $data['sAddress'] = input('address').input('detail_address');
        $data['sPhone'] = input('phone');
        $data['iAddTime'] = time();
        db('order')->insert($data);
        db('goods_list')->where(['id'=>$data['iGoodsId']])->update(['iStatus'=>2]);

        $task = [];
        $goods = db('goods_list')->find($data['iGoodsId'])['sGoodsName'];
        $task['sTaskTitle'] = '配送 '.$goods;
        $task['sLocation'] = $data['sAddress'];
        $task['sContact'] = $data['sPhone'];
        $task['iPublisher'] = $data['uid'];
        $task['iAddTime'] = time();
        $res = db('task_list')->insert($task);
        $this->result($res,10000,'请求成功','json');
    }

    //订单列表
    public function orderlist(){
        $uid = input('uid');
        $list = db('order')->where(['uid'=>$uid])->order('iAddTime DESC')->select();
        foreach($list as &$l){
            $goods = db('goods_list')->find($l['iGoodsId']);
            $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].$goods['sImgUrls'];
            $l['sGoodsName'] = $goods['sGoodsName'];
            $l['sDescribe'] = $goods['sDescribe'];
        }
        $return[] = ['goodsList'=>$list];
        $this->result($return,10000,'请求成功','json');
    }

    //获取积分额度
    public function getAmount(){
        $uid = input('uid');
        $res = db('user_list')->field('iIntegral')->find($uid);
        $this->result($res,10000,'请求成功','json');
    }

    //删除地址
    public function delAddress(){
        $id = input('id');
        $res = db('user_address')->delete($id);
        if($res){
            $this->result($res,10000,'请求成功','json');
        }
    }

    //获取地址详情
    public function getAddressDetail(){
        $id = input('id');
        $res = db('user_address')->find($id);
        if($res){
            $res['phone'] = $res['phoneNumber'];
            $this->result($res,10000,'请求成功','json');
        }
    }
}
