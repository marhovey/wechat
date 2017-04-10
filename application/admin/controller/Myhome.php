<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
class Myhome extends Controller
{
  public function index(){
  	$userinfo = db('wx_user_lst')->where('username',Session::get('username'))->find();
  	$this->assign('name',$userinfo['username']);
  	$this->assign('appid',$userinfo['userapp']);
  	$this->assign('appsert',$userinfo['usersert']);
  	return $this->fetch('myhome');
  }
}
?>