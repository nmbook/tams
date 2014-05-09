from json import loads
from random import sample, choice, randint
from subprocess import Popen,PIPE
from os import environ
APP_LIMIT = 4
site_string = 'http://localhost/~' + environ['USER'] + '/'

def submit(f,net,password,**kwargs):
	data = '&'.join([key+'='+val for (key,val) in kwargs.iteritems()])
	obj = Popen(['curl', '-s', '-b', 'netid=' + net + '; password=' + password, '--data',  data, site_string + f],stdout=PIPE)
	obj.wait()
	for line in obj.stdout:
		#print line
		if "ERROR" in line:
			print line[3:-5]

with open('data/courses.json','r') as f:
	crns = [loads(line)['crn'] for line in f]
with open('data/tas.csv','r') as f:
	netids = {line.split('\t')[0]:line.split('\t')[-1][:-1] for line in f}
with open('data/instructors.json','r') as f:
	instructors = [loads(line)['netid'] for line in f]

apps = {crn:[] for crn in crns}
for (netid,password) in netids.iteritems():
	for crn in sample(crns,randint(0,APP_LIMIT)):
		apps[crn].append(netid)
		for_credit = choice(['true','false'])
		submit('apply-course.php',netid,password,crn=str(crn),for_credit=for_credit)

print 'Finished TA applications. Awaiting Instructor approvals...'
for crn in crns:
	for netid in apps[crn]:
		state = choice(['pending','approved','denied'])
		submit('ta-approval.php','marty','password',netid=netid,crn=str(crn),state=state)

