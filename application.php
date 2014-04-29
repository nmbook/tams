<?php
require_once('../dbsetup.php');
require_once('utils.php');

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
	public function getTimeSignup() { return $this->time_signup; }
	public function getTimeResponse() { return $this->time_response; }

	public function setState($newState) {
		$dt = new DateTime();
		Utils::getVoid('UPDATE applications SET state=:state,time_response=:time_response WHERE crn=:crn AND netid=:netid',
			array(':state' => $newState, ':time_response' => $dt->format('H:i:s'), ':crn' => $this->crn, ':netid' => $this->netid));
		$this->state = $newState;
	}

}

