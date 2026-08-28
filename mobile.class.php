<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_neko_mobile_home {

	function common() {
		global $_G;

		if(basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
			return;
		}
		$home = $_G['cache']['plugin']['neko_mobile_home']['home'];
		if(!$home || $home == 'forum' || !empty($_GET['mod']) || !empty($_GET['forumlist'])) {
			return;
		}

		$urls = array(
			'portal' => 'portal.php',
			'group' => 'group.php',
			'guide' => 'forum.php?mod=guide&view=newthread',
			'blog' => 'home.php?mod=space&do=blog&view=all',
			'album' => 'home.php?mod=space&do=album&view=all',
			'doing' => 'home.php?mod=space&do=doing&view=all',
			'ranklist' => 'misc.php?mod=ranklist',
			'feed' => 'home.php?mod=space&do=home&view=all',
			'faq' => 'misc.php?mod=faq',
			'custom' => trim($_G['cache']['plugin']['neko_mobile_home']['customurl']),
		);

		$url = isset($urls[$home]) ? $urls[$home] : '';
		if(!$url) {
			return;
		}
		if($home == 'custom' && basename(parse_url($url, PHP_URL_PATH)) == 'index.php') {
			return;
		}
		dheader('location: '.$url);
	}
}
