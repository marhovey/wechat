<?php
namespace app\index\controller;
use think\Controller;
class Count extends Controller
{
	public function index(){
		if($_POST['kefu']=="kefu01"){
    		$count = db('kefu')->where('id','1')->find();
    		$count['num']++;
    		db('kefu')->update(['num' => $count['num']]);
    	}else if($_POST['kefu']=="kefu02"){
			$count = db('kefu')->where('id','2')->find();
    		$count['num']++;
    		db('kefu')->update(['num' => $count['num']]);
    	}	
	}
}