from datetime import datetime, timedelta
import random
import mysql.connector
sdate = datetime(2021, 5, 16, 00, 0, 00)
edate = datetime(2021, 5, 20, 1 , 0 , 00) 
tempint = 20.00
phint = 7
lumint = 50
delta = edate - sdate     
empty="TRUNCATE TABLE `results`;"
DB_SERVER ='' 
DB_USER=''     
DB_PWD=''          
DB_BASE=''  
def reset_file():
     sql_file = open('random.sql','w')
     #sql_file.write(" ")
     sql_file.close
def temp(): 
    way = random.randint(0, 1)
    if way:
        newtemp = tempint + random.uniform(0, 0.4)
    else:
        newtemp = tempint - random.uniform(0, 0.4)
    return round(newtemp, 1)
def lum():
    way = random.randint(0, 1)
    if way:
        newlum = lumint + random.uniform(0, 5)
    else:
        newlum = lumint - random.uniform(0, 5)
    return round(newlum, 1)

def ph():
    way = random.randint(0, 1)
    if way:
        newph = phint + random.uniform(0, 0.2)
    else:
        newph = phint - random.uniform(0, 0.2)
    return round(newph, 1)
def write(query):
    sql_file = open('random.sql','a')
    sql_file.write(query)
def daterange(start_date, end_date):
    delta = timedelta(minutes=1)
    while start_date < end_date:
        yield start_date
        start_date += delta
def generate():
    reset_file()
    empty_base()
    for single_date in daterange(sdate, edate):
        timestamp =single_date.strftime('%Y-%m-%d %H:%M:%S')
        query = """INSERT INTO results (datetag,hwid, ph,temp,lum) VALUES ('%s',1,'%s','%s', '%s');
                """ % (timestamp,ph(), temp() ,lum())
        write(query)
def query_db(sql):
    try:
        db = mysql.connector.connect(
            host = DB_SERVER, 
            user = DB_USER, 
            password = DB_PWD, 
            database = DB_BASE) #Connexion
        cursor = db.cursor() #Curseur
        cursor.execute(sql) #On envoie la requete et on ferme
        db.commit()
        db.close()
    except:
        print("SQL query error")
def empty_base():
    try:
        db = mysql.connector.connect(
            host = DB_SERVER, 
            user = DB_USER, 
            password = DB_PWD, 
            database = DB_BASE) #Connexion
        cursor = db.cursor() #Curseur
        cursor.execute(empty) #On envoie la requete et on ferme
        db.commit()
        db.close()
    except:
        print("SQL table reset error")

generate()