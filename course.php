<?php
require_once('../dbsetup.php');
require_once('utils.php');

class Course {
	private $crn;
	private $year;
	private $semester;
	private $department;
	private $positions;
	private $instructors;
	private $applications;
	private $pending;
	private $approved;
	private $denied;

	public function __construct($row) {
		$this->crn = $row['crn'];
		$this->year = $row['year'];
		$this->semester = $row['semester'];
		$this->department = $row['department'];
		$this->positions = $this['position_count'];
		$this->instructors = NULL;
		$this->applications = NULL;
		$this->pending = NULL;
		$this->approved = NULL;
		$this->denied = NULL;
	}

	public function getCrn() { return $this->crn; }
	public function getYear() { return $this->year; }
	public function getSemester() { return $this->semester; }
	public function getDepartment() { return $this->department; }
	public function getPositionCount() { return $this->positions; }

	public function getInstructors() {
		if ($this->instructors == NULL) {
			$this->instructors = Utils::getMapping('SELECT * FROM teaches JOIN instructors 
				ON teaches.instructor_id=instructors.id 
				WHERE teaches.crn=:crn',
				array(':crn' => $this->crn),
				function ($x) { return Instructor($x); });
		}
		return $this->instructors;
	}

	public function getAllApplications() {
		if ($this->applications == NULL) {
			$this->applications = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn',
				array(':crn' => $this->crn),
				function ($x) { return Application($x); });
		}
		return $this->applications;
	}

	public function getPendingApplications() {
        if ($this->pending == NULL) {
            $this->pending = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'pending\'',
                array(':crn' => $this->crn),
                function ($x) { return Application($x); });
        }
        return $this->pending;
    }

	public function getApprovedApplications() {
        if ($this->approved == NULL) {
            $this->approved = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'approved\'',
                array(':crn' => $this->crn),
                function ($x) { return Application($x); });
        }
        return $this->approved;
    }

	public function getDeniedApplications() {
        if ($this->denied == NULL) {
            $this->denied = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'denied\'',
                array(':crn' => $this->crn),
                function ($x) { return Application($x); });
        }
        return $this->denied;
    }

	public function update() {	
		$this->applications = NULL;
		$this->pending = NULL;
		$this->approved = NULL;
		$this->denied = NULL;
	}

	public function addSession($session) {
		$this->getVoid('INSERT INTO course_sessions (crn,session_id) VALUES (:crn,:session)',
			array(':crn' => $this->crn, ':session' => $session->getID()));
	}

}

?>
