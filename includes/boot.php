<?php
require_once("config.php");
require_once("templates.php");
require_once("database.php");
require_once("tools.php");
require_once("stemmer.php");
require_once("search.php");
require_once("callbacks.php");
require_once('libs/Smarty.class.php');



function start() {

	if(isset($_GET['logout'])) {
		logout();
	}
	if(isset($_POST['auth-enter'])){
		login();
    }
	
	$vars = array(
		'auth' => false,
		'content' => '',
		'title' => '',
	);
	if(!empty($_SESSION['slinker']['auth'])) {
		$vars['auth'] = true;
		$page = main_page_callback();
		$vars['content'] .= $page['content'];
		$vars['title'] .= $page['title'];
	} else {
		$vars['content'] = view_auth_form();
	}
	
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	$smarty->assign('vars', $vars, true);
	$smarty->display('index.tpl');
}

function main_page_callback() {
	$page = array(
		'title' => '',
		'content' => '',
	);
	$path = '';
	if(isset($_GET['p'])) {
		$path = $_GET['p'];
	}
	
	switch($path) {
		case 'keywords': $page['content'] = keywords_callback(); $page['title'] = 'Сборщик ключевых фраз'; break;
		case 'htmllinker': $page['content'] = html_linker_callback(); $page['title'] = 'Линковщик html'; break;
		case 'sitelinker': $page['content'] = site_linker_callback(); $page['title'] = 'Линковщик сайта'; break;
		case 'savedkeywords': $page['content'] = saved_keywords_callback(); $page['title'] = 'Сохраненные ссылки'; break;
		case 'demositemap': $page['content'] = sitemap_callback(); $page['title'] = 'Демо: Карта сайта'; break;
		case 'demostemmer': $page['content'] = stemmer_callback(); $page['title'] = 'Демо: Стеммер'; break;
		case 'demosearchtag': $page['content'] = noindex_search_callback_tag(); $page['title'] = 'Демо: Поиск по фразе'; break;
		default: $page['content'] = html_linker_callback(); $page['title'] = 'Линковщик html'; break;
	}
	
	return $page;
}

