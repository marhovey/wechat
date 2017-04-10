<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Validate;
class Signup extends Controller
{
  public function index(){
    Session::set('username',$_POST['username'],'think');
    $data = [
      'username'   => $_POST['username'],
      'password'   => $_POST['password'],
      'repassword' => $_POST['repassword'],
      'email'      => $_POST['email']
    ];
    $validate = validate('User');
    if(!$validate->check($data)){
      echo ($validate->getError());
    }else{
      $newin['email']    = $_POST['email'];
      $newin['username'] = $_POST['username'];
      $newin['password'] = sha1($_POST['password']);
      $newin['userapp']  = $_POST['appid'];
      $newin['usersert'] = $_POST['appsert'];
      $newin['token']    = 'othnk'.rand(1000000,9999999);
      db('wx_user_lst')->insert($newin);
      echo 'ok';
    }
  }
}