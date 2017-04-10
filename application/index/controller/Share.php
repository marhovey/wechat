<?php
namespace app\index\controller;
use think\Controller;
class Share extends Controller
{
	public function index(){
		$name = $_POST['name'];
		$img = $_POST['img'];
		$to = $_POST['to'];
		$openid = $_POST['wecha_id'];
		if($openid != ""){
			$alshare = db('wx_share')->where('openid',$openid)->find();
			if($alshare){
				$count = $alshare['share_cont'];
				$count++;
				db('wx_share')->where('openid',$openid)->update(['share_cont' => $count]);
			}else{
				$newin['openid'] = $openid;
				$newin['share_cont'] = 1;
				$newin['img'] = $img;
				$newin['name'] = $name;
				$newin['to'] = $to;
				db('wx_share')->insert($newin);
			}
		}
	}
	public function updateDesc(){
		$openid = $_POST['wecha_id'];
		$desc = $_POST['desc'];
		db('wx_user')->where('openid',$openid)->update(['desc' => $desc]);
	}
}