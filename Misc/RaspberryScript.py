import serial
import time
import json
import mysql.connector
from time import sleep
hwid = 2
ser = serial.Serial('/dev/ttyACM0',9600, timeout=1)
ser.flush()
#--------------------------#
#----Gestion Base Mysql----#
#--------------------------#
DB_SERVER =''
DB_USER=''
DB_PWD=''
DB_BASE=''

def query_db(sql):
    db = mysql.connector.connect(
        host = DB_SERVER,
        user = DB_USER,
        password = DB_PWD,
        database = DB_BASE)
    cursor = db.cursor()
    cursor.execute(sql)
    result = cursor.fetchall()
    db.commit()
    db.close()
    return result
def query_db_noResult(sql):
    db = mysql.connector.connect(
        host = DB_SERVER,
        user = DB_USER,
        password = DB_PWD,
        database = DB_BASE)
    cursor = db.cursor()
    cursor.execute(sql)
    db.commit()
    db.close()

def valuesGet():
    datebuff = time.strftime('%Y-%m-%d %H:%M:%S')
    line = ser.readline().decode('utf-8').rstrip()
    line_dict = json.loads(line)
    query = """INSERT INTO results (datetag,hwid, ph, temp, lum) VALUES ('{0}',{1},{2},{3}, {4});""".format(datebuff, hwid,line_dict['ph'], line_dict['temp'], line_dict['lum'])
    query_db_noResult(query)

def getChanges():
    data = query_db('SELECT * FROM changeValues;')
    for r in data:
        temp = 0
        lum = 0
        send = ''
        if(r[1] == hwid):
            if(r[2]!=0):
                send += "temp:%s;"%(r[2])
            if(r[3]!=0):
                send += "lum:%s"%(r[3])
        query_db_noResult("DELETE FROM `changeValues` WHERE `changeValues`.`id` = %s "%(r[0]))
        print("J'ai bien recu une demande de changement !")

try:
 ok = True
 while ok:
    if ser.in_waiting>0:
        valuesGet()
    getChanges()
    sleep(5)
except KeyboardInterrupt:
    print("adios.")
    exit()