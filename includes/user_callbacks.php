<?php
require_once("config.php");
require_once("templates.php");
require_once("database.php");
require_once("tools.php");
require_once("stemmer.php");
require_once("search.php");
require_once('libs/Smarty.class.php');






function html_linker_callback() {
	$content = '<p>Данный поисковой алгоритм использует для поиска ключевые слова, сохраненные в базе данных. Модифицирован для линковки HTML. ' . 
				'Заполнение базы ключевыми фразами происходит в автоматическом режиме на странице "<a href="?p=keywords">Сбор ключевых слов</a>". ' . 
				'Их просмотр доступен на странице "<a href="?p=savedkeywords">Сохраненные фразы</a>", а редактирование в phpmyadmin.</p>';
	$content .= view_html_linker_form();
	
	if(!empty($_POST['text'])) {
		
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
		
		$keyphrases = get_keyphrases();
				
		$words = search_keyphrases_in_html($text, $keyphrases, $deletelinks);
		$output_text = search_create_text_from_words($words, $skiplen);
		
		$content .= '<hr>';
		$content .= '<h3>Обработанный текст:</h3>';
		$content .= '<div class="search-result">' . $output_text . '</div>';
		$content .= '<hr>';
		$content .= '<h3>Исходный текст:</h3>';
		$content .= '<div class="search-result">' . $text . '</div>';
		//$content .= '<div class="search-result">' . dpms($words) . '</div>';
	}
	
	return $content;
}




function keywords_callback() {
	
	$content = '<p>Построитель карты сайта выполняет парсинг всех ключевых фраз в тегах meta и сохраняет результаты в базу. ' . 
				'Их просмотр доступен на странице "<a href="?p=savedkeywords">Сохраненные фразы</a>", а редактирование в phpmyadmin :)</p>' . 
				'<p>Внимание! Все что было в базе до отправки этой формы <span class="red uppercase">будет удалено с крайней жестокостью!</span></p>';
	$content .= view_sitemap_form();
	
	if(!empty($_POST['domain'])) {
		truncate_table ('slinker_keywords');
		truncate_table ('slinker_keyphrases');
		truncate_table ('slinker_pages');
	
		$host = $_POST['domain']; //'meparts.itmediagroup.ru'; // Хост сайта
		$scheme = 'http://'; // http или https?
		$site_links = get_site_links($host,$scheme);
		
		$counters = array(
			'keyphrases' => 0,
			'keywords' => 0,
			'pages' => 0,
		);
		
		$content .= '<ul>';
		foreach($site_links['urls'] as $url=>$row) {
			$url_path = parse_url($url, PHP_URL_PATH);
			if(substr($url_path, -1) != '/') {
				$url_path .= '/';
			}
			$depth = count(explode('/', ($url_path))) - 2;
			if($depth < 0) {
				$depth = 0;
			}
			$page_id = add_page($url, $depth);
			$content .= '<li>#' . $page_id . ' - ' . $url . ' (processed)</li>';
			if(!empty($page_id)) {
				$counters['pages']++;
				
				$keyphrases = explode(',', $site_links['meta_keywords'][$url]);
				foreach($keyphrases as $phrase_ind=>$keyphrase) {
					
					$keyphrase = trim($keyphrase);
					$phrase_id = add_keyphrase ($page_id, $keyphrase);
					if(!empty($phrase_id)) {
						$counters['keyphrases']++;
						
						$chars_to_delete = search_get_chars_to_delete();
						$not_valid_words = search_get_not_valid_words();
						
						$keywords = mb_split("\s", $keyphrase);
						$keywords_prepared = array();
						foreach($keywords as $keyword) {
							if(in_array($keyword, $not_valid_words)) {
								continue;
							}
							$keywords_prepared[] = str_replace($chars_to_delete, '', mb_strtolower($keyword, 'UTF-8'));
						}
						$keywords_stemmed = array();
						
						foreach($keywords_prepared as $ind=>$keyword) {
							$counters['keywords']++;
							
							$keyword_stemmed = mb_strtolower(stem($keyword), 'UTF-8');
							add_keyword ($phrase_id, $keyword_stemmed, $keyword);
						}
					}
				}
			}
		}
		$content .= '</ul>';
		$content .= '<p>Всего добавлено: ' . 
					'<ul>' .
					'<li>Страниц: ' . $counters['pages'] . '</li>' .
					'<li>Ключевых фраз: ' . $counters['keyphrases'] . '</li>' .
					'<li>Ключевых слов: ' . $counters['keywords'] . '</li>' .
					'</ul>' .
					'</p>';
	}
	return $content;
}


function saved_keywords_callback() {
	
	$content = '<p>Тут отображается сохраненная в базе копия карты сайта и ключевые слова.</p>';
	$content = '<p>На данной странице отображается сохраненная в базе копия карты сайта и ключевые слова. ' . 
				'Заполнение происходит в автоматическом режиме на странице "<a href="?p=keywords">Сбор ключевых слов</a>"</p>';
	
	$content .= '<table>';
	$content .= '<thead>' .
					'<tr>' .
						'<th>Page id</th>' . 
						'<th>Phrase id</th>' . 
						'<th>Path</th>' . 
						'<th>Depth</th>' . 
						'<th>Keyphrase</th>' . 
					'</tr>' .
				'</thead><tbody>';
	
	$keyphrases = get_keyphrases('page_id ASC, phrase_id ASC');
	
	foreach($keyphrases as $phrase_id=>$keyphrase) {
		$content .= '<tr>' .
						'<td>' . $keyphrase['page_id'] . '</td>' . 
						'<td>' . $phrase_id . '</td>' . 
						'<td><a class="smaller" href="' . $keyphrase['path'] . '">' . $keyphrase['path'] . '</a></td>' . 
						'<td>' . $keyphrase['path_depth'] . '</td>' . 
						'<td>' . (!empty($keyphrase['keyphrase'])?('<span class="green">' . $keyphrase['keyphrase'] . '</span>'):'<span class="red">NULL</span>') . '</td>' . 
					'</tr>'; 
	}
		
	$content .= '</tbody>';
	$content .= '</table>';

	return $content;
}




function stemmer_callback() {
	$content = '<p>Данный алгоритм используется для выделения основы слова. Работает также и для фраз. Не учитывает слова не несущие смысловой нагрузки.</p>';
	
	$content .= view_stemmer_form();
	
	if(!empty($_POST['keyphrase'])) {
		$content .= '<p>Ключевая фраза: ' . $_POST['keyphrase'] . '</p>';
		
		$keywords_stemmed = search_stem_keyphrase($_POST['keyphrase']);
		
		$content .= '<p>Stemmed: <ul>';
		foreach($keywords_stemmed as $stemmed) {
			$content .= '<li>' . $stemmed . '</li>';
		}
		$content .= '</ul></p>';
		
	}
	
	return $content;
}



function noindex_search_callback_tag() {
	$content = '<p>Новый алгоритм поиска. Работает с html.</p>';
	
	$content .= view_noindex_search_form();
	
	if(!empty($_POST['text']) && !empty($_POST['keyword'])) {
		
		$word_proximity = KEYWORDPROXIMITY; 
		$word_likenessstep = LIKENESSSTEP;
		$depth_coef = LIKENESDEPTHCOEF;
		$depth = $_POST['depth'];
		$skiplen = 0;
		
		// Подготовка ключевой фразы для поиска
		$keywords_stemmed = search_stem_keyphrase($_POST['keyword']);
		$keyphrase_len = count($keywords_stemmed);
		
		$text = $_POST['text'];
		$text_chars = mbStringToArray($text);
		$text_output = '';
		
		$words = search_explode($text);	
		$words_len = count($words);
		
		// Установка флагов прямой похожести
		foreach($words as $key=>$word) {
			$words[$key]['match'] = 0;
			$words[$key]['match_ind'] = 0;
			if($word['type'] == 'word' && $word['valid']) {
				$words[$key]['match_ind'] = is_match_keyphrase_array($word['stemmed'], $keywords_stemmed);
				$words[$key]['match'] = ($words[$key]['match_ind']?1:0);
			}			
		}
		
		// Соединение разорванных фраз разделителями
		foreach($words as $key=>$word) {
			if($key == 0 || $key == ($words_len - 1)) continue; // первый и последний не смотрим
			if($word['type'] != 'word') continue;				// только для слов
			if($word['match']) {
				$proximity_match = 0;
				$i = 0;
				$i_prox = 0;
				while($i_prox <= $word_proximity) {
					$i++;
					if(($key + $i) >= $words_len) break;			// дошли до конца. отбой
					if($words[$key + $i]['type'] == 'tag') break;	// дошли до тега. отбой
					if($words[$key + $i]['type'] != 'word') continue;		// только слова
					if($words[$key + $i]['valid'] == 0) continue;		// только валидные слова
					
					$i_prox++;
					if($words[$key + $i]['match']) {			// нашли похожее слово. отбой
						$proximity_match = $i;
						break;
					}
				}
				if($proximity_match) {
					for($i = 1; $i <= $proximity_match; $i++) {
						if(!$words[$key + $i]['match']) {			// все слова в этом промежутке норм
							$words[$key + $i]['match'] = 1;
						}
					}
				}
			}
		}
		
		// Поиск похожих фраз
		$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
		$link_start_key = 0;
		$last_link_i = -99999999;
		$current_i = 0;
		foreach($words as $key=>$word) {
						
			if($word['type'] == 'word' && is_valid_tag($word['tag'])) {
				if($word['match'] && $word['match_ind']) { // если слово совпадает с словом из ключевой фразы...
					$keyphrase_matches[$word['match_ind'] - 1] = 1;	// то фиксируем это в массиве
				} else {
					$link_start_key = $key + 1;
				}
				if(!$word['match']) {
					$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
				}
			} elseif($word['type'] == 'tag') {
				$link_start_key = $key + 1;
				$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
			}
			
			if(array_sum($keyphrase_matches) >= (((float)$keyphrase_len*$word_likenessstep) - ((float)$depth_coef*intval($depth)))) { 
				if(is_valid_tag($words[$link_start_key]['tag']) && ($current_i - $last_link_i) > $skiplen) {
					$words[$key]['val'] .= '</a>';
					$words[$link_start_key]['val'] = '<a href="/" title="' . $_POST['keyword'] . '">' . $words[$link_start_key]['val'];
					$last_link_i = $current_i;
				}
				$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
			}
			
			
			if($word['type'] != 'tag') {
				$current_i += mb_strlen($word['type'], 'UTF-8');
			}
			
		}
		
		$output_text = '';
		
		// Сборка текста обратно
		foreach($words as $key=>$word) {
			$output_text .= $word['val'];
		}
		
		$content .= '<div class="search-result">Ключевая фраза: ' . $_POST['keyword'] . '</div>';
		//$content .= '<div class="search-result">' . dpms($words) . '</div>';
		$content .= '<div class="search-result">' . $output_text . '</div>';
	}
	
	return $content;
}

function sitemap_callback() {
	$content = '<p>Построитель карты сайта выполняет парсинг всех ключевых фраз в тегах meta. ' . 
				'В демо режиме НЕ сохраняет результаты в базу. Выполнение может занять много времени.</p>';
	
	$content .= view_sitemap_form();
	
	if(!empty($_POST['domain'])) {
		$host = $_POST['domain']; //'meparts.itmediagroup.ru'; // Хост сайта
		$scheme = 'http://'; // http или https?
		$site_links = get_site_links($host,$scheme);
		
		$content .= '<ul>';
		foreach($site_links['urls'] as $url=>$row) {
			$content .= '<li>' . $url . '<br><strong>keywords:</strong> ' . (isset($site_links['meta_keywords'][$url])?$site_links['meta_keywords'][$url]:'<span class="red">Отсутствует</span>') . '</li>';
		}
		$content .= '</ul>';
	}
	return $content;
}
