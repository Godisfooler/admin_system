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
        $uid = input('uid');
        $map = [];
        if(!empty($uid)){
            $map['iReceiver'] = $uid;
        }
        $start = $start-1>0?$start-1:0;
        if(empty($limit)){
            $list = db('task_list')->where($map)->order('iAddTime DESC')->select();
        }else{
            $list = db('task_list')->where($map)->order('iAddTime DESC')->limit($start*$limit,$limit)->select();
        }
        $userlist = db('user_list')->column('id,username');
        foreach($list as &$l){
            if($l['iStatus'] == 2){
                $l['completed'] = true;
            }else{
                $l['completed'] = false;
            }
            if($l['iReceiver'] > 0){
                $l['receiver'] = isset($userlist[$l['iReceiver']])?$userlist[$l['iReceiver']]:'';
            }
            $l['iPublisher'] = isset($userlist[$l['iPublisher']])?$userlist[$l['iPublisher']]:'';
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
        $id = input('post.taskid');
        $uid = input('post.uid');
        $res = db('task_list')->where(['id'=>$id])->update(['iStatus'=>1,'iReceiver'=>$uid]);
        if($res){
            $this->result([],10000,'请求成功','json');
        }
    }

    //完成任务
    public function completeTask(){
        $id = input('post.taskid');
        $res = db('task_list')->where(['id'=>$id])->update(['iStatus'=>2]);
        if($res){
            $this->result([],10000,'请求成功','json');
        }
    }

    //删除任务
    public function deleteTask(){
        $id = input('post.id');
        db('task_list')->delete($id);
    }
}
