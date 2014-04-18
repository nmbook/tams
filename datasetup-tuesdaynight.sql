-- Fill with data

TRUNCATE tas;

LOAD DATA LOCAL INFILE '/home/nbook/tnd/tas.csv' INTO TABLE tas ( netid, name, email, class_year );

