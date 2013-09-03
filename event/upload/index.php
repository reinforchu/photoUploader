<?php
require_once('../../php_conf.inc');
require_once('../../resource/MySQL/MySQL.php');
require_once('../../resource/tmhOAuth/tmhOAuth.php');
require_once('../../resource/tmhOAuth/tmhUtilities.php');
$upload = new upload();

/**
* コンテンツアップロード
*
* @author @reinforchu
*/
class upload {
	public $file;
	public $ext;
	public $thumb;
	private $date;
	private $imgid;
	private $desc;
	private $ac;

	/**
	* コンストラクタ
	*/
	public function __construct() {
		session_start();
		$handle = fopen($_FILES['file']['tmp_name'], 'rb');
		$file = fread($handle, filesize($_FILES['file']['tmp_name']));
		fclose($handle);
		self::fileChk($_FILES['file']['tmp_name']);
		if ($this->ext === FALSE) self::redirect('http://pic.reinforce.tv/');
		$this->date = date('U');
		$this->imgid = uniqid(mt_rand(100, 999));
		$this->file = $file;
		self::createThumbnail($_FILES['file']['tmp_name']);
		$this->file = self::convertBase64($this->file);
		$this->thumb = self::convertBase64($this->thumb);
		$this->desc = htmlspecialchars($_POST['description']);
		$this->ac = htmlspecialchars($_POST['ac']);
		self::insertDb();
		if (preg_match("/^1$/iu", $_POST['tweet'])) self::tweetWithMedia();
		self::redirect("http://pic.reinforce.tv/event/view/?{$this->imgid}");
	}

	/**
	* ファイルタイプの判定
	*/
	private function fileChk($file) {
		switch(exif_imagetype($file)) { 
			case IMAGETYPE_GIF      : $this->ext = 'gif'; break;
			case IMAGETYPE_JPEG     : $this->ext = 'jpg'; break;
			case IMAGETYPE_PNG      : $this->ext = 'png'; break;
			case IMAGETYPE_BMP      : $this->ext = 'bmp'; break;
			default                 : $this->ext = FALSE;
		}
	}

	/**
	* DBへ追加
	*/
	private function insertDb() {
		$MySQL = new MySQL();
		$MySQL->insertImg($this->imgid, $this->date, $this->file, $this->thumb, $this->ext, "{$_SESSION['response']->id}", $this->ac, $this->desc);
	}

	/**
	* base64へエンコード
	*/
	private function convertBase64($file) {
		return base64_encode($file);
	}

	/**
	* サムネイル作成
	*/
	private function createThumbnail($filePath) {
		if (preg_match("/^jpg$/iu", $this->ext) == 1) {
			$img = imagecreatefromjpeg($filePath);
		} else if (preg_match("/^png$/iu", $this->ext) == 1) {
			$img = imagecreatefrompng($filePath);
		} else if (preg_match("/^gif$/iu", $this->ext) == 1) {
			$img = imagecreatefromgif($filePath);
		} else if (preg_match("/^bmp$/iu", $this->ext) == 1){
			$img = imagecreatefromwbmp($filePath);
		} else {
			return FALSE;
		}
		$width = imagesx($img);
		$height = imagesy($img);
		$gd = imagecreatetruecolor(150, 150);
		imagecopyresampled($gd, $img, 0,0,0,0, 150, 150, $width, $height);
		imagejpeg($gd, "R:\\{$this->imgid}.jpg", 80);
		imagedestroy($gd);
		$handle = fopen("R:\\{$this->imgid}.jpg", 'rb');
		$file = fread($handle, filesize("R:\\{$this->imgid}.jpg"));
		fclose($handle);
		unlink("R:\\{$this->imgid}.jpg");
		$this->thumb = $file;
	}

	/**
	* twitterへPOST
	*/
	private function tweetWithMedia() {
		$tmhOAuth = new tmhOAuth(array(
			'consumer_key'=>'',
			'consumer_secret'=>'',
			'user_token'=>"{$_SESSION['access_token']['oauth_token']}",
			'user_secret'=>"{$_SESSION['access_token']['oauth_token_secret']}",
		));
		$code = $tmhOAuth->request(
			'POST',
			'https://upload.twitter.com/1/statuses/update_with_media.json',
			array(
				'media[]' => "@{$_FILES['file']['tmp_name']};type=image/{$this->ext};filename={$_FILES['file']['tmp_name']}",
				'status'  => "{$this->desc} - http://pic.reinforce.tv/event/view/?{$this->imgid}",
			),
			true, // use auth
			true  // multipart
		);
	}

	/**
	* Homeへリダイレクト
	*/
	private function redirect($url) {
		header('HTTP/1.1 301 Moved Permanently');
		header("Location: {$url}");
	}

}