#Light Control

Modul für Symcon ab Version 6.3.

Steuert einen Lichtpunkt nach vorgegebenen Einstellungen.

## Dokumentation

**Inhaltsverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguration)  
6. [Statusvariablen und Profile](#6-statusvariablen-und-profile)  
7. [Anhang](#7-anhang)
    1. [GUIDs der Module](#guids-der-module)
    2. [Spenden](#spenden)

## 1. Funktionsumfang

- 1 Dalitreiber wird geschaltet
- 1-4 auslösende PM auf Hauptprogramm
- 1-4 auslösende PM auf Nebenprogram
- Zeitschaltung von Nacht auf Morgen.
- Zeitschaltung von Tag auf Abend
- Wenn bereits IstTag auf Tag oder Abend geschaltet hat, 
- Aktionsschaltung von Morgen auf Tag (IstTag)
- Aktionsschaltung von Tag auf Abend (IstTag)
- Geht auf Nebenprogramm, sobald mindestens 1 NebenPM an geht und kein HauptPM an ist
- Geht auf Hauptprogramm, sobald mindestens 1 HaupPM an geht.
- Geht aus, solbald kein NebenPM mehr an ist und kein HauptPM an ist.

## 2. Voraussetzungen


## 3. Installation

## 4. Funktionsreferenz

## 5. Konfiguration

### 5.1 Überprüfen, ob der zu steuernde Rollladen korrekt in IP-Symcon eingerichtet ist

### 5.2 Einrichtung des Wochenplans

### 5.3 Tagerkennung (optional)

#### 5.3.1 Übersteuernde Tagesanfang- und Endezeiten (optional)

### 5.4 Beschattung (optional)

#### 5.4.1 Beschattung nach Sonnenstand (optional)

### 5.4.2 Beschattung nach Helligkeit (optional)

### 5.5 Erkennung von Kontakten (optional)

### 5.6 Blind Controller

## 6. Statusvariablen und Profile

Folgende Statusvariablen werden angelegt:

#####ACTIVATED
Über die Statusvariable kann die automatische Steuerung aktiviert und deaktiviert werden. Beim (Wieder-)Einschalten der automatischen Steuerung werden vorher erkannte manuelle Eingriffe verworfen.
 
#####LAST_MESSAGE
Die Statusvariable beinhaltet einen Hinweis über die letzte Bewegung. Um die Bewegungen eines Rollladens zu kontrollieren, bietet es sich an, die Archivierung für diese Variable einzuschalten. 
Dann werden im Webfront die Bewegungen in Form eines Logfiles dargestellt.  

## 7. Anhang

###  GUIDs der Module

|           Modul            |  Typ   |                  GUID                  |
|:--------------------------:|:------:|:--------------------------------------:|
|      Blind Controller      | Device | {538F6461-5410-4F4C-91D3-B39122152D56} |
| Blind Control Group Master | Device | {1ACD8A0D-5385-6D05-9537-F24C9014FD02} |