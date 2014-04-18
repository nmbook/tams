<?php
require_once 'dbsetup.php';

function getMapping($sql,$arr,$callback) {
        global $db;
        $stmt = $db->prepare($sql);
        $stmt->execute($arr);
        $stmt->setFetchMode(PDO::FETCH_NUM);
        return array_map($callback,$stmt);
}

class TA{
        private $netid;
        private $name;
        private $email;
        private $class_year;
        private $course_applications;
        private $workshop_applications;

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
        public function getClassYear() {return $this->class_year;}

        public function getCourseApplications() {
                if ($this->course_applications == NULL)
                        $this->course_applications = getMapping("SELECT * FROM CourseApplications WHERE
                                tanetid = :netid",
                                array(':netid' => $this->netid),
                                function ($x) { return CourseApplication($x); });
                return $this->course_applications;
        }

         public function getWorkshopApplications() {
                if ($this->workshop_applications == NULL)
                        $this->workshop_applications = getMapping("SELECT * FROM WorkshopApplications WHERE
                                tanetid = :netid",
                                array(':netid' => $this->netid),
                                function ($x) { return WorkshopApplication($x); });
                return $this->workshop_applications;
        }


        static public function getByNetID($netid) {
                global $db;
                $stmt = $db->prepare('SELECT netid,name,email,classYear FROM TAs WHERE netid=:netid;');
                $stmt->execute(array(':netid' => $netid));
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $row = $stmt->fetch();
                return TA($row);
        }

	static public function getByIDRange($id_start,$id_end) {
		return getMapping('SELECT netid,name,email,classYear FROM TAs WHERE :start<=id AND id<=:end',
			array(':start' => $id_start, ':end' => $id_end),
			function ($x) { return TA($x); });
	}

	static public function getTACount() {
		global $db;
		$stmt = $db->prepare('SELECT COUNT(*) FROM TAs;')
		$stmt->execute(array());
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		return $stmt->fetch();
	}

}
?>
