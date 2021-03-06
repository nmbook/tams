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
	private $password;
    private $class_year;
    private $applications;

    public function __construct($row) {
        $this->netid = $row['netid'];
        $this->name = $row['name'];
		$this->password = $row['credentials'];
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
	public function getPassword() {return $this->password;}
    public function getClassYear() {return intval($this->class_year);}

    public function getApplication($crn) {
        if ($this->applications == NULL) {
            $tmp = Utils::getMapping(
                'SELECT * FROM applications
                 WHERE netid = :netid',
            array(':netid' => $this->netid),
            function ($x) { return new Application($x); });
			$this->applications = array();
			foreach ($tmp as $app) {
				$this->applications[$app->getCrn()] = $app;	
			}
        }
        return $this->applications[$crn];
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

    public static function create($netid, $name, $email, $password, $class_year) {
        Utils::getVoid(
            'INSERT INTO tas
             (netid,name,email,credentials,class_year)
             VALUES
             (:netid,:name,:email,:password,:year)',
			array('netid' => $netid,
			':name' => $name,
			':email' => $email,
			':password' => $password,
			':year' => $class_year));
    }

    static public function getByNetID($netid) {
        return Utils::getSingle(
            'SELECT * FROM tas
             WHERE netid=:netid',
        	array(':netid' => $netid),
        	function ($x) { return new TA($x); });
    }

    static public function getByCredentials($netid, $password) {
        return Utils::getSingle(
            'SELECT * FROM tas
            WHERE netid=:netid
            AND credentials=:password',
            array(':netid' => $netid,':password' => $password),
            function ($x) { return new TA($x); });
    }

    static public function getByRange($start,$len) {
        return Utils::getMapping(
            'SELECT * FROM tas
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
            'SELECT t.netid,t.credentials,t.name,email,class_year FROM courses c
             INNER JOIN applications a ON c.crn = a.crn
             INNER JOIN tas t ON t.netid = a.netid
             WHERE c.year = :year AND c.semester = :semester
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

