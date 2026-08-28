<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_neko_mobile_home {

	function _redirect() {
		global $_G;

		$home = $_G['cache']['plugin']['neko_mobile_home']['home'];
		if(!$home || $home == 'forum') {
			return;
		}
		if(CURMODULE != 'index' || !empty($_GET['forumlist']) || !empty($_GET['mod'])) {
			return;
		}
		$cur = $_G['basescript'];
		if($cur != 'forum' && $cur != 'portal') {
			return;
		}
		if($cur == 'portal' && $home == 'portal') {
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
		if($home == 'custom') {
			$path = basename(parse_url($url, PHP_URL_PATH));
			if($path == $cur.'.php' || $path == 'index.php') {
				return;
			}
		}
		dheader('location: '.$url);
	}

	function common() {
		$this->_redirect();
	}
}

class mobileplugin_neko_mobile_home_forum extends mobileplugin_neko_mobile_home {

	function index() {
		$this->_redirect();
	}
}
