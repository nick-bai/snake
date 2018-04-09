<?php
namespace app\api;

class Auth {
    /**
     * 手机端用户鉴权
     * @return json 频道
     */
    public static function auth($data){
    	$uat_url = 'https://sxappuat.cpic.com.cn/eup-app-user/user/childrenWatchResult';
    	$sit_url = 'https://sxappsit.cpic.com.cn/eup-app-user/user/childrenWatchResult';
    	$prd_url = 'https://lfapp.cpic.com.cn/eup-app-user/user/childrenWatchResult';
		$use_url = '';
		if($data['host'] == 'uat.yptech.tv'){
			$use_url = $uat_url;
		}else if($data['host'] == 'sit.yptech.tv'){
			$use_url = $sit_url;
		}else{
			$use_url = $prd_url;
		}
    	$url = $use_url.'?userId='.$data['userId'].'&sessionToken='.$data['sessionToken'].'&source='.$data['source'];
    	return file_get_contents($url);
    }
}
