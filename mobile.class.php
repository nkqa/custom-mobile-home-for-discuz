<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_neko_mobile_home {

	function common() {
		global $_G;

		// 防重入：被 require 的入口文件会再次执行 runhooks()
		if(defined('NEKO_MOBILE_HOME_DONE') || basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
			return;
		}
		$home = $_G['cache']['plugin']['neko_mobile_home']['home'];
		if(!$home || $home == 'forum' || !empty($_GET['mod']) || !empty($_GET['forumlist'])) {
			return;
		}

		// 目标: 入口文件, GET 参数, 需手动补载的缓存(二次 require 时 C::app()->init() 幂等跳过, cachelist 不会加载)
		$targets = array(
			'portal' => array('portal.php', array(), array('portalcategory', 'diytemplatenameportal')),
			'group' => array('group.php', array(), array('grouptype', 'groupindex', 'diytemplatenamegroup')),
			'guide' => array('forum.php', array('mod' => 'guide', 'view' => 'newthread'), array()),
			'blog' => array('home.php', array('mod' => 'space', 'do' => 'blog', 'view' => 'all'), array('magic', 'usergroups', 'diytemplatenamehome')),
			'album' => array('home.php', array('mod' => 'space', 'do' => 'album', 'view' => 'all'), array('magic', 'usergroups', 'diytemplatenamehome')),
			'doing' => array('home.php', array('mod' => 'space', 'do' => 'doing', 'view' => 'all'), array('magic', 'usergroups', 'diytemplatenamehome')),
			'feed' => array('home.php', array('mod' => 'space', 'do' => 'home', 'view' => 'all'), array('magic', 'usergroups', 'diytemplatenamehome')),
			'ranklist' => array('misc.php', array('mod' => 'ranklist'), array('forums', 'diytemplatename')),
			'faq' => array('misc.php', array('mod' => 'faq'), array()),
		);

		if(isset($targets[$home])) {
			// ponytail: 依赖 Discuz 内建行为——C::app()->init() 有 initated 守卫(不重复初始化),
			// 且 error_reporting(E_ERROR) + DISCUZ_CORE_DEBUG=false 使入口文件重复 define 常量的警告无感知。
			// 若未来 Discuz 收紧错误处理, 需改为在 index.php 层注入。
			define('NEKO_MOBILE_HOME_DONE', 1);
			list($entry, $params, $cachelist) = $targets[$home];
			if($cachelist) {
				loadcache($cachelist);
			}
			foreach($params as $k => $v) {
				$_GET[$k] = $v;
		}
			if(!empty($params['mod'])) {
				C::app()->var['mod'] = $params['mod'];
				$_G['mod'] = $params['mod'];
			}
			require DISCUZ_ROOT.'./'.$entry;
			exit;
		}

		// 自定义链接无法在本进程内渲染, 仅能跳转
		if($home == 'custom') {
			$url = trim($_G['cache']['plugin']['neko_mobile_home']['customurl']);
			if($url && basename(parse_url($url, PHP_URL_PATH)) != 'index.php') {
				dheader('location: '.$url);
			}
		}
	}
}
