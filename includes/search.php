<?php 
require_once "stemmer.php";


/*
 * Подготовка слова
 */
function search_prepare_word($word) {
	$word_new = str_replace(search_get_chars_to_delete(), '', mb_strtolower($word, 'UTF-8'));
	$word_new = str_replace('ё', 'е', mb_strtolower($word_new, 'UTF-8'));
	return $word_new;
}

/*
 * Возвращает массив символов, которые разделяют слова
 */
function search_get_chars_to_delete() {
	$chars_to_delete = array("\s", "\n", "\r", ' ', ',', ':', ';', '.', '!', '?', '"', '\'', '#', '$', '%', '^', '&', '*', '(', ')', '[', ']', '{', '}', '@', '<', '>', '~', '`');
	return $chars_to_delete;
}

/*
 * Разбивает текст на слова, теги и разделители
 */
function search_explode($text, $deletelinks = 0) {
	$tag_stack = array('');
	
	$not_valid_words = search_get_not_valid_words();
	$text_chars = mbStringToArray($text);
	
	$word = '';
	$words = array();
	$is_for_text_block = true;
	$tag = '';
	foreach($text_chars as $i=>$char) {
		if($char == '<') {
			if(!empty($word)) {
				$prepared = search_prepare_word($word);
				$stemmed = stem($prepared);
				$valid_word = intval(!in_array($prepared, $not_valid_words));
				if(mb_strlen($stemmed) < 3) {
					$valid_word = 0;
				}
				$words[] = array('val' => $word, 'i' => $i, 'type' => 'word', 'prepared' => $prepared, 'stemmed' => $stemmed, 'valid' => $valid_word, 'tag' => end($tag_stack));
				$word = '';							
			}
			
			$tag .= $char;
			$is_for_text_block = false;
			continue;
		}
		if($char == '>') {
			$tag .= $char;
			
			$tag_chars = mbStringToArray($tag);
			$tagtype = 'open';
			if($tag_chars[1] == '/') {
				$tagtype = 'close';
			}
			$tagname = '';
			for($tag_i = (($tagtype == 'close')?2:1); $tag_i < count($tag_chars); $tag_i++) {
				if(in_array($tag_chars[$tag_i], array(' ', '>'))) {
					break;
				}
				$tagname .= $tag_chars[$tag_i];
			}
			
			if($tagtype == 'open') {
				if(!($deletelinks && $tagname == 'a')) {
					$tag_stack[] = $tagname;
				} else {
					$tag_stack[] = end($tag_stack);
				}
			} else {
				array_pop($tag_stack);
			}
			
			
			if(!($deletelinks && $tagname == 'a')) {
				$words[] = array('val' => $tag, 'i' => $i, 'type' => 'tag', 'tagtype' => $tagtype, 'tagname' => $tagname);
			}
			$tag = '';
			$is_for_text_block = true;
			continue;
		}
		
		if($is_for_text_block) {
			if(is_word_char($char)) {
				$word .= $char;
			} else {
				if(!empty($word)) {
					$prepared = search_prepare_word($word);
					$stemmed = stem($prepared);
					$valid_word = intval(!in_array($prepared, $not_valid_words));
					if(mb_strlen($stemmed) < 3) {
						$valid_word = 0;
					}
					$words[] = array('val' => $word, 'i' => $i, 'type' => 'word', 'prepared' => $prepared, 'stemmed' => $stemmed, 'valid' => $valid_word, 'tag' => end($tag_stack));
					$word = '';							
				}
				$words[] = array('val' => $char, 'i' => $i, 'type' => 'delimiter', 'tag' => end($tag_stack));
			}
		} else {
			$tag .= $char;
		}
		
	}
	if(!empty($word)) {
		$prepared = search_prepare_word($word);
		$stemmed = stem($prepared);
		$valid_word = intval(!in_array($prepared, $not_valid_words));
		if(mb_strlen($stemmed) < 3) {
			$valid_word = 0;
		}
		$words[] = array('val' => $word, 'i' => $i, 'type' => 'word', 'prepared' => $prepared, 'stemmed' => $stemmed, 'valid' => $valid_word, 'tag' => end($tag_stack));
		$word = '';							
	}
	return $words;
}


/*
 * Проверяет является ли символ буквой
 */
function is_word_char($char) {
	$char = mb_strtolower($char);
	$chars = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ы', 'ъ','ь','э','ю','я',
				   'a','b','s','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','-');
	return in_array($char, $chars);
}

/*
 * Возвращает массив слов не несущих смысловой нагрузки
 */
function search_get_not_valid_words() {
	$not_valid_words = array(
	   'купить', 'спб', 'мск', 'заказать', 'заказ', 'оформить', 'смотреть', 'онлайн', 'регистрации', 'смс',
	   'интернет', 'магазин', 'цена', 'цены', 'сколько',
	   'а', 'но', 'да', 'нет', 'есть', 'тому', 'вы', 'мы', 
	   'он', 'она', 'оно', 'они', 'ты', 'я', 'все', 'его', 'ee', 'eё', 
	   'по', 'у', 'о', 'из', 'до', 'в', 'бы',
	   'с', 'не', 'к', 'на', 'от', 'около', 'об', 'за', 'и',
	   'что', 'как', 'так', 'только', 'мне', 'было', 'вот',
	   'меня', 'еще', 'нет', 'ему', 'теперь', 'когда', 'даже', 'вдруг', 'если',
	   'уже', 'или', 'быть', 'был', 'него', 'вас', 'нибудь', 'опять', 'вам', 'ведь',
	   'там', 'потом', 'себя', 'может', 'тут', 'где', 'есть', 'надо', 'ней',
	   'для', 'тебя', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего', 'раз',
	   'тоже', 'себе', 'под', 'будет', 'тогда', 'кто', 'этот', 'того', 'потому',
	   'этого', 'какой', 'ним', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы',
	   'нее', 'были', 'куда', 'зачем', 'всех', 'можно', 'при', 'два', 'другой',
	   'хоть', 'после', 'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'них',
	   'какая', 'много', 'разве', 'три', 'эту', 'моя', 'мои', 'мою', 'свою', 'этой', 'перед',
	   'чуть', 'том', 'такой', 'более', 'всю'
	);
	return $not_valid_words;
}


/*
 * Возвращает массив стеммированой фразы
 */
function search_stem_keyphrase($keyword_phrase) {
	$keywords = mb_split("\s", $keyword_phrase);
	$keywords_prepared = array();
	foreach($keywords as $keyword) {
		$keyword_prepared = search_prepare_word($keyword);
		if(in_array($keyword_prepared, search_get_not_valid_words()) || empty($keyword)) {
			continue;
		}
		$keywords_prepared[] = $keyword_prepared;
	}
	$keywords_stemmed = array();
	//$stemmer = new Lingua_Stem_Ru();
	foreach($keywords_prepared as $keyword) {
		if(mb_strlen($keyword, 'UTF-8') > 3) {
			$keywords_stemmed[] = mb_strtolower(stem($keyword), 'UTF-8');
		} else {
			$keywords_stemmed[] = $keyword;
		}
	}
	return $keywords_stemmed;
}


/*
 * Возвращает номер слова в ключевой фразе на которое похоже слово $word
 */
function is_match_keyphrase_array($word, $keywords_stemmed) {

	$in_array_like = 0;
	foreach($keywords_stemmed as $key=>$keyword) {
		if((mb_strpos($word, $keyword) !== false) || (mb_strpos($keyword, $word) !== false)) {
			$in_array_like = $key + 1;
		}
	}
	return $in_array_like;
}





/*
 * Выполняет поиск в тексте массива ключевых фраз.
 * $deletelinks - нужно ли удалять существующие в тексте теги <a> </a>
 */
function search_keyphrases_in_html($text, $keyphrases, $deletelinks = 0, $word_proximity = KEYWORDPROXIMITY, $word_likenessstep = LIKENESSSTEP, $depth_coef = LIKENESDEPTHCOEF) {

	$words = search_explode($text, $deletelinks);	
	$words_len = count($words);
	
	foreach($keyphrases as $phrase_id=>$keyphrase) {
		if(!empty($keyphrase['keyphrase'])) {
			$depth = $keyphrase['path_depth'];
			
			// Подготовка ключевой фразы для поиска
			$keywords_stemmed = search_stem_keyphrase($keyphrase['keyphrase']);
			$keyphrase_len = count($keywords_stemmed);
			
			$text_output = '';
			
			// Установка флагов прямой похожести и удаление (если нужно) всех ссылок
			foreach($words as $key=>$word) {
				if(!isset($words[$key]['match'])) {
					$words[$key]['match'] = 0;
				}
				if($word['type'] == 'word' && $word['valid']) {
					$match_ind = is_match_keyphrase_array($word['stemmed'], $keywords_stemmed);
					$match = ($match_ind?1:0);
					if($match) {
						if(!isset($words[$key]['phrases'][$phrase_id])) {
							$words[$key]['phrases'][$phrase_id] = array();
							$words[$key]['phrases'][$phrase_id]['match'] = 0;
							$words[$key]['phrases'][$phrase_id]['match_ind'] = 0;
							$words[$key]['phrases'][$phrase_id]['phrase_id'] = $phrase_id;
						}
						$words[$key]['phrases'][$phrase_id]['match_ind'] = $match_ind;
						$words[$key]['phrases'][$phrase_id]['phrase_id'] = $phrase_id;
						$words[$key]['phrases'][$phrase_id]['match'] = $match;
						$words[$key]['phrases'][$phrase_id]['match_keyword'] = $keywords_stemmed[$match_ind - 1];
						$words[$key]['phrases'][$phrase_id]['match_keyphrase'] = $keyphrase['keyphrase'];
						$words[$key]['match'] = 1;
					}
				}
				
			}
			
			// Соединение разорванных фраз разделителями
			foreach($words as $key=>$word) {
				//if($key == ($words_len - 1)) continue; // последний не смотрим
				if($word['type'] != 'word') continue;				// только для слов
				
				if(!empty($word['phrases'][$phrase_id]['match'])) {
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
						if(!empty($words[$key + $i]['phrases'][$phrase_id]['match'])) {			// нашли похожее слово. отбой
							$proximity_match = $i;
							break;
						}
					}
					if($proximity_match) {
						for($i = 1; $i <= $proximity_match; $i++) {
							
							if(!isset($words[$key + $i]['phrases'][$phrase_id])) {
								$words[$key + $i]['phrases'][$phrase_id] = array();
								$words[$key + $i]['phrases'][$phrase_id]['match'] = 0;
								$words[$key + $i]['phrases'][$phrase_id]['match_ind'] = 0;
								$words[$key + $i]['phrases'][$phrase_id]['phrase_id'] = $phrase_id;
							}
							// все слова в этом промежутке норм
							$words[$key + $i]['phrases'][$phrase_id]['match'] = 1;
							$words[$key + $i]['match'] = 1;
						}
					}
				}
			}
			
			// Полная линковка массива слов
			$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
			$link_start_key = 0;
			foreach($words as $key=>$word) {
							
				if ($word['type'] == 'word' && is_valid_tag($word['tag'])) { // если это слово и оно в валидном теге
					if (!empty($word['phrases'][$phrase_id]['match'])) { // если слово совпадает с словом из ключевой фразы...
						if($word['phrases'][$phrase_id]['match_ind']) {
							$keyphrase_matches[$word['phrases'][$phrase_id]['match_ind'] - 1] = 1;	// то фиксируем это в массиве
						}
					} else {
						$link_start_key = $key + 1;
					}
					/*
					if(!array_sum($keyphrase_matches)) {
						if (empty($word['phrases'][$phrase_id]['match'])) {
							$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
						}
					}*/
				} elseif ($word['type'] == 'tag') { // если тег то обрываем фразу
					$link_start_key = $key + 1;
					$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
				} elseif ($word['type'] == 'delimiter') {
					if(!array_sum($keyphrase_matches)) {	// фраза не может начинаться с разделителем
						$link_start_key = $key + 1;
						$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
					}
				}
				
				// Если набрана достаточная похожесть фразы...
				if(array_sum($keyphrase_matches) >= (((float)$keyphrase_len*$word_likenessstep) - ((float)$depth_coef*intval($depth)))) { 
					if(is_valid_tag($words[$link_start_key]['tag'])) {
						$words[$key]['link_ends'][$phrase_id] = true;
						$words[$key]['phrases'][$phrase_id]['link_end'] = true;
						for($i_start_key = $link_start_key; $i_start_key < $key; $i_start_key++ ) {
							if($words[$i_start_key]['type'] == 'word') {
								$words[$i_start_key]['link_starts'][$phrase_id] = '<a href="' . $keyphrase['path'] . '" title="' . mbUcfirst($keyphrase['keyphrase'], 'UTF-8') . '" data-linkedby="slinker">';
								$words[$i_start_key]['phrases'][$phrase_id]['link_start'] = '<a href="' . $keyphrase['path'] . '" title="' . mbUcfirst($keyphrase['keyphrase']) . '">';					
							}
						}
					}
					$keyphrase_matches = array_fill(0, $keyphrase_len, 0);
				}
			}
			
		}
	}
	return $words;
}

/*
 * Сборка текста обратно
 */
function search_create_text_from_words(
	$words, 
	$skiplen = 0, 
	$keyphrases = array(),
	$current_url = '',
	$low_depth_priority = LOWDEPTHPRIORITY, 
	$only_different_urls = ONLYDIFFERENTURLS, 
	$add_end_link = ADDENDLINK, 
	$add_end_link_only_if_not_found = ADDENDLINKONLYIFNOTFOUND
	) {
		
		
	$output_text = '';
	$last_link_i = 0;
	$is_first_link = true;
	$current_i = 0;
	$is_link = false;
	$link_phrase_id = 0;
	
	$placed_urls = array();
	$counters = array(
		'placed' => 0,
	);
	foreach($words as $key=>$word) {
		if(!$is_link) {
			if(($current_i - $last_link_i) > $skiplen || $is_first_link) {
				if(!empty($word['link_starts'])) {
					
					// Если есть low_depth_priority то оставляем только самые минимальные path_depth
					$lowdepth_link_starts = array();
					if($low_depth_priority) {
						$min_depth = 99999999;
						foreach($word['link_starts'] as $link_key=>$link_start) {
							if($min_depth > $keyphrases[$link_key]['path_depth']) {
								$min_depth = $keyphrases[$link_key]['path_depth'];
							}
						}
						foreach($word['link_starts'] as $link_key=>$link_start) {
							if($min_depth == $keyphrases[$link_key]['path_depth']) {
								$lowdepth_link_starts[$link_key] = $link_start;
							}
						}
					} else {
						$lowdepth_link_starts = $word['link_starts'];
					}
					
					// Если есть only_different_urls то удаляем повторы
					$link_starts = array();
					if($only_different_urls) {
						foreach($lowdepth_link_starts as $link_key=>$link_start) {
							if(!in_array($keyphrases[$link_key]['path'], $placed_urls)) {
								$link_starts[$link_key] = $link_start;
							}
						}
					} else {
						$link_starts = $lowdepth_link_starts;
					}
					
					if(!empty($link_starts)) {
						$link_phrase_id = array_rand($link_starts, 1);
						//$link_phrase_id = $link_phrase_id[0];
						$output_text .= $word['link_starts'][$link_phrase_id];
						$is_link = true;
						$is_first_link = false;
						$placed_urls[] = $keyphrases[$link_phrase_id]['path'];
					}
				}
			}
		} 
		
		if($word['type'] != 'tag') {
			$current_i += mb_strlen($word['type'], 'UTF-8');
		}
		
		$output_text .= $word['val'];
		
		if($is_link) {
			if(!empty($word['link_ends'][$link_phrase_id])) {
				$output_text .= '</a>';
				$last_link_i = $current_i;
				$is_link = false;
				$counters['placed']++;
			}
		}
	}
	
	// Если надо добавлять ссылку в конец текста...
	if($add_end_link) {
		$needs_end_link = false;
		if($add_end_link_only_if_not_found && !$counters['placed']) {
			$needs_end_link = true;
		} elseif(($current_i - $last_link_i) > $skiplen || $is_first_link) {
			$needs_end_link = true;
		}
		if($needs_end_link) {
			$current_depth = (!empty($current_url))?get_url_depth($current_url):1;
			$same_depth_paths = array();
			if($current_depth <= 0) {
				$current_depth = 1;
			}
			foreach($keyphrases as $phrase_id=>$keyphrase) {
				if($keyphrase['path_depth'] == $current_depth) {
					$same_depth_paths[] = $keyphrase;
				}
			}
			$link_phrase_id = 0;
			$end_link_keyphrase = array();
			if(!empty($same_depth_paths)) {
				$end_link_keyphrase = $same_depth_paths[array_rand($same_depth_paths, 1)];
			} else {
				$end_link_keyphrase = $keyphrases[array_rand($keyphrases, 1)];
			}
			$end_link = '<a href="' . $end_link_keyphrase['path'] . '" title="' . mbUcfirst($end_link_keyphrase['keyphrase'], 'UTF-8') . '" data-linkedby="slinker">' . mbUcfirst($end_link_keyphrase['title'], 'UTF-8') . '</a>';
			$output_text .= '<p>Также Вам может быть интересно: ' . $end_link . '.</p>';
			
		}
	}
	
	return $output_text;
}



