<?php
/**
* MySQL Server管理
*
* @author @reinforchu
*/
class MySQL {
	public $mysqli;
	private $serverName;
	private $userName;
	private $userPassword;
	private $dbName;

	/**
	* コンストラクタ
	*/
	public function __construct() {
		$this->serverName = 'localhost';
		$this->userName = 'root';
		$this->userPassword = '';
		$this->dbName = '';
		self::connectDB();
	}

	/**
	* サーバへログイン
	*/
	private function connectDB() {
		$this->mysqli = new mysqli($this->serverName, $this->userName, $this->userPassword, $this->dbName);
		if ($this->mysqli->connect_error) {
			print("SYSTEM>Connect\040Error\040(" . $this->mysqli->connect_errno . ') '. $this->mysqli->connect_error. "\n");
		} else {
			mysqli_set_charset($this->mysqli, "utf8");
		}
	}

	public function getPhotosCount($userid) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "SELECT owner, COUNT(owner) FROM img GROUP BY owner HAVING owner = '$userid'";
		$this->mysqli->multi_query($query);
		$result = $this->mysqli->store_result();
	//	printf("Errormessage: %s\n", $this->mysqli->error);
		$row = $result->fetch_row();
		if (preg_match("/[0-9]/iu", $row['1']) == 0) $row['1'] = 0;
		return $row['1'];
	}

	public function getPhoto($imgid) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "SELECT * FROM img WHERE imgid = '$imgid'";
		$this->mysqli->multi_query($query);
		$result = $this->mysqli->store_result();
		$row = $result->fetch_assoc();
		return $row;
	}

	public function getPhotos($userid) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "SELECT * FROM img WHERE owner = '$userid' ORDER BY date DESC";
		$this->mysqli->multi_query($query);
		$result = $this->mysqli->store_result();
		return $result;
	}

	public function getRecentPhotos($userid) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "SELECT * FROM img WHERE owner = '$userid' ORDER BY date DESC LIMIT 0, 3";
		$this->mysqli->multi_query($query);
		$result = $this->mysqli->store_result();
		return $result;
	}

	public function insertImg($imgid, $date, $full, $thumb, $ext, $owner, $ac, $description) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "INSERT INTO img (imgid, date, full, thumb, ext, owner, ac, description) VALUES ('$imgid', '$date', '$full', '$thumb', '$ext', '$owner', '$ac', '$description');";
		$this->mysqli->query($query);
	}

	public function deleteImg($imgid, $userid) {
		if (mysqli_connect_errno()) printf("MySQL\040>Connect\040failed:\040%s\n", mysqli_connect_error());
		$query = "DELETE FROM img WHERE imgid = '$imgid' AND owner = '$userid';";
		$this->mysqli->query($query);
	}
}