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

$dbname = 'nbook';
require_once('../dbsetup.php');

class TA {
    private $netid;
    private $name;
    private $email;
    private $class_year;
    private $course_applications;
    private $workshop_applications;

    static private function getMapping($sql,$arr,$callback,$limit_start = null,$limit_len = null) {
        global $db;
        $stmt = $db->prepare($sql);
        if ($limit_start !== null && $limit_len !== null) {
            $stmt->bindParam(':start',intval($limit_start),PDO::PARAM_INT);
            $stmt->bindParam(':len',intval($limit_len),PDO::PARAM_INT);
        }
        if (!$stmt->execute()) {
            echo $sql;
            print_r($stmt->errorInfo());
            exit;
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return array_map($callback,$stmt->fetchAll());//$stmt->fetchAll());
    }

    public function __construct($row) {
        $this->netid = $row['netid'];
        $this->name = $row['name'];
        $this->class_year = $row['class_year'];
        if (array_key_exists('email',$row))
            $this->email = $row['email'];
        else
            $this->email = $row['name'] . "@u.rochester.edu";
        $this->course_applications = NULL;
        $this->workshop_applications = NULL;
    }

    public function getNetID() {return $this->netid;}
    public function getName()  {return $this->name;}
    public function getEmail() {return $this->email;}
    public function getClassYear() {return intval($this->class_year);}

    public function getCourseApplications() {
        if ($this->course_applications == NULL)
            $this->course_applications = TA::getMapping("SELECT * FROM CourseApplications WHERE
            tanetid = :netid",
            array(':netid' => $this->netid),
            function ($x) { return CourseApplication($x); });
        return $this->course_applications;
    }

    public function getWorkshopApplications() {
        if ($this->workshop_applications == NULL)
            $this->workshop_applications = TA::getMapping("SELECT * FROM WorkshopApplications WHERE
            tanetid = :netid",
            array(':netid' => $this->netid),
            function ($x) { return WorkshopApplication($x); });
        return $this->workshop_applications;
    }


    static public function getByNetID($netid) {
        global $db;
        $stmt = $db->prepare('SELECT netid,name,email,class_year FROM tas WHERE netid=:netid');
        $stmt->execute(array(':netid' => $netid));
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        return new TA($row);
    }

    static public function getByRange($start,$len) {
        return TA::getMapping('SELECT netid,name,email,class_year FROM tas LIMIT :start, :len',
            array(),
            function ($x) { return new TA($x); },
            $start,$len);
    }

    static public function getCount() {
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) FROM tas');
        if (!$stmt->execute(array())) {
            var_dump( $stmt->errorInfo());
            exit;
        }
        $stmt->setFetchMode(PDO::FETCH_NUM);
        $result = $stmt->fetch();
        return $result[0] + 0;
    }
}

