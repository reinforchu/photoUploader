<?php
require_once('../../php_conf.inc');
require_once('../../resource/MySQL/MySQL.php');
new delete();

/**
* コンテンツ削除
*
* @author @reinforchu
*/
class delete {
	private $imgid;

	/**
	* コンストラクタ
	*/
	public function __construct() {
		session_start();
		$this->imgid = htmlspecialchars($_SERVER['QUERY_STRING']);
		self::updateDb();
		self::redirect();
	}

	/**
	* DB更新(削除)
	*/
	private function updateDb() {
		$MySQL = new MySQL();
		$MySQL->deleteImg($this->imgid, "{$_SESSION['response']->id}");
	}

	/**
	* Homeへリダイレクト
	*/
	private function redirect() {
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: http://pic.reinforce.tv/");
	}

}