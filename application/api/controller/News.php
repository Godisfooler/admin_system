<?php
namespace app\api\controller;
use think\Controller;

//商品
class News extends Controller
{

    //任务列表
    public function newsList(){
        $start = input('page');
        $limit = input('pageSize');
        $start = $start-1>0?$start-1:0;
        if(empty($limit)){
            $list = db('news_list')->order('iAddTime DESC')->select();
        }else{
            $list = db('news_list')->order('iAddTime DESC')->limit($start*$limit,$limit)->select();
        }
        foreach($list as &$l){
            if(mb_strlen($l['sContent']) > 50){
                $l['summary'] = mb_substr($l['sContent'],0,50).'...';
            }else{
                $l['summary'] = $l['sContent'];
            }
            $l['date'] = date("Y-m-d H:i",$l['iAddTime']);
        }
        $this->result($list,10000,'请求成功','json');
    } 

    public function newsdetail(){
        $id = input('id');
        $res = db('news_list')->find($id);
        $res['date'] = date("Y-m-d H:i",$res['iAddTime']);
        $this->result($res,10000,'请求成功','json');
    }

    public function commentlist(){
        $start = empty(input('page'))?0:input('page');
        $start = $start-1>0?$start-1:0;
        $limit = input('pageSize');
        if(empty($limit)){
            $list = db('comment_list')->order('iAddTime DESC')->select();
        }else{
            $list = db('comment_list')->order('iAddTime DESC')->limit(($start)*$limit,$limit)->select();
        }
        foreach($list as &$l){
            $l['date'] = date("Y-m-d H:i",$l['iAddTime']);
        }
        $this->result($list,10000,'请求成功','json');
    }
    
    public function addComment(){
        $username = input('username');
        $comment = input('content');
        $data = [];
        $data['sComment'] = $comment;
        $data['username'] = $username;
        $data['iAddTime'] = time();
        $res = db('comment_list')->insert($data);
        $this->result($res,10000,'添加成功','json');
    }
}
