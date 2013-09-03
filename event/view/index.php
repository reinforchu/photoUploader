<?php
require_once('../../php_conf.inc');
require_once('../../resource/MySQL/MySQL.php');
$view = new view();

/**
* コンテンツ表示
*
* @author @reinforchu
*/
class view {
	public $imgdata;
	public $imgid;

	/**
	* コンストラクタ
	*/
	public function __construct() {
		session_start();
		if (empty($_SESSION)) self::redirect('http://pic.reinforce.tv/?authenticate=1');
		$this->imgid = htmlspecialchars($_SERVER['QUERY_STRING']);
		self::getImage();
		if (!empty($this->imgdata)) {
			include('./success.inc');
		} else {
			include_once('../../httperrors/404.php');
		}
	}

	/**
	* imgIdからイメージ情報を取得
	*/
	private function getImage() {
		$mysql = new MySQL();
		$this->imgdata = $mysql->getPhoto($this->imgid);
	}

	/**
	* Homeへリダイレクト
	*/
	private function redirect($url) {
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: {$url}");
	}
}