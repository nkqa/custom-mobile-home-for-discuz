<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_neko_mobile_home {

	function common() {
		global $_G, $space, $navtitle;

		// 防重入 + 仅站点首页入口生效
		if(defined('NEKO_MOBILE_HOME_DONE') || basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
			return;
		}
		$home = $_G['cache']['plugin']['neko_mobile_home']['home'];
		if(!$home || $home == 'forum' || !empty($_GET['mod']) || !empty($_GET['forumlist'])) {
			return;
		}

		// 直接渲染目标模块: array(模块路径, GET参数, 需补载的函数文件, 需补载的缓存, basescript)
		// ponytail: 不能 require 入口文件本身——入口会再次 require class_core.php,
		// class core 重复声明为致命错误(白屏)。故复制入口后半段逻辑直接加载模块,
		// 依赖"Discuz 模块不检查 CURMODULE、自行 loadcache 所需缓存"的现状。
		$targets = array(
			'portal' => array('portal/portal_index', array(), array('function_home', 'function_portal'), array('portalcategory', 'diytemplatenameportal'), 'portal'),
			'group' => array('group/group_index', array(), array(), array('grouptype', 'groupindex', 'diytemplatenamegroup'), 'group'),
			'guide' => array('forum/forum_guide', array('mod' => 'guide', 'view' => 'newthread'), array(), array(), 'forum'),
			'blog' => array('home/home_space', array('mod' => 'space', 'do' => 'blog', 'view' => 'all'), array('function_home'), array('magic', 'usergroups', 'diytemplatenamehome'), 'home'),
			'album' => array('home/home_space', array('mod' => 'space', 'do' => 'album', 'view' => 'all'), array('function_home'), array('magic', 'usergroups', 'diytemplatenamehome'), 'home'),
			'doing' => array('home/home_space', array('mod' => 'space', 'do' => 'doing', 'view' => 'all'), array('function_home'), array('magic', 'usergroups', 'diytemplatenamehome'), 'home'),
			'feed' => array('home/home_space', array('mod' => 'space', 'do' => 'home', 'view' => 'all'), array('function_home'), array('magic', 'usergroups', 'diytemplatenamehome'), 'home'),
			'ranklist' => array('misc/misc_ranklist', array('mod' => 'ranklist'), array(), array('forums', 'diytemplatename'), 'misc'),
			'faq' => array('misc/misc_faq', array('mod' => 'faq'), array(), array(), 'misc'),
		);

		if(!isset($targets[$home])) {
			// 自定义链接无法在本进程内渲染, 仅能跳转
			if($home == 'custom') {
				$url = trim($_G['cache']['plugin']['neko_mobile_home']['customurl']);
				if($url && basename(parse_url($url, PHP_URL_PATH)) != 'index.php') {
						dheader('location: '.$url);
				}
			}
			return;
		}

		define('NEKO_MOBILE_HOME_DONE', 1);
		list($module, $params, $funcfiles, $cachelist, $basescript) = $targets[$home];

		foreach($funcfiles as $funcfile) {
			require_once DISCUZ_ROOT.'./source/function/'.$funcfile.'.php';
		}
		if($cachelist) {
			loadcache($cachelist);
		}
		foreach($params as $k => $v) {
			$_GET[$k] = $v;
		}
		$space = array();
		$_G['basescript'] = $basescript;
		if(isset($params['mod'])) {
			$_G['mod'] = $params['mod'];
		}

		require DISCUZ_ROOT.'./source/module/'.$module.'.php';
		exit;
	}
}
