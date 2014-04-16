-- CSC296 Database Systems
-- Created: 15 Apr 2014

-- Remove tables in order to remove constraints correctly

DROP TABLE IF EXISTS teaches;
DROP TABLE IF EXISTS workshop_sessions;
DROP TABLE IF EXISTS course_sessions;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS workshop_apps;
DROP TABLE IF EXISTS course_apps;
DROP TABLE IF EXISTS workshops;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

-- Set up table Courses

CREATE TABLE courses (
    crn SMALLINT NOT NULL PRIMARY KEY, -- natural key
    year SMALLINT(4) NOT NULL,
    semester ENUM('spring','fall','summer','winter') NOT NULL,
    department CHAR(3) NOT NULL,
    course_number CHAR(4) NOT NULL,
    name VARCHAR(28) NOT NULL,
    position_count SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;

-- Set up table Sessions

CREATE TABLE sessions (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    weekday ENUM('U','M','T','W','R','F','S') NOT NULL,
    time DATETIME NOT NULL,
    room VARCHAR(32) NOT NULL,

    UNIQUE (weekday, time, room) -- natural key
) ENGINE=InnoDB;

-- Set up table Workshops

CREATE TABLE workshops (
    crn SMALLINT NOT NULL PRIMARY KEY, -- natural key
    course_crn SMALLINT NOT NULL,
    position_count SMALLINT UNSIGNED NOT NULL,

    FOREIGN KEY (course_crn) REFERENCES courses (crn)
) ENGINE=InnoDB;

-- Set up table Person/Users (TA/Instructor/Staff/Admin)

CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    netid CHAR(8) NOT NULL UNIQUE, -- natural key
    name VARCHAR(32) NOT NULL,
    email VARCHAR(256) NOT NULL,
    role ENUM('student','faculty','staff','admin') NOT NULL,
    office_room VARCHAR(32) NULL,
    class_year SMALLINT(4) NULL,
    credentials VARCHAR(256) NULL
) ENGINE=InnoDB;

-- Set up table CourseApplications

CREATE TABLE course_apps (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn SMALLINT NOT NULL,
    netid CHAR(8) NOT NULL,
    for_credit BOOLEAN NOT NULL,
    state ENUM('pending','approved','denied') NOT NULL,
    time_signup DATETIME NOT NULL,
    time_response DATETIME NULL,

    FOREIGN KEY (crn) REFERENCES courses (crn),
    FOREIGN KEY (netid) REFERENCES users (netid),
    UNIQUE (crn, netid) -- natural key
) ENGINE=InnoDB;

-- Set up table WorkshopApplications

CREATE TABLE workshop_apps (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn SMALLINT NOT NULL,
    netid CHAR(8) NOT NULL,
    for_credit BOOLEAN NOT NULL,
    state ENUM('pending','approved','denied') NOT NULL,
    time_signup DATETIME NOT NULL,
    time_response DATETIME NULL,

    FOREIGN KEY (crn) REFERENCES workshops (crn),
    FOREIGN KEY (netid) REFERENCES users (netid),
    UNIQUE (crn, netid) -- natural key
) ENGINE=InnoDB;

-- Set up table CourseSessions

CREATE TABLE course_sessions (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn SMALLINT NOT NULL,
    session_id INTEGER NOT NULL,

    FOREIGN KEY (crn) REFERENCES courses (crn),
    FOREIGN KEY (session_id) REFERENCES sessions (id),
    UNIQUE (crn, session_id) -- natural key
) ENGINE=InnoDB;

-- Set up table WorkshopSessions

CREATE TABLE workshop_sessions (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn SMALLINT NOT NULL,
    session_id INTEGER NOT NULL,

    FOREIGN KEY (crn) REFERENCES workshops (crn),
    FOREIGN KEY (session_id) REFERENCES sessions (id),
    UNIQUE (crn, session_id) -- natural key
) ENGINE=InnoDB;

-- Set up table Teaches

CREATE TABLE teaches (
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    crn SMALLINT NOT NULL,
    user_id INTEGER NOT NULL
        CHECK (user_id IN (SELECT id FROM users WHERE role = 'faculty')), -- only faculty can teach courses

    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (crn) REFERENCES courses (crn),
    UNIQUE (crn, user_id) -- natural key
) ENGINE=InnoDB;

