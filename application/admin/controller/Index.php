<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Index extends Controller
{
  public function index(){
    return $this->fetch('login');
  }
  public function signup(){
    return $this->fetch();
  }
  public function retrieve(){
    return $this->fetch();
  }
  public function isreset(){
    if(isset($_POST['username'])){
      $user_info = db('wx_user_lst')->where('username',$_POST['username'])->find();
      if(isset($user_info['username'])){
        if($user_info['email']==$_POST['email']){
          echo "ok";
          Session::set('name',$_POST['username'],'think'); 
        }else{
          echo "邮箱不正确";
      }}else{
        echo "用户名不存在";
      }
    }
  }
  public function resetnow(){
    if(Session::get('name')){
      $this->assign('name',Session::get('name'));
      return $this->fetch();
    }
  }
  public function reset(){
    if(isset($_POST['username'])){
      db('wx_user_lst')->where('username',$_POST['username'])->update(['password' => sha1($_POST['password'])]);
      echo "ok";
    }
  }
}