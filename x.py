from json import loads,dumps
with open('data/instructors.json','r') as f:
	for line in f:
		obj = loads(line)
		obj['credentials'] = obj['password']
		del obj['password']
		print dumps(obj)
