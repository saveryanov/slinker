<?
 
/**
 * Database settings
 */
function get_config() {
	static $databases = array ( 
	  'database' => 'test-db',
	  'username' => 'test-db-user',
	  'password' => '1q2w3e4r',
	  'host' => 'localhost',
	  'port' => '',
	);
	return $databases;
}

define("TABLEPREFIX", "");
define("USERLOGIN", "admin");
define("USERPASSWORD", "admin");

// Настройки построителя карты сайта
define("DEFAULTSCHEME", 'http://');	

// Настройки поисковика
define("LIKENESSSTEP", 0.9);	// Порог похожести
define("LIKENESDEPTHCOEF", 0.0); // Поблажка на глубину url (чем меньше, тем реже будут встречаться ссылки в самую глубокую часть сайта)
define("KEYWORDPROXIMITY", 0); // Как далеко могут стоять друг от друга ключевые слова в тексте (целое число в словах, слова не несущие смысловой нагрузки не считаются)
define("ONLYDIFFERENTURLS", 1); // Добавлять в текст только разные ссылки (1 - не может быть двух ссылок на одну и ту же страницу)
define("LOWDEPTHPRIORITY", 1); // Приоритет ссылок с наименьшей вложенностью
define("ADDENDLINK", 1); // Добавлять в конец текста фразу "Вам также может быть интересно ..."
define("ADDENDLINKONLYIFNOTFOUND", 1); // Добавлять в конец текста фразу "Вам также может быть интересно ..." только если ни одной ссылки не добавлено (иначе если не кончился skiplen)

mb_internal_encoding("UTF-8");

// включим сессию
session_start();
