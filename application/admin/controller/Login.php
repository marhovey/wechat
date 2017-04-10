<?php
namespace app\admin\Controller;
use think\Controller;
use think\Validate;
use think\Session;
class Login extends Controller
{
  public function index(){
    Session::set('username',$_POST['username'],'think');
    $login_eck = db('wx_user_lst')->where('username',$_POST['username'])->find();
    if(!$login_eck){
      die('用户名不存在');
    }else if($login_eck['password']==sha1($_POST['password'])&&captcha_check($_POST['verify'])){
      echo 'ok';
    }else if(!captcha_check($_POST['verify'])){
      echo '验证码错误';
    }else if($login_eck['password']!=$_POST['password']){
      echo '密码错误';
    }
  }
}