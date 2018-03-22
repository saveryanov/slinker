<?php
require_once("config.php");

/*
 * Выполняет подключение к БД
 * Возвращает PDO объект для работы с базой
 */
function get_db_handler($conf = array()) {
	if(empty($conf)) {
		$conf = get_config();
	}
	$dbh = new PDO('mysql:host=' . $conf['host'] . ';dbname=' . $conf['database'] . ';charset=UTF8', $conf['username'], $conf['password']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

/*
 * Пишет ошибку в лог файл
 */
function write_error_log($e, $query) {
	file_put_contents('error_log.txt', "\n" . date("d:m:Y H:i:s") . "\n" . $e->getMessage() . "\n" . $query . "\n", FILE_APPEND | LOCK_EX);
}

/*
 * Добавляет в БД ключевую фразу
 */
function add_keyphrase ($keyphrase, $path, $depth, $title) {
	$query = 'INSERT INTO ' . TABLEPREFIX . 'slinker_keyphrases(keyphrase, path, depth, title) VALUES(:keyphrase, :path, :depth, :title)';
		
	try {
		$dbh = get_db_handler();
		
		$sth = $dbh->prepare($query);
		$sth->execute(array(
			':keyphrase' => trim($keyphrase),
			':path' => trim($path),
			':depth' => $depth,
			':title' => $title,
		));
		
		return $dbh->lastInsertId();
		
	} catch (PDOException $e) {
		write_error_log($e, $query);
		die();
	}
}


/*
 * Удаляет из БД ключевую фразу
 */
function delete_keyphrase ($id) {
	$query = 'DELETE FROM ' . TABLEPREFIX . 'slinker_keyphrases WHERE id = :id';
		
	try {
		$dbh = get_db_handler();
		
		$sth = $dbh->prepare($query);
		$sth->execute(array(
			':id' => $id,
		));
		
		return true;
		
	} catch (PDOException $e) {
		write_error_log($e, $query);
		die();
	}
}

/*
 * Возвращает полную информацию о всех фразах и страницах
 */
function get_db_keyphrases ($order = 'RAND()') {
	
	$query = 'SELECT * FROM ' . TABLEPREFIX . 'slinker_keyphrases ORDER BY ' . $order;

	try {
		$dbh = get_db_handler();
		
		$result = array();
		foreach($dbh->query($query) as $row) {
			$result[] = $row;
		}
		
		return $result;
		
	} catch (PDOException $e) {
		write_error_log($e, $query);
		die();
	}
}


/*
 * Функция очистки таблиц
 */
function truncate_table ($table_name) {
	
	$query = 'TRUNCATE TABLE ' . TABLEPREFIX . $table_name;
	
	try {
		$dbh = get_db_handler();
		$sth = $dbh->prepare($query);
		$sth->execute();
	} catch (PDOException $e) {
		write_error_log($e, $query);
		die();
	}
}