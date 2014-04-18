<?php


require_once 'dbsetup.php';



function getMapping($sql,$arr,$callback) {
        global $db;
        $stmt = $db->prepare($sql);
        $stmt->execute($arr);
        $stmt->setFetchMode(PDO::FETCH_NUM);
        return array_map($callback,$stmt);
}

class Instructor
{
        private $netid;
        private $name;
        private $email;
        private $office;
        private $classes;

        public function __construct($row) {
                $this->netid = $row['netid'];
                $this->name = $row['name'];
                $this->office = $row['office'];
                if (array_key_exists('email',$row))
                        $this->email = $row['email'];
                else
                        $this->email = $row['name'] . "@u.rochester.edu";
                $this->classes = NULL;
        }

        public function getNetID() {return $this->netid;}
        public function getName()  {return $this->name;}
        public function getEmail() {return $this->email;}
        public function getOffice() {return $this->office;}

        public function getClasses() {
                if ($this->classes == NULL)
                        $this->classes = getMapping("SELECT * FROM Teaches INNER JOIN Courses
                        ON Teaches.course = Courses.id
                        WHERE
                        Teaches.instructor = :netid",
                        array(':netid' => $this->netid),
                        function ($x) { return Course($x); });
                return $this->classes;
        }

        static public function getByNetID($netid) {
                global $db;
                $stmt = $db->prepare('SELECT * FROM Instructors WHERE netid=:netid;');
                $stmt->execute(array(':netid' => $netid));
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                return Instructor($stmt->fetch());
        }

}
?>

