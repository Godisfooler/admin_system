<?php
namespace app\api\controller;
use think\Controller;

//任务处理类
class Task extends Controller
{

    //任务列表
    public function taskList(){
        $list = db('task_list')->select();
        $this->result($list,10000,'请求成功','json');
    } 

    //创建任务
    public function createTask(){
        $post = input('post');
        $data = [];
        $data['sTaskTitle'] = $post['task_title'];
        $data['sTaskContent'] = $post['task_content'];
        $data['iPublisher'] = $post['user'];
        $data['iAddTime'] = time();
        $res = db('task_list')->add($data);
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
