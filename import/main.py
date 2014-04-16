__author__ = 'Deathnerd'

#strip the nulls from the json
import glob
import os
import json
import MySQLdb

# def removeNulls(filename):
# 	quiz = json.load(open(filename))
# 	# for question in quiz['quiz']['questions']:
# 	# 	question['choices'].remove(None)
# 	with open(filename, 'w') as outfile:
# 		# json.dump(quiz, outfile)
# 		outfile.write(unicode(json.dumps(quiz, indent=4, separators=(',', ':'))))

os.chdir('../quizzes')
jsonFiles = glob.glob("*.json")

# for name in jsonFiles:
# 	removeNulls(name)

#write to the db
db = MySQLdb.connect(host="localhost", user="root", passwd="root", db="EKULMS")
cursor = db.cursor()

for f in jsonFiles:
	quiz = json.load(open(f))
	#insert name of test into Tests table
	testName = os.path.splittext(f)[0]  #get just the filename and not the extension

	#check if the test name already exists in the database
	cursor.execute('SELECT * FROM "Tests" WHERE testName=' + testName)
	row = cursor.fetchall()
	if row.__len__() > 0:  #if it already exists in the database, skip
		print testName + " already exists. Skipping!"
		continue
	#add the test name to the Tests table
	cursor.execute("INSERT INTO \"Tests\" (courseId, testName) VALUES ('CSC185', " + testName + ")")
	#get the test id for the next step
	cursor.execute('SELECT testId FROM Tests WHERE testName=' + testName)
	row = cursor.fetchall()
	testId = row['testId']
#add the questions to the questions table

cursor.execute("SELECT * FROM Tests WHERE testName='Random Stuff'")
row = cursor.fetchall()
if row.__len__() > 0:
	print "This already exists"
else:
	cursor.execute("INSERT INTO Tests (courseId, testName) VALUES ('CSC185', 'Random Stuff')")