from json import loads
from random import sample, choice, randint
from subprocess import Popen,PIPE
from os import environ
APP_LIMIT = 4
site_string = 'http://localhost/~' + environ['USER'] + '/'

def submit(f,**kwargs):
	data = '&'.join([key+'='+val for (key,val) in kwargs.iteritems()])
	obj = Popen(['curl', '-s', '--data',  data, site_string + f],stdout=PIPE)
	obj.wait()
	for line in obj.stdout:
		if "ERROR" in line:
			print line[3:-5]

crns = []
with open('data/courses.json','r') as f:
	crns = [loads(line)['crn'] for line in f]
netids = []
with open('data/tas.csv','r') as f:
	netids = [line.split('\t')[0] for line in f]
instructors = []
with open('data/instructors.json','r') as f:
	instructors = [loads(line)['netid'] for line in f]
apps = {crn:[] for crn in crns}
for netid in netids:
	for crn in sample(crns,randint(0,APP_LIMIT)):
		apps[crn].append(netid)
		for_credit = choice(['true','false'])
		submit('apply-course.php',netid=netid,crn=str(crn),for_credit=for_credit)

print 'Finished TA applications. Awaiting Instructor approvals...'
for crn in crns:
	for netid in apps[crn]:
		state = choice(['pending','approved','denied'])
		submit('ta-approval.php',netid=netid,crn=str(crn),state=state)

