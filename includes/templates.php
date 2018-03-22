<?php
require_once("config.php");

function view_auth_form() {
		
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_auth.tpl');	
	
}

function view_noindex_search_form() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_noindex_search.tpl');	
	
}
function view_sitemap_form() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_sitemap.tpl');	
	
}
function view_stemmer_form() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_stemmer.tpl');	
	
}

function view_topmenu() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('topmenu.tpl');	
	
}

function view_text_linker_form() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_text_linker.tpl');	
	
}
function view_html_linker_form() {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch('form_html_linker.tpl');	
	
}

function view_tpl($tpl) {
	$smarty = new Smarty();
	$smarty->debugging = false;
	$smarty->caching = false;
	$smarty->cache_lifetime = 120;
	return $smarty->fetch($tpl . '.tpl');	
	
}