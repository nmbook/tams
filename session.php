<?php
require_once('../dbsetup.php');
require_once('utils.php');

class Session {
	private $_id;
	private $weekday;
	private $start_time;
	private $end_time;
	private $crn;
	private $room;

	public function __construct($row) {
		$this->weekday = $row['weekday'];
		$this->start_time = $row['start_time'];
		$this->end_time = $row['end_time'];
		$this->room = $row['room'];
		$this->crn = $row['crn'];
		$this->_id = $row['id'];
	}

	public function getWeekday() { return $this->weekday; }
	public function getStartTime() { return $this->start_time; }
	public function getEndTime() { return $this->end_time; }
	public function getRoom() { return $this->room; }
	public function getCrn() { return $this->crn; }
	public function getID() { return $this->_id; }

	public static function import($arr) {
		foreach ($arr as $row) {
			$row2 = array();
			foreach ($row as $key => $value) {
				$row2[':' . $key] = $value;	
			}
			Utils::getVoid('INSERT INTO sessions (weekday,start_time,end_time,room,crn) VALUES (:weekday,:start_time,:end_time,:room,:crn)',
				$row2);
		}
	}

}

