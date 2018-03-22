<?php
require_once("config.php");
require_once("templates.php");
require_once("database.php");
require_once('libs/Smarty.class.php');


function get_url_depth($url) { 
	$url_path = parse_url($url, PHP_URL_PATH);
	if(substr($url_path, -1) != '/') {
		$url_path .= '/';
	}
	$depth = count(explode('/', ($url_path))) - 2;
	if($depth < 0) {
		$depth = 0;
	}
	return $depth;
}

function mbUcfirst($str, $enc = 'utf-8') { 
	return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc); 
}

function is_valid_tag($tag) {
	$valid_tags = array('', 'p', 'div', 'span', 'li', 'strong', 'b', 'i', 'ul', 'ol');
	return in_array($tag, $valid_tags);
}

/*
 * Обработчик выхода из аккаунта
 */
function logout() {
	unset($_SESSION['slinker']);	
    header("location:".$_SERVER['PHP_SELF']);
}

/*
 * Обработчик авторизации
 */
function login() {
	if($_POST['login'] == USERLOGIN && $_POST['password'] == USERPASSWORD){
		$_SESSION['slinker'] = array();
		$_SESSION['slinker']['auth'] = true;
	}
}

/*
 * Вывод переменной в тегах pre
 */
function dpm($var, $title = 'Вывод переменной') {
	echo '<hr>';
	echo '<pre>';
	echo ('<strong>' . $title . '</strong></br>');
	print_r($var);
	echo '</pre>';
	echo '<hr>';
}

/*
 * Возвращает красиво оформленную переменную в строке
 */
function dpms($var, $title = 'Вывод переменной') {
	
	$out = '';
	$out .= '<div style="padding: 1em; margin: 1em auto; border: 1px solid #909090; background-color: #fff; overflow: auto; width: 100%;">';
	$out .= '<pre>';
	$out .= ('<strong style="font-size: 1.2em;">' . $title . '</strong><hr></br>');
	$out .= htmlspecialchars(print_r($var, true));
	$out .= '</pre>';
	$out .= '</div>';
	return $out;
}

/*
 * Преобразовывает строку в массив
 */
function mbStringToArray ($string) { 
    $strlen = mb_strlen($string); 
    while ($strlen) { 
        $array[] = mb_substr($string,0,1,"UTF-8"); 
        $string = mb_substr($string,1,$strlen,"UTF-8"); 
        $strlen = mb_strlen($string); 
    } 
    return $array; 
} 

/*
 * Парсер мета-тегов на регулярных выражениях
 */
function getMetaTags($str) {
  $pattern = '
  ~<\s*meta\s

  # using lookahead to capture type to $1
    (?=[^>]*?
    \b(?:name|property|http-equiv)\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  )

  # capture content to $2
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>

  ~ix';
  
  if(preg_match_all($pattern, $str, $out))
    return array_combine($out[1], $out[2]);
  return array();
}



/*
 * Парсер H1 на регулярных выражениях
 */
function getH1($str) {
  $pattern =  '`<h1(.*?)>(.*?)</h1>`im';
  
  if(preg_match_all($pattern, $str, $out))
    return implode(', ', $out[2]);
  return '';
}


/*
 * Функция сбора карты сайта и ключевых фраз
 */
function sitemap_geturls($page,&$host,&$scheme,&$nofollow,&$extensions,&$urls,&$keywords,&$h1s) {
	//Возможно уже проверяли эту страницу
	if($urls[$page]==1){continue;}
	
	//Получаем содержимое ссылки. если недоступна, то заканчиваем работу функции и удаляем эту страницу из списка
	// Аналогично via cURL
	$ch = curl_init();
    // set url
    curl_setopt($ch, CURLOPT_URL, $page);
    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    // $output contains the output string
    $content = curl_exec($ch);
    // close curl resource to free up system resources
    curl_close($ch); 
	
	if(!$content){
		unset($urls[$page]);
		return false;
	}
	
	// Отмечаем ссылку как проверенную (мы на ней побывали)
	$urls[$page]=1;
	
	// Считываем все мета теги
	$tags = getMetaTags($content);
	if(!empty($tags['keywords'])) {
		$keywords[$page] = $tags['keywords'];     // php documentation
	} else {
		$keywords[$page] = '';
	}
	// Считываем все h1 теги
	$h1s[$page] = getH1($content);
	
	
	
	
	//Проверяем не стоит ли запрещающий индексировать ссылки на этой странице мета-тег с nofollow|noindex|none
	if(preg_match('/<[Mm][Ee][Tt][Aa].*[Nn][Aa][Mm][Ee]=.?("|\'|).*[Rr][Oo][Bb][Oo][Tt][Ss].*?("|\'|).*?[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=.*?("|\'|).*([Nn][Oo][Ff][Oo][Ll][Ll][Oo][Ww]|[Nn][Oo][Ii][Nn][Dd][Ee][Xx]|[Nn][Oo][Nn][Ee]).*?("|\'|).*>/',$content)){$content=NULL;}
	
	//Собираем все ссылки со страницы во временный массив, с помощью регулярного выражения.
	preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/",$content,$tmp);$content=NULL;
	
	//Добавляем в массив links все ссылки не имеющие аттрибут nofollow
	$links = array();
	foreach($tmp[0] as $k => $v){
		if(!preg_match('/<.*[Rr][Ee][Ll]=.?("|\'|).*[Nn][Oo][Ff][Oo][Ll][Ll][Oo][Ww].*?("|\'|).*/',$v)){
			$links[$k]=$tmp[1][$k];
		}
	}
	unset($tmp);
    
	//Обрабатываем полученные ссылки, отбрасываем "плохие", а потом и с них собираем...
	foreach($links as $i=>$link) {
		
		//Если слишком много ссылок в массиве, то пора прекращать нашу деятельность (читай спецификацию)
		if(count($urls)>49900){return false;}
		
		//Если не установлена схема и хост ссылки, то подставляем наш хост
		if(!strstr($links[$i],$scheme.$host)) {
			$links[$i] = $scheme.$host.$links[$i];
		}
		
		//Убираем якори у ссылок
		$links[$i]=preg_replace("/#.*/X", "",$links[$i]);
		
		//Узнаём информацию о ссылке
		$urlinfo=@parse_url($links[$i]);
		if(!isset($urlinfo['path'])){$urlinfo['path']=NULL;}
		
		//Если хост совсем не наш, ссылка на главную, на почту или мы её уже обрабатывали - то заканчиваем работу с этой ссылкой
		if((isset($urlinfo['host']) AND $urlinfo['host']!=$host) OR $urlinfo['path']=='/' OR isset($urls[$links[$i]]) OR strstr($links[$i],'@')){continue;}
		
		//Если ссылка в нашем запрещающем списке, то также прекращаем с ней работать
		$nofoll=0;if($nofollow!=NULL){foreach($nofollow as $of){if(strstr($links[$i],$of)){$nofoll=1;break;}}}if($nofoll==1){continue;}
		
		//Если задано расширение ссылки и оно не разрешёно, то ссылка не проходит
		$ext=end(explode('.',$urlinfo['path']));
		$noext=0;
		if($ext!='' AND strstr($urlinfo['path'],'.') AND count($extensions)!=0){$noext=1;foreach($extensions as $of){if($ext==$of){$noext=0;continue;}}}
		if($noext==1){continue;}
		
		//Заносим ссылку в массив и отмечаем непроверенной (с неё мы ещё не забирали другие ссылки)
		$urls[$links[$i]]=0;
		
		//Проверяем ссылки с этой страницы
		sitemap_geturls($links[$i],$host,$scheme,$nofollow,$extensions,$urls,$keywords,$h1s);
	}
	return true;
}


function get_site_links($host,$scheme) {
	// Поможет при длительном выполнении скрипта
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
	set_time_limit(0);
	$urls = array(); // Здесь будут храниться собранные ссылки
	$meta_keywords = array(); // Здесь будут храниться собранные ссылки
	
	// Здесь ссылки, которые не должны попасть в sitemap.xml
	$nofollow = array('/go.php','/search/','/404/');
	// Первой ссылкой будет главная страница сайта, ставим ей 0, т.к. она ещё не проверена
	$urls[$scheme.$host] = '0';
	$meta_keywords[$scheme.$host] = '';
	// Разрешённые расширения файлов, чтобы не вносить в карту сайта ссылки на медиа файлы. Также разрешены страницы без разрешения, у меня таких страниц подавляющее большинство.
	$extensions = array('php', 'aspx', 'htm', 'html', 'asp', 'cgi', 'pl');
	
	// (START!) Первоначальный старт функции для проверки главной страницы и последующих
	sitemap_geturls($scheme.$host,$host,$scheme,$nofollow,$extensions,$urls,$meta_keywords,$h1s);
	
	return array(
		'urls' => $urls,
		'meta_keywords' => $meta_keywords,
		'h1' => $h1s,
	);
}

function get_keyphrases($order = 'RAND()') {
	$keywords = get_db_keyphrases($order);
		
	$keyphrases = array();
	foreach($keywords as $ind=>$keyword) {
		$keyphrases[(string)$keyword['id']] = array(
			'keyphrase' => $keyword['keyphrase'],
			'path' => $keyword['path'],
			'path_depth' => $keyword['depth'],
			'title' => $keyword['title'],
			'phrase_id' => $keyword['id'],
		);
	}
	
	return $keyphrases;
}