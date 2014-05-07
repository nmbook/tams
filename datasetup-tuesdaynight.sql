-- Fill with data

-- TRUNCATE tas;

LOAD DATA LOCAL INFILE 'data/tas.csv' INTO TABLE tas ( netid, name, email, class_year );
LOAD DATA LOCAL INFILE 'data/courses.csv' INTO TABLE courses (crn,year,semester,department,course_number,name,description,parent_crn,position_count);
LOAD DATA LOCAL INFILE 'data/instructors.csv' INTO TABLE instructors (netid,name,email,office_room,credentials);
LOAD DATA LOCAL INFILE 'data/sessions.csv' INTO TABLE sessions (weekday,start_time,end_time,room,crn);
LOAD DATA LOCAL INFILE 'data/applications.csv' INTO TABLE applications (crn,netid,for_credit,state,time_signup,time_response);
LOAD DATA LOCAL INFILE 'data/teaches.csv' INTO TABLE teaches (crn,netid);


