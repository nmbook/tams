<?php
require_once('../dbsetup.php');
require_once('utils.php');

class Session {
	private $_id;
	private $day;
	private $time;
	private $room;

	public function __construct($row) {
		$this->day = $row['day'];
		$this->time = $row['time'];
		$this->room = $row['room'];
		$this->_id = $row['id'];
	}

	public function getDay() { return $this->day; }
	public function getTime() { return $this->time; }
	public function getRoom() { return $this->room; }
	public function getID() { return $this->_id; }

	public static insertSession($day, $time, $room) {
		return Utils::getVoid('INSERT INTO sessions (day,time,room) VALUES (:day,:time,:room)',
			array(':day' => $day, ':time' => $time, ':room' => $room));
	}

	public static getSession($day,$time,$room) {
		return Utils::getSingle('SELECT * FROM sessions WHERE day=:day AND time=:time AND room=:room',
			array(':day' => $day, ':time' => $time, ':room' => $room),
			function ($x) { return Session($x); });
	}

}


?>
