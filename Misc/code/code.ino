#include <Adafruit_GFX.h>
#include <SPI.h>
#include <Wire.h>
#include <Adafruit_ILI9341.h>
#include <Adafruit_STMPE610.h>
#include <RTClib.h>
#include <TouchScreen.h>
#include <DHT.h>
//Ecran
#define DHTPIN 3
#define DHTTYPE DHT22
#define TS_MINX 150
#define TS_MINY 130
#define TS_MAXX 3800
#define TS_MAXY 4000
#define STMPE_CS 8  
#define TFT_CS 10
#define TFT_DC 9
// DHT 
#define DHTPIN 2 
#define DHTTYPE DHT22
// PH
#define phSensorPin A1           
#define Offset 0.07             
#define samplingInterval 20     
#define printInterval 800       
#define ArrayLenth  40    
#define LIGHT_SENSOR A0      
int pHArray[ArrayLenth];        
int pHArrayIndex=0; 

const long interval = 5000;  
unsigned long previousMillis = 0;

Adafruit_STMPE610 ts = Adafruit_STMPE610(STMPE_CS);
Adafruit_ILI9341 tft = Adafruit_ILI9341(TFT_CS, TFT_DC);
DHT dht( DHTPIN ,  DHTTYPE );
int x,y;
float temp = 20.2;

float getTemp(){
    return dht.readTemperature();
}
int getLum(){
    return analogRead(LIGHT_SENSOR);
}


float getPh(){
  static float pHValue,voltage;
  voltage = analogRead(phSensorPin)*5.0/1024;   
  pHValue = 3.5*voltage+Offset;                                                       
  return pHValue;               
        
}


void setup(){
  Serial.begin(9600);
  tft.begin();
  dht.begin();
  if (!ts.begin()){ return;} 
  tft.fillScreen(ILI9341_RED);
  tft.setTextSize(2);
  tft.setTextWrap(0);
  tft.setTextColor(ILI9341_BLACK);
  bouton();
  RTC_DS1307 RTC;
   dht.begin();
}
void loop(){
    unsigned long currentMillis = millis();
   
    if (!ts.bufferEmpty()){
        TS_Point p = ts.getPoint();
        x = map(p.x, TS_MINX, TS_MAXX, 0, tft.width());
        y = map(p.y, TS_MINY, TS_MAXY, 0, tft.height());
        if ((x > 40) && (x < 160) && (y > 20) && (y < 40)){
            if (ts.touched()){
              dateHeure();
              addBackButton();
            }
        }
        else if ((x > 40) && (x < 160) && (y > 60) && (y < 100)){
            if (ts.touched())
            {
                temperature();
                addBackButton();
            }
        }
        else if ((x > 40) && (x < 160) && (y > 120) && (y < 160)){
            if (ts.touched())
            {
                ph();
                addBackButton();
            }
        }
        else if ((x > 40) && (x < 160) && (y > 180) && (y < 220)){
            if (ts.touched())
            {
                luminosite();
                addBackButton();
            }
        }
        else if ((x > 40) && (x < 160) && (y > 240) && (y < 280)){
            if (ts.touched()){
                controle_lum();
                printBackButton();
                waitControlMenu();  
           }
      }
    }
     if (currentMillis - previousMillis >= interval) {
        previousMillis = currentMillis;

        Serial.println("{\"temp\":" + String(getTemp())+ ",\"lum\":"+ String(getLum())+ ", \"ph\":" + String(getPh()) + "}");
    }
}

void bouton(){
  tft.fillScreen(ILI9341_BLUE); 
  tft.setTextColor(ILI9341_BLACK); 
  tft.fillRect(40, 20, 160, 40, ILI9341_WHITE);
  tft.setCursor(60, 30);
  tft.print("Date/Heure");
  tft.fillRect(40, 80, 160, 40, ILI9341_WHITE);
  tft.setCursor(60, 90);
  tft.print("Temperature");
  tft.fillRect(40, 140, 160, 40, ILI9341_WHITE);
  tft.setCursor(60, 150);
  tft.print("Ph/Qualite");
  tft.fillRect(40, 200, 160, 40, ILI9341_WHITE);
  tft.setCursor(60, 210);
  tft.print("Luminosite");
  tft.fillRect(40,260, 160, 40, ILI9341_WHITE);
  tft.setCursor(60, 270);
  tft.print("Controle");
}
void dateHeure(){
    tft.fillScreen(ILI9341_BLACK);
    tft.setTextColor(ILI9341_RED);
    tft.setCursor(50, 50);
    tft.print("La date est  ");
}
void temperature(){
    tft.fillScreen(ILI9341_BLACK);
    tft.setTextColor(ILI9341_RED);
    tft.setCursor(50, 50);
    tft.print("Temperature: ");
    tft.setCursor(50, 170);
    tft.setTextSize(5);
    tft.print(String(getTemp()));
    tft.setTextSize(2);
    tft.print(String((char)223) + "C");
}
void ph(){
    tft.fillScreen(ILI9341_BLACK);
    tft.setTextColor(ILI9341_RED);
    tft.setCursor(70, 50);
    tft.print("Ph : ");
    tft.setCursor(80, 160);
    tft.setTextSize(5);
    tft.print(getPh());    
    tft.setTextSize(2);
}
void luminosite(){
    tft.fillScreen(ILI9341_BLACK);
    tft.setTextColor(ILI9341_RED);
    tft.setCursor(50, 50);
    tft.print("Luminosite : ");
    tft.setCursor(50, 170);
    tft.setTextSize(5);
    tft.print(String(getLum()));
    tft.setCursor(150, 190);
    tft.setTextSize(2);
    tft.print("Lumens");
}
void addBackButton(){
    tft.fillRect(40,260, 160, 40, ILI9341_WHITE);
    tft.setCursor(60, 270);
    tft.print("BACK");
    bool pressed = false;
    while(!pressed){
      if (!ts.bufferEmpty()){
      TS_Point p = ts.getPoint();
      x = map(p.x, TS_MINX, TS_MAXX, 0, tft.width());
      y = map(p.y, TS_MINY, TS_MAXY, 0, tft.height());
      if ((x > 40) && (x < 160) && (y > 240) && (y < 280)){
            if (ts.touched())
            {
                pressed = true;
                bouton();
                return;
            }
        }
      }
  }  
}
void printBackButton(){
    tft.fillRect(40,260, 160, 40, ILI9341_WHITE);
    tft.setCursor(60, 270);
    tft.print("BACK");
}
void controle_lum(){
    tft.fillScreen(ILI9341_BLACK);
    tft.setTextColor(ILI9341_RED);
    tft.setCursor(30, 10);
    tft.print("Controle de lum");
    
    tft.setCursor(30, 100);
    tft.print("Controle de temp");
   
    tft.fillRect(40,50, 60, 40, ILI9341_WHITE);
    tft.setCursor(65, 60);
    tft.print("+");

    tft.fillRect(150,50, 60, 40, ILI9341_WHITE);
    tft.setCursor(175, 60);
    tft.print("-");

    tft.fillRect(40,130, 60, 40, ILI9341_WHITE);
    tft.setCursor(65, 140);
    tft.print("+");

    tft.fillRect(150,130, 60, 40, ILI9341_WHITE);
    tft.setCursor(175, 140);
    tft.print("-");
    
}
void waitControlMenu(){
  bool exitMenu = false;
  while(!exitMenu){
    if (!ts.bufferEmpty()){
      TS_Point p = ts.getPoint();
      x = map(p.x, TS_MINX, TS_MAXX, 0, tft.width());
      y = map(p.y, TS_MINY, TS_MAXY, 0, tft.height());
      if ((x > 40) && (x < 80) && (y > 50) && (y < 90)){
          if (ts.touched()){
              
              exitMenu = false;
          }
      }
      if ((x > 140) && (x < 180) && (y > 50) && (y < 90)){
          if (ts.touched()){
              
              exitMenu = false;
          }
      }
      if ((x > 40) && (x < 160) && (y > 240) && (y < 280)){
            if (ts.touched())
            {
                exitMenu = false;
                bouton();
                return;
            }
        }
    }
  }
}
  
