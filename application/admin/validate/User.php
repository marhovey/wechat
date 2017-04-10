<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
  protected $rule = [
    'username'   => 'require|max:25',
    'password'   => 'require|max:25',
    'email'      => 'email',
    'username'   => 'unique:wx_user_lst',
    'repassword' => 'require|confirm:password',
  ];
  protected $message = [
    'username.require'   => '请输入用户名',
    'username.unique'    => '用户名已存在',
    'username.max'       => '用户名不能超过25个字符',
    'email'              => '邮箱格式错误',
    'password.require'   => '请输入密码',
    'password.max'       => '密码不能超过25个字符',
    'repassword.confirm' => '两次密码不一致',
  ];
}
?>