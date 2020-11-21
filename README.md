# eTrax | rescue - Verwaltung und Livetracking für Personensuchen

## Development Environment Setup

> __Wichtig__: Für die Entwicklung von eTrax | rescue muss [node.js](https://nodejs.org/) installiert sein

* Repository auschecken
* Alle nötigen __node-Pakete__ sind im __package.json__-File angeführt 
* Development Files liegen im __dev__ Verzeichnis
* Beim Building-Prozess werden die erzeugten Dateien in ein Verzeichnis mit dem Namen __v5__ geschrieben
* Der Building-Prozess wird durch __gulp__ gesteuert
	* __webpack-stream__ importiert die js Files in ol.js (import jQuery from 'jquery')
	
### Installation des Development Environments

* Um die benötigten __node-Pakete__ zu installieren ein Terminal Fenster öffnen und __npm install__ eingeben
	
### Anstoßen des build Prozesses

* Zum Builden im Terminal __npm run build__ eingeben
* Mit __npm run watch__ wird die __watcher__ Funktion von __gulp__ aktiviert, die Änderungen in den Development Files verfolgt und den Build anstößt wenn Änderungen gespeichert werden

## Installation der Software

### Systemvoraussetzungen
Voraussetzungen zur Installation:
* MySQL (5.7.28 oder höher) - 1 Datenbank 
* PHP (7.3 oder höher)
* Zur Nutzung der APP bzw. BOS-Schnittstelle muss jeweils 1 Subdomain angelegt werden können

### Download
* Aktuellste Release im Repository https://github.com/etrax-rescue/webapp/releases oder
* Builden der aktuellen Version (siehe oberhalb)

### Installation
