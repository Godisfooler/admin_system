<?php
namespace app\api\controller;
use think\Controller;

//商品
class Goods extends Controller
{

    //任务列表
    public function taskList(){
        $list = db('task_list')->select();
        $this->result($list,10000,'请求成功','json');
    } 

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

    public function getGoodslist(){
        $categoryid = input('categoryid');
        $list = db('goods_list')->where(['pid'=>$categoryid])->select();
        foreach($list as &$l){
            $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].$l['sImgUrls'];
        }
        $this->result($list,10000,'请求成功','json');
    }

    //已发布商品列表
    public function publishlist(){
        $uid = empty(input('uid'))?1:input('uid');
        $list = db('goods_list')->where(['uid'=>$uid])->select();
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

    public function goodsImgUp(){
        $path = $this->uploadFile('images/goods');
        $this->result(['savepath'=>$path],10000,'请求成功','json');
    }

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

    public function downloadImage($url)
    {
        // 获取图片数据
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        // 创建文件夹并保存图片
        $imageFolder = ROOT_PATH.'public'. DS .'/images/goods/';
        if (!is_dir($imageFolder)) {
            mkdir($imageFolder);
        }
        $imageName = uniqid() . '.jpg';
        $imagePath = $imageFolder . $imageName;

        $file = fopen($imagePath, 'w');
        fwrite($file, $data);
        fclose($file);
        return $imageName;
    }

    public function getCategorylist(){
        $list = db('categorys')->where(['parentid'=>['GT',0]])->select();
        $this->result($list,10000,'请求成功','json');
    }

    public function goodsDetail(){
        $id = input('goodsid');
        $res = db('goods_list')->find($id);
        $res['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($res['sImgUrls'],'.');
        $imgArr = json_decode($res['sImgList'],true);
        $imgUrl = [];
        foreach($imgArr as $img){
            $imgUrl[] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($img,'.');
        }
        $res['images'] = $imgUrl;
        $this->result($res,10000,'请求成功','json');
    }

    public function getAddress(){
        $uid = input('uid');
        $list = db('user_address')->where(['uid'=>$uid])->select();
        // $res['address'] = $res['sAddress'];
        // $res['isDefault'] = 1;
        // $res['name'] = '测试用';
        // $res['phoneNumber'] = '15680590891';
        // $res['phone'] = '15680590891';
        // $res['cityName'] = "甘南藏族自治州";
        // $res['countryCode'] = "chn";
        // $res['countryName'] = "中国";
        // $res['detailAddress'] = "松日鼎盛大厦0层0号";
        // $res['districtCode'] = "623026";
        // $res['districtName'] = "碌曲县";
        $this->result($list,10000,'请求成功','json');
    }

    public function saveAddress(){
        $data['uid'] = input('uid');
        $data['address'] = input('address');
        $data['isDefault'] = input('isDefault')?1:0;
        $data['name'] = input('username');
        $data['phoneNumber'] = input('phone');
        $data['detailAddress'] = input('detail_address');
        $data['iAddTime'] = time();

        // $data['phone'] = input('phone');
        // $data['cityName'] = "甘南藏族自治州";
        // $data['countryCode'] = "chn";
        // $data['countryName'] = "中国";
        // $data['districtCode'] = "623026";
        // $data['districtName'] = "碌曲县";
        $res = db('user_address')->insert($data);
        $this->result([],10000,'请求成功','json');
    }

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
        $task = [];
        $goods = db('goods_list')->find($data['iGoodsId'])['sGoodsName'];
        $task['sTaskTitle'] = '配送 '.$goods;
        $task['sLocation'] = $data['sAddress'];
        $task['sContact'] = $data['sPhone'];
        $task['iPublisher'] = $data['uid'];
        db('task_list')->insert($task);
    }
//iAddTime: 1682274054
// iStatus: 1
// id: 7
// pid: 3
// sDescribe: "11"
// sGoodsName: "发布测试"
// sImgList: "["\/images\/goods\/6445770652ac3.jpg"]"
// sImgUrls: "http://admin.test.com/images/goods/6445770652ac3.jpg"
// sPrice: "222"
// uid: 7
    public function orderlist(){
        $uid = input('uid');
        $list = db('order')->where(['uid'=>$uid])->select();
        foreach($list as &$l){
            $goods = db('goods_list')->find($l['iGoodsId']);
            $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].$goods['sImgUrls'];
            $l['sGoodsName'] = $goods['sGoodsName'];
            $l['sDescribe'] = $goods['sDescribe'];
        }
        $return[] = ['goodsList'=>$list];
        $this->result($return,10000,'请求成功','json');
    }

    public function getAmount(){
        $uid = input('uid');
        $res = db('user_list')->field('iIntegral')->find($uid);
        $this->result($res,10000,'请求成功','json');
    }

    public function delAddress(){
        $id = input('id');
        $res = db('user_address')->delete($id);
        if($res){
            $this->result($res,10000,'请求成功','json');
        }
    }
}
