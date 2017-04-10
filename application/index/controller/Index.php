<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
  public function index()
  {
  	$appsert = '076ed9d206ac871f2f12d6a22ea49e59';
	$appid = 'wxb52625b5332abeac';
  	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=http://wx.rojewel.com/myownwechat/public/&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
  	if(isset($_GET['code'])){
  		$code = $_GET['code'];
	  	$appsert = '076ed9d206ac871f2f12d6a22ea49e59';
	  	$appid = 'wxb52625b5332abeac';
	  	$access_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsert.'&code='.$code.'&grant_type=authorization_code';
	  	$access = file_get_contents($access_url);
	  	$accesst = json_decode($access);
	  	$iscode = db('wx_user')->where('code',$code)->find();
	  	if(isset($accesst->openid)){
		  	$open_id = $accesst->openid;
		  	$isouath = db('wx_user')->where('openid',$open_id)->find();
		  	if($isouath){
		  		$this->assign('info_name',$isouath['name']);
		  		$this->assign('info_img',$isouath['img']);
		  		$this->assign('desc',$isouath['desc']);
		  		db('wx_user')->where('openid',$open_id)->update(['code' => $code]);
		  	}else{
			  	$access_token = $accesst->access_token;
			  	$info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$open_id.'&lang=zh_CN';
			  	$info = file_get_contents($info_url);
			  	$info_li = json_decode($info);
			  	$info_name = $info_li->nickname;
			  	$info_img = $info_li->headimgurl;
		        $newin = array();
		        $newin['name'] = $info_name;
		        $newin['img'] = $info_img;
		        $newin['time'] = time();
		        $newin['openid'] = $open_id;
		        $newin['city'] = $info_li->city;
		        $newin['code'] = $code;
		        $newin['desc'] = "随随便便分享吧---测试ing";
		        db('wx_user')->insert($newin);
		        $this->assign('info_name',$info_name);
			  	$this->assign('info_img',$info_img);
			  	$this->assign('desc',$newin['desc']);
		  	}
		  	$this->assign('wecha_id',$open_id);
	  	}else if($iscode){
	  		$this->assign('info_name',$iscode['name']);
		  	$this->assign('info_img',$iscode['img']);
		  	$this->assign('wecha_id',$iscode['openid']);
		  	$this->assign('desc',$iscode['desc']);
	  	}else{
	  		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=http://wx.rojewel.com/myownwechat/public/&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
	  		$this->assign('info_name','');
		  	$this->assign('info_img','');
		  	$this->assign('timestamp','');
		  	$this->assign('nonceStr','');
		  	$this->assign('signature','');
		  	$this->assign('wecha_id','');
	  		$this->assign('skip','OK');
	  		$this->assign('desc','');
	  	}
	  	// START
	  	$jstck = db('wx_jspai')->where('id',1)->find();
	  	if($jstck){
	  		$newtime = time();
	  		$oldtime = $jstck['time'];
	  		$timedis = $newtime - $oldtime;
	  		if($timedis>=7200){
	  			$time = time();
	  			$access_tokennormal = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsert);
		  		$access_normal = json_decode($access_tokennormal);
		  		$acc_tonor = $access_normal->access_token; 
		  		$jsapi = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$acc_tonor.'&type=jsapi');
		  		$jsapi_json = json_decode($jsapi);
		  		$jstick = $jsapi_json->ticket;
		  		db('wx_jspai')->where('id',1)->update(['jspai' => $jstick,'time'=>$time]);
	  		}else{
	  			$time = time();
	  			$jstick = $jstck['jspai'];
	  		}
	  	}else{
		  	$access_tokennormal = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsert);
		  	$access_normal = json_decode($access_tokennormal);
		  	$acc_tonor = $access_normal->access_token; 
		  	$jsapi = file_get_contents('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$acc_tonor.'&type=jsapi');
		  	$jsapi_json = json_decode($jsapi);
		  	$jstick = $jsapi_json->ticket;
		  	$time = time();
		  	$newin = array();
	        $newin['jspai'] = $jstick;
	        $newin['time'] = $time;
		  	db('wx_jspai')->insert($newin);	
	  	}
	  	$noncestr = rand(10000,99999); 
	  	$signature = sha1("jsapi_ticket=".$jstick."&noncestr=".$noncestr ."&timestamp=".$time ."&url=http://wx.rojewel.com/myownwechat/public/?code=".$code."&state=STATE");
	  	$this->assign('timestamp',$time);
	  	$this->assign('nonceStr',$noncestr);
	  	$this->assign('signature',$signature);
	  	// END
  	}else {
  		$this->assign('info_name','');
	  	$this->assign('info_img','');
	  	$this->assign('timestamp','');
	  	$this->assign('nonceStr','');
	  	$this->assign('signature','');
	  	$this->assign('wecha_id','');
	  	$this->assign('skip','OK');
	  	$this->assign('desc','');
  	}
  	$lst = db('wx_share')->order('id asc')->select();
  	if($lst){
  		$this->assign('lst',$lst);
  	}
  	$this->assign('url',$url);
    return $this->fetch();
  }
}