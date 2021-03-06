-- SQL for Example Queries
-- CASE 1
-- #1: List the positions with times, room, and total positions for a specified course:
SELECT weekday, start_time, room, position_count 
FROM courses c INNER JOIN sessions cs 
ON c.crn = cs.crn
WHERE c.year = 2014 AND c.semester = 'spring' AND c.department = 'CSC' AND course_number = 171;

SELECT weekday, start_time, room, w.position_count
FROM courses c
INNER JOIN courses w
ON w.crn = c.parent_crn
INNER JOIN sessions ws
ON ws.crn = w.crn
WHERE c.year = 2014 AND c.semester = 'fall' AND c.department = 'CSC' AND c.course_number = 171;

-- #2: List the students wanting to sign up to TA a specific professor’s class(es):
select t.name, c.department, c.course_number, c.name  
from courses c INNER JOIN applications ap 
ON c.crn = ap.crn INNER JOIN tas t 
ON t.netid = ap.netid INNER JOIN teaches te 
ON te.crn = c.crn INNER JOIN instructors i 
ON i.netid = te.netid 
WHERE ap.state = 'pending' AND i.netid = 'boondoggle' AND c.year = 2014 AND c.semester = 'fall';



-- CASE 2
-- #1:  
SELECT c.name
FROM courses c
INNER JOIN applications ca
ON c.crn = ca.crn
INNER JOIN tas t
ON t.netid = ca.netid
WHERE ca.state = "approved" AND c.year = 2014 AND c.semester = 'fall' AND c.department = 'CSC' AND course_number = 173;



-- #2:
SELECT t.name 
FROM courses c 
INNER JOIN courses w 
ON w.parent_crn = c.crn 
INNER JOIN applications wa 
ON w.crn = wa.crn 
Inner join tas t 
ON t.netid = wa.netid 
WHERE wa.state = "approved" AND c.year = 2015 AND c.semester = 'fall' AND c.department = 'CSC' AND c.course_number = 173;


-- CASE 3
-- #1: 
SELECT t.email
FROM courses c
INNER JOIN applications ca
ON ca.crn = c.crn
INNER JOIN tas t
ON t.netid = ca.netid
WHERE ca.state = "approved" AND c.year = 2014 AND c.semester = 'fall' AND c.department = 'CSC' AND course_number = 173;

SELECT t.email
FROM courses c
INNER JOIN courses w
ON w.crn = c.crn
INNER JOIN applications wa
ON w.crn = wa.crn
INNER JOIN tas t
ON t.netid = wa.netid
WHERE wa.state = "approved" AND c.year = 2015 AND c.semester = 'fall' AND c.department = 'CSC' AND c.course_number = 173;



-- CASE 4
-- #1: 
SELECT c.name, weekday, start_time, room
FROM instructors i
INNER JOIN teaches t
ON t.netid = i.netid
INNER JOIN courses c
ON c.crn = t.crn
INNER JOIN sessions s
ON c.crn = s.crn
WHERE i.netid = "boondoggle" AND c.semester = "spring" AND c.year = 2014;

--#2:
SELECT weekday, start_time, room
FROM courses c
INNER JOIN sessions cs
ON c.crn = cs.crn
WHERE c.year = 2014 AND c.semester = 'spring' AND c.department = 'CSC' AND course_number = 240;


--  
--                This will be testqueriesout-tuesdaynight.txt:
--                case 1:
--                    SQL
--                    case 1:
--                        #1:
--                        Select *
--                        From (SELECT wkday, time, room, numpositions from R1, CourseSessions 
--                            UNION 
--                            SELECT wkday, time, room, numpositions from R1, Workshops, WorkshopSession )
--                        Where (where = 2014 AND semester = "Fall" AND depId = "CSC" AND courseName = "171");
--  
--                        This query will return the time, weekday and the room that a workshop and lab is offered. It will also show the number of availabilities for TAs/Workshop leaders for that course.
--  
--                        #2:
--                        Select T.name, C.deptId, C.number, C.name
--                        From CourseApplications C, TAs T, 
--                        (Select * From Courses C1, Teaches, Instructors I1 
--                            WHERE I1.netid = "boondoggle" AND C1.year = 2014 AND C1.semester = "fall")
--                        Where C.state = pending
--  
--                        This query will result with a list of names (First and Last name) with the students who have applied to be TAs for a certain professors classes. These students will not have been selected or rejected yet, but have a “pending” state. They are also possibly signed up for different classes as a professor teaches more than one course. A student could then appear more than once on the result as a student can apply for both of the two different courses that the professor is teaching. 
--  
--                        case 2:
--                            #1: 
--                            select T.name
--                            from TAs T, CourseApplications A, Courses C
--                            where A.state = "approved"
--                            AND C.year = 2014
--                            AND C.semester = "fall"
--                            AND C.depId = "CSC"
--                            AND C.courseNumber = 173;
--  
--                            The first query for case 2 will return the names (First and Last name) for the TAs that have been approved for the course that is being queried for, during a specific semester and year. These TAs do not have a lab or a workshop, they are simply grader TAs It will just be on column with the names in them.
--  
--                            #2: 
--                            SELECT T.name, 
--                            FROM TAs T, WorkshopApplications A, Workshops, WorkshopApplications, Courses
--                            WHERE A.state = "approved"
--                            AND year = 2015
--                            AND semester = "spring"
--                            AND depId = "CSC"
--                            AND courseNumber = 172;
--                            the second query will return the names(First and Last name) for the Workshop leaders and Lab TAs for the course this time. These TAs and Workshop leaders have a lab or workshop associated with them. It will again just be a simple column with the names of the students.
--  
--                            case 3:
--                                SELECT T.name
--                                FROM (SELECT * FROM Workshops, WorkshopApplications, 
--                                    (SELECT * FROM Courses WHERE year = 2014 AND semester = "fall" AND courseNumber = 173 AND depId = "CSC"))
--                                UNION
--                                (SELECT * FROM CourseApplications, 
--                                    (SELECT * FROM Courses WHERE year = 2014 AND semester = "fall" AND courseNumber = 173 AND depId = "CSC"))
--                                WHERE state = "Approved"
--                                This query will result with a column with all the emails of the approved TAs and the workshop leaders for a certain course for a certain semester.
--  
--                                case 4:
--                                    #1:
--                                    SELECT C.name, S.wkday, S.time, S.room
--                                    FROM Teaches, Courses C, Sessions, Instructors A
--                                    WHERE being one of columns.  
--                                    #2:
--                                    SELECT S.wkday, S.time, S.room
--                                    FROM Courses C, Session S
--                                    WHERE C.depId = "CSC"
--                                    AND C.number = 240
--                                    AND C.year = 2014
--                                    AND C.semester = "spring"
--                                    This query will return the room, time and day of the week that a course is being offered in a specific semester. Each one of the attributes will again be in a separate column. 
 

