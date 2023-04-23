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
        foreach($list as &$l){
            if(strpos($l['sImgUrls'],'http') === false){
                $l['sImgUrls'] = 'http://'.$_SERVER['SERVER_NAME'].ltrim($l['sImgUrls'],'.');
            }
        }
        $temp = [];
        $temp['status'] = 1;
        $temp['statusDesc'] = '已成交';
        $temp['goodsList'] = $list;
        $reutrn[$temp['status']] = $temp;
        $temp['statusDesc'] = '待成交';
        $reutrn[2] = $temp;
        $this->result($reutrn,10000,'请求成功','json');
    }

    public function goodspublish(){
        $imgArr = json_decode(input('imgs'),true);
        $data = [];
        $data['sGoodsName'] = input('goodsname');
        $data['sPrice'] = input('price');
        $data['sDescribe'] = input('descripe');
        $data['pid'] = input('categoryid');
        $data['uid'] = input('uid');
        foreach($imgArr as $img){
            $imgUrl = str_replace('http://','http://127.0.0.1:43374/',$img['path']);
            $imgPath = $this->downloadImage($imgUrl);
            $data['sImgUrls'] = '/images/goods/'.$imgPath;
            $imgPathArr[] = '/images/goods/'.$imgPath;
        }
        $data['sImgList'] = json_encode($imgPathArr);
        $data['iAddTime'] = time();
        $res = db('goods_list')->insert($data);
        if($res){
            $this->result($res,10000,'请求成功','json');
        }
    }

    public function uploadFile(){
        $file = request()->file('image');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'avatarUrl');
            if($info){
                // 成功上传后 获取上传信息
                $filePath = $info->getSaveName(); 
            }else{
            }
        }
        $filePath = './avatarUrl/'.str_replace('\\','/',$filePath);
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
}
