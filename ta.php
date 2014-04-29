<?php

/**
 * This is the DAL for the TA class.
 *
 * Currently functional (and tested) parts:
 * int TA::getCount() -- gets the total number of rows in the data
 * array TA::getByRange() -- gets an array of objects, one TA object per row.
 * string $ta->getNetID()
 * string $ta->getName()
 * string $ta->getEmail()
 * int $ta->getClassYear()
 */

require_once('../dbsetup.php');
require_once('application.php');
require_once('utils.php');

class TA {
    private $netid;
    private $name;
    private $email;
    private $class_year;
    private $applications;

    public function __construct($row) {
        $this->netid = $row['netid'];
        $this->name = $row['name'];
        $this->class_year = $row['class_year'];
        if (array_key_exists('email',$row))
            $this->email = $row['email'];
        else
            $this->email = $row['netid'] . "@u.rochester.edu";
        $this->applications = NULL;
    }

    public function getNetID() {return $this->netid;}
    public function getName()  {return $this->name;}
    public function getEmail() {return $this->email;}
    public function getClassYear() {return intval($this->class_year);}

    public function getApplications() {
        if ($this->applications == NULL) {
            $this->applications = Utils::getMapping(
                'SELECT * FROM applications
                 WHERE netid = :netid',
            array(':netid' => $this->netid),
            function ($x) { return new Application($x); });
        }
        return $this->applications;
    }

	public function update() {
		$this->applications = NULL;
    }

	public function applyCourse($crn,$forCredit) {
        Utils::getVoid(
            "INSERT INTO applications
             (crn,netid,time_signup,time_response,state,for_credit)
             VALUES
             (:crn,:netid,NOW(),NULL,'pending',:credit)",
			array(':crn' => $crn,
			':netid' => $this->netid,
			':credit' => $forCredit));
        $this->update();
	}

    public static function create($netid, $name, $email, $class_year) {
        Utils::getVoid(
            'INSERT INTO tas
             (netid,name,email,class_year)
             VALUES
             (:netid,:name,:email,:year)',
			array('netid' => $netid,
			':name' => $name,
			':email' => $email,
			':year' => $class_year));
    }

    static public function getByNetID($netid) {
        return Utils::getSingle(
            'SELECT netid,name,email,class_year FROM tas
             WHERE netid=:netid',
        	array(':netid' => $netid),
        	function ($x) { return new TA($x); });
    }

    static public function getByRange($start,$len) {
        return Utils::getMapping(
            'SELECT netid,name,email,class_year FROM tas
             LIMIT :start, :len',
            array(),
            function ($x) { return new TA($x); },
            $start,$len);
    }

    static public function getCount() {
        $count = Utils::getSingle('SELECT COUNT(*) FROM tas',array(),null,true);
        return intval($count);
    }

	static public function getByClass($year, $semester, $department, $course_number) {
        return Utils::getMapping(
            'SELECT t.netid,t.name,email,class_year FROM courses c
             INNER JOIN applications a ON c.crn = a.crn
             INNER JOIN tas t ON t.netid = a.netid
             WHERE a.state = \'approved\'
             AND c.year = :year AND c.semester = :semester
             AND c.department = :department
             AND course_number = :course',
             array(
                 ':year' => $year,
                 ':semester' => $semester,
                 ':department' => $department,
                 ':course' => $course_number),
            function ($x) { return new TA($x); });
    }
}

