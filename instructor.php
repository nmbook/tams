<?php
/**
 * This is the DAL for the Instructor class.
 *
 * Currently functional (and tested) parts:
 * string $ins->getNetID()
 * string $ins->getName()
 * string $ins->getEmail()
 * int $ins->getOffice()
 */

require_once('../dbsetup.php');
require_once('utils.php');
class Instructor
{
    private $netid;
    private $name;
    private $email;
	private $password;
    private $office;
    private $classes;

    public function __construct($row) {
        $this->netid = $row['netid'];
        $this->name = $row['name'];
		$this->password = $row['credentials'];
        $this->office = $row['office_room'];
        if (array_key_exists('email',$row))
            $this->email = $row['email'];
        else
            $this->email = $row['name'] . "@u.rochester.edu";
        $this->classes = NULL;
    }

    public function getNetID() {return $this->netid;}
    public function getName()  {return $this->name;}
	public function getPassword() {return $this->password;}
    public function getEmail() {return $this->email;}
    public function getOffice() {return $this->office;}

    public function getClasses() {
        if ($this->classes == NULL) {
            $this->classes = Instructor::getMapping("SELECT * FROM teaches INNER JOIN courses
            ON teaches.course = courses.id
            WHERE
            teaches.instructor = :netid",
            array(':netid' => $this->netid),
            function ($x) { return new Course($x); });
        }
        return $this->classes;
    }

	public function update() {
		$this->classes = NULL;	
	}

    static public function getByNetID($netid) {
        return Utils::getSingle(
            'SELECT * FROM instructors
             WHERE netid=:netid',
            array(':netid' => $netid),
            function ($x) { return new Instructor($x); });
    }

	static public function getByCredentials($netid,$password) {
		 return Utils::getSingle(
            'SELECT * FROM instructors
             WHERE netid=:netid
			AND credentials=:password',
            array(':netid' => $netid,':password' => $password),
            function ($x) { return new Instructor($x); });
	}

	public function assignCourse($crn) {
		Utils::getVoid('INSERT teaches (netid,crn) VALUES (:netid,:crn)',
			array(':netid' => $this-> netid,
			':crn' => $crn));
		$this->update();
	}

    static public function getCount() {
        $count = Utils::getSingle('SELECT COUNT(*) FROM instructors',array(),null,true);
        return intval($count);
    }

	static public function import($arr) {
		foreach ($arr as $row) {
			$row['credentials'] = Utils::passwordCreate($row['credentials']);
			Utils::getVoid('INSERT INTO instructors (netid,name,email,credentials,office_room) VALUES 
				(:netid,:name,:email,:credentials,:office_room)',
				Utils::prepareArray($row));
		}
	}

	static public function importClasses($arr) {
		foreach ($arr as $row) {
			Utils::getVoid('INSERT INTO teaches (crn,netid) VALUES (:crn,:netid)',
				Utils::prepareArray($row));
		}
	}	

	//Fixme
	static public function getByCoursesNetid($netid) {
		return Utils::getMapping('SELECT c.name, weekday, time, room
				FROM instructors i
				INNER JOIN teaches t
				ON t.net_id = i.net_id
				INNER JOIN courses c
				ON c.crn = t.crn
				INNER JOIN course_sessions cs
				ON c.crn = cs.crn
				INNER JOIN sessions s
				ON cs.session_id = s.id
				WHERE i.netid = :netid
				 AND c.semester = \'spring\' AND c.year = 2014',
	
            array(':netid' => $netid),
            function ($x) { return new Instructor ($x); });
    }
}

