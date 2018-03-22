<?php
	require_once("../includes/config.php");
	require_once("../includes/templates.php");
	require_once("../includes/database.php");
	require_once("../includes/tools.php");
	require_once("../includes/stemmer.php");
	require_once("../includes/search.php");
	require_once('../includes/libs/Smarty.class.php');
	
	if(!empty($_POST['text'])) {
		$scheme = DEFAULTSCHEME;
		// Инициализаци
		$text = $_POST['text'];
		$skiplen = 0;
		if(!empty($_POST['toskip'])) {
			$skiplen = intval($_POST['toskip']);
		}
		$deletelinks = 0;
		if(!empty($_POST['deletelinks'])) {
			$deletelinks = intval($_POST['deletelinks']);
		}
		$path = '';
		if(!empty($_POST['path'])) {
			$path = $_POST['path'];
			
			//Если не установлена схема и хост ссылки, то подставляем наш хост
			if(!strstr($path,$scheme)) {
				$path = $scheme . $path;
			}
			
			$link_headers = get_headers($path, 1);
			if(!empty($link_headers['Location'])) {
				$path = $link_headers['Location'];
			}
		}
		
		$keyphrases = get_keyphrases();
		$keyphrases_tmp = array();
		foreach($keyphrases as $phrase_id=>$keyphrase) {
			if($keyphrase['path'] != $path) {
				$keyphrases_tmp[$phrase_id] = $keyphrase;
			}
		}
		
		$words = search_keyphrases_in_html($text, $keyphrases_tmp, $deletelinks);
		//dpm($words);
		$output_text = search_create_text_from_words($words, $skiplen, $keyphrases_tmp, $path);
		print $output_text;
	}
	