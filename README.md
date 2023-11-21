# Light Control

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
    1. [GUIDs der Module](#guids-der-modul(e))

## 1. Funktionsumfang

Durch die Vewendung eines an KNX gekoppelten Dali Systems ergaben sich viele einzelnd zu steuernde Lichtpunkte in der Wohneinheit. Da die grundsätzliche Steuerung durch Präsenzmelder realisiert werden sollte, standen dadurch vielfältige Möglichkeiten einer sehr feinen Ansteuerung der einzelnen Lichtpunkte zur verfügung. Grundsätzlich sollte zwar soviel Logik wie Möglich in dem autarken KNX System implementiert werden, allerdings schien der Aufwand an Hardware (Logikmodule, Zeitschaltuhren,..) überprpportinal hoch.

Ein herzliches Dankeschön muss ich an dieser Stelle an @bumaas aussprechen, der mit seinem BlindControl Modul nicht nur meine Anforderungen an eine Rolladensteuerung teilweise übertroffen hat, sondern mit dem Code seines Modules auch an vielen Stelle "Pate" stand da es etliche Überschneidungen in den Logikanforderungen gab.

- 1 Daliaddresse wird geschaltet
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
IP Symcon 7.0 und später
Der Lichtpunkt muss einen an/aus Schalter haben (boolean)
UND der Lichtpunkt muss ein Dimmobjekt haben (integer 0-100 % )
Die Auslösenden Variablen (Präsenzmelder) sind vom typ boolean


## 3. Installation

Das Modul wird über den Modul Store installiert.

Anlegen einer Lichtinstanz
In Symcon an beliebiger Stelle Instanz hinzufügen auswählen und Light Controller auswählen. Es wird eine Lichtinstanz angelegt, in der die Eigenschaften zur Steuerung eines einzelnen Licht(punkt)es gesetzt werden.

## 4. Funktionsreferenz

## 5. Konfiguration

### 5.1 Überprüfen, ob der zu steuernde Lichtpunkt korrekt in IP-Symcon eingerichtet ist

### 5.2 Einrichtung des Wochenplans

### 5.3 Tagerkennung (optional)

#### 5.3.1 Übersteuernde Tagesanfang- und Endezeiten (optional)

### 5.4 Licht nach Helligket (optional)

### 5.5 Erkennung von Kontakten (optional)

### 5.6 Light Controller

## 6. Statusvariablen und Profile

Folgende Statusvariablen werden angelegt:

#####ACTIVATED
Über die Statusvariable kann die automatische Steuerung aktiviert und deaktiviert werden. Beim (Wieder-)Einschalten der automatischen Steuerung werden vorher erkannte manuelle Eingriffe verworfen.
 
#####LAST_MESSAGE
Die Statusvariable beinhaltet einen Hinweis über die letzte Schaltung. Um die Schaltungen eines Lichpunktes zu kontrollieren, bietet es sich an, die Archivierung für diese Variable einzuschalten. 
Dann werden im Webfront die Schaltereignisse in Form eines Logfiles dargestellt.  

## 7. Anhang

###  GUIDs der Module

|           Modul            |  Typ   |                  GUID                  |
|:--------------------------:|:------:|:--------------------------------------:|
|      Blind Controller      | Device | {4CE0F1A7-2B82-C104-8CD8-6AA669E534CF} |
| ...                        | Device | {...                                 } |