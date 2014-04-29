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
		$this->positions = $row['position_count'];
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
				function ($x) { return new Instructor($x); });
		}
		return $this->instructors;
	}

	public function getAllApplications() {
		if ($this->applications == NULL) {
			$this->applications = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn',
				array(':crn' => $this->crn),
				function ($x) { return new Application($x); });
		}
		return $this->applications;
	}

	public function getPendingApplications() {
        if ($this->pending == NULL) {
            $this->pending = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'pending\'',
                array(':crn' => $this->crn),
                function ($x) { return new Application($x); });
        }
        return $this->pending;
    }

	public function getApprovedApplications() {
        if ($this->approved == NULL) {
            $this->approved = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'approved\'',
                array(':crn' => $this->crn),
                function ($x) { return new Application($x); });
        }
        return $this->approved;
    }

	public function getDeniedApplications() {
        if ($this->denied == NULL) {
            $this->denied = Utils::getMapping('SELECT * FROM applications WHERE crn=:crn AND state=\'denied\'',
                array(':crn' => $this->crn),
                function ($x) { return new Application($x); });
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
		Utils::getVoid('INSERT INTO course_sessions (crn,session_id) VALUES (:crn,:session)',
			array(':crn' => $this->crn, ':session' => $session->getID()));
	}

	static public function getCourseByCrn($crn) {
		return Utils::getSingle('SELECT * FROM courses WHERE crn=:crn',
			array(':crn' => $crn),
			function ($x) { return new Course($x);});
	}

	static public function getCourseByName($dept,$number,$year,$semester) {
		return Utils::getSingle(
			'SELECT * FROM  courses WHERE department=:dept AND course_number=:number AND year=:year AND semester=:semester',
			array(':dept' => $dept, ':number' => $number, ':year' => $year, 'semester' => $semester),
			function ($x) { return new Course($x); });
	}

	static public function import($arr) {
		foreach ($arr as $row) {
			$row2 = array();
			foreach ($row as $key => $val) {
				$row2[':' . $key] = $val;
			}
			Utils::getVoid('INSERT INTO courses (crn,year,semester,department,course_number,name)
				VALUES (:crn,:year,:semester,:department,:course_number,:name)',
				$row2);
		}
	}

	static public function getCoursesByNetid($netid, $year) {
        return Utils::getMapping('SELECT c.crn, c.name, weekday, start_time, room
                FROM instructors i
                INNER JOIN teaches t
                ON t.netid = i.netid
                INNER JOIN courses c
                ON c.crn = t.crn
                INNER JOIN sessions s
                ON c.crn = s.crn
                WHERE i.netid = :netid
                AND c.year = :year',

            array(':netid' => $netid,
                    ':year' => $year),
            function ($x) { return $x; });
    }


}

?>
