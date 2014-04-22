-- Fill with data

-- TRUNCATE tas;

LOAD DATA LOCAL INFILE 'tas.csv' INTO TABLE tas ( netid, name, email, class_year );

