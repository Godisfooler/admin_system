<?php
namespace app\api\controller;
use think\Controller;

//任务处理类
class Task extends Controller
{

    //任务列表
    public function taskList(){
        $start = input('page');
        $limit = input('pageSize');
        $start = $start-1>0?$start-1:0;
        if(empty($limit)){
            $list = db('task_list')->order('iAddTime DESC')->select();
        }else{
            $list = db('task_list')->order('iAddTime DESC')->limit($start*$limit,$limit)->select();
        }
        $this->result($list,10000,'请求成功','json');
    } 

    //创建任务
    public function createTask(){
        $data = [];
        $data['sTaskTitle'] = input('taskname');
        $data['sTaskContent'] = input('content');
        $data['sLocation'] = input('location');
        $data['sContact'] = input('phone');
        $data['iAddTime'] = time();
        $res = db('task_list')->insert($data);
        if($res){
            $this->result([],10000,'请求成功','json');
        }else{
            $this->result([],10000,'请求失败','json');
        }
    }

    //接单
    public function reciveTask(){
        $id = input('post.id');
        $uid = input('post.uid');
        db('task_list')->where(['id'=>$id])->update(['iStatus'=>1,'iReceiver'=>$uid]);
    }

    //完成任务
    public function completeTask(){
        $id = input('post.id');
        db('task_list')->where(['id'=>$id])->update(['iStatus'=>2]);
    }

    //删除任务
    public function deleteTask(){
        $id = input('post.id');
        db('task_list')->delete($id);
    }
}
