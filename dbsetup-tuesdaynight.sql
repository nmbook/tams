-- CSC296 Database Systems
-- Created: 15 Apr 2014

-- Remove tables in order to remove constraints correctly

DROP TABLE IF EXISTS teaches;
DROP TABLE IF EXISTS course_sessions;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS workshops;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS tas;
DROP TABLE IF EXISTS instructors;

-- Set up table Courses

CREATE TABLE courses (
    crn INTEGER NOT NULL PRIMARY KEY, -- natural key
    year YEAR NOT NULL,
    semester ENUM('spring','fall','summer','winter') NOT NULL,
    department CHAR(3) NOT NULL,
    course_number CHAR(4) NOT NULL,
    name VARCHAR(128) NOT NULL,
    description TEXT NULL,
    parent_crn INTEGER NULL,
	position_count SMALLINT UNSIGNED NOT NULL,
	
	FOREIGN KEY (parent_crn) REFERENCES courses(crn)
) ENGINE=InnoDB;

-- Set up table Sessions

CREATE TABLE sessions (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    weekday ENUM('U','M','T','W','R','F','S') NOT NULL,
    start_time TIME NOT NULL,
	end_time TIME NOT NULL,
    room VARCHAR(32) NOT NULL,
	crn INTEGER NOT NULL,
    UNIQUE (crn,weekday, start_time, end_time, room), -- natural key

	FOREIGN KEY (crn) REFERENCES courses(crn)
) ENGINE=InnoDB;

-- Set up table Person/Users (TA/Instructor/Staff/Admin)

CREATE TABLE tas (
    netid VARCHAR(8) NOT NULL PRIMARY KEY, -- natural key
    name VARCHAR(32) NOT NULL,
    email VARCHAR(256) NOT NULL,
    class_year SMALLINT(4) NULL,
    credentials VARCHAR(256) NULL
) ENGINE=InnoDB;

-- role ENUM('student','faculty','staff','admin') NOT NULL,
CREATE TABLE instructors (
    netid VARCHAR(8) NOT NULL PRIMARY KEY, -- natural key
    name VARCHAR(32) NOT NULL,
    email VARCHAR(256) NOT NULL,
    office_room VARCHAR(32) NULL,
    credentials VARCHAR(256) NULL
) ENGINE=InnoDB;

-- Set up table CourseApplications

CREATE TABLE applications (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn INTEGER NOT NULL,
    netid VARCHAR(8) NOT NULL,
    for_credit BOOLEAN NOT NULL,
    state ENUM('pending','approved','denied') NOT NULL,
    time_signup DATETIME NOT NULL,
    time_response DATETIME NULL,
	
	FOREIGN KEY (crn) REFERENCES courses (crn),
    FOREIGN KEY (netid) REFERENCES tas (netid),
    UNIQUE (crn, netid) -- natural key
) ENGINE=InnoDB;

-- Set up table Teaches
CREATE TABLE teaches (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn INTEGER NOT NULL,
    netid VARCHAR(8) NOT NULL,

    FOREIGN KEY (netid) REFERENCES instructors (netid),
    FOREIGN KEY (crn) REFERENCES courses (crn),
    UNIQUE (crn, netid) -- natural key
) ENGINE=InnoDB;
-- CHECK (user_id IN (SELECT id FROM users WHERE role = 'faculty')), -- only faculty can teach courses


ALTER TABLE courses
ADD CONSTRAINT CK_applications_c
CHECK ((SELECT COUNT(*) FROM applications a where a.crn=crn and a.state='approved') <= position_count);

ALTER TABLE applications
ADD CONSTRAINT CK_applications_a
CHECK ((SELECT COUNT(*) FROM applications a WHERE a.crn=crn and a.state='approved') <= (SELECT position_count FROM courses c WHERE c.crn=crn));


