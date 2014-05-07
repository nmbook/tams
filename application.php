<?php
require_once('../dbsetup.php');
require_once('utils.php');
require_once('course.php');

class Application {
	private $crn;
	private $netid;
	private $for_credit;
	private $state;
	private $time_signup;
	private $time_response;

	public function __construct($row) {
		$this->crn = $row['crn'];
		$this->netid = $row['netid'];
		$this->for_credit = $row['crn'];
		$this->state = $row['state'];
		$this->time_signup = $row['time_signup'];
		$this->time_response = $row['time_response'];
	}

	public function getCrn() { return $this->crn; }
	public function getNetid() { return $this->netid; }
	public function getForCredit() { return $this->for_credit; }
	public function getState() { return $this->state; }
	public function getTimeSignup() { return $this->time_signup; }
	public function getTimeResponse() { return $this->time_response; }

	public function setState($newState) {
		if ($newState == 'approved') {
			$course = Course::getCourseByCrn($this->crn);
			if (count($course->getApprovedApplications()) >= $course->getPositions()) {
				throw new TamsException(TamsException::E_GENERAL);
			}
		}
		Utils::getVoid('UPDATE applications SET state=:state,time_response=NOW() WHERE crn=:crn AND netid=:netid',
			array(':state' => $newState, ':crn' => $this->crn, ':netid' => $this->netid));
		$this->state = $newState;
	}

	public static function getByName($crn,$netid) {
		return Utils::getSingle('SELECT * FROM applications WHERE crn=:crn AND netid=:netid',
			array(':crn' => $crn, ':netid' => $netid),
			function ($x) { return new Application($x); });	
	}

}

