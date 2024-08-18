# Kundenverwaltungsapp

Die ist eine Kundenverwaltungsapp, mit einem VueJS Frontend und einem Laravel Backend das als API genutzt wird.

## Inhaltsverzeichnis

1. [Projekt](#projekt)
    1. [Beschreibung](#beschreibung)
    2. [Aufgabenanforderungen](#aufgabenanforderungen)
    3. [Projektaufbau](#projektaufbau)
    4. [Verbesserungsmöglichkeiten](#verbesserungsmöglichkeiten)
2. [Backend](#backend)
    1. [Backend Installation](#backend-installation)
    2. [Seeder](#seeder)
    3. [API-Endpunkte](#api-endpunkte)
    4. [Tests](#tests)
3. [Frontend](#frontend)
    1. [Installation](#installation)
    2. [Frontend Umgebungsvariablen](#frontend-umgebungsvariablen)
4. [Autor](#autor)

## Projekt

### Beschreibung

Bei diesem Projekt handelt es sich um eine kleine Kundenverwaltungsapp. Kunden lassen sich anlegen, bearbeiten und löschen.
Es gibt einen Themeswitch, die Seite merkt sich ein paar Nutzereinstellungen und die Daten sind persistent.

### Aufgabenanforderung

- Ein Kunde kann mit folgenden Parametern erfasst werden (Nachname, Vorname, Email, Telefonnummer, Straße+Nr., PLZ, Ort)
- Eine Id wird automatisch erzeugt bei der Erstellung
- Ein Kunde lässt sich nach folgenden Parametern suchen (Nachname, Vorname, Email, Id)
- Der Datensatz eines Kunden kann vollständig angezeigt werden
- Folgenede Parameter sollen sich bearbeiten und speichern lassen: (Nachname, Vorname, Email, Telefonnummer, Straße+Nr., PLZ, Ort)
- Ein Kunde kann gelöscht werden
- Alle Änderung sind persistent auf der Datenbank

### Projektaufbau

Das Projekt ist aufgeteilt in zwei einzeln agierende Apps:  
**Frontend**  
Das Frontend ist VueJS mit Vuetify und Vite als Buildtool. Es gibt einen Header und eine Kundentabelle. Die Kundentabelle ruft alle Modale auf und sendet einzelnen Befehle für den Server an den ApiService.
Lokale Speicherung von einzelnen Nutzerpräferenzen werden im LocalStorage abgelegt.

**Backend**  
Das Backend besteht aus Laravel. Laravel selbst wird hier nur als API verwendet.

### Verbesserungsmöglichkeiten

Folgende Punkte lassen sich noch verbessern oder auch erweitern:

- Eine Sortiermöglichkeit nach einzelnen Spalten in der Tabelle, nicht nur nach der ID
- Frontend testing wäre noch zu ergänzen um die App robuster zu machen
- Ein Authentifizierungsfeature würde die Sicherheit erhöhen
- Die Datenbank anpassen, sodass Straße und Hausnummer getrennt sind
- Eine engere Verknüpfung von VueJS und Laravel könnte die Performance steigern

## Backend
### Backend Installation
Die offizielle README-Datei der Originalinstallation ist ebenfalls im Backend-Ordner verfügbar.
1. **Projekt klonen - Gleicher Schritt beim Frontend, wird nur einmal für beides benötigt**  
   ```bash
   git clone https://github.com/robin-boschkundenverwaltung.git
   ```
2. **In den Ordner navigieren**  
    ```bash
    cd kundenverwaltung/backend
    ```
3. **Abhängigkeiten installieren**  
    ```bash
    composer install
    npm install
    ```
4. **App Schlüssel generieren**  
    ```bash
    php artisan key:generate
    ```
5. **Umgebungsvariablen in die .env Datei eintragen**  
    Am besten die .env.example kopieren und durch die eigenen Daten ersetzen. Besonders wichtig ist die Datenbankverbindung.  

6. **Datenbank migrieren**  
    ```bash
    php artisan migrate
    ```
7. **(optional) Datenbank mit Testdaten füllen - Siehe [Seeder](#seeder) für mehr Infos**  
8. **Server starten**
    ```bash
    php artisan serve
    ```
   
### Seeder
Die Datenbank lässt sich mit folgenden Befehl mit 50 Testdatensätze füllen:  
```bash
php artisan db:seed --class=KundeSeeder
```
Die Zahl lässt sich auch in der Datei /database/seeder/KunderSeeder.php anpassen im For-Loop.  

### API-Endpunkte

Folgende API-Endpunkte stehen zur Verfügung:  

- GET /api/kunde: Liste aller Kunden mit Paginierung und optionaler Suche.
- POST /api/kunde: Erstellen eines neuen Kunden.
- GET /api/kunde/{id}: Abrufen eines bestimmten Kunden.
- PUT /api/kunde/{id}: Aktualisieren eines bestimmten Kunden.
- DELETE /api/kunde/{id}: Löschen eines bestimmten Kunden.

### Tests
Die Unittests sind automatisch bei der Datenbank auf .env.testing Datei eingestellt, welches eine lokale sqlite Instanz nutzt.
Die Tests können mit folgendem Befehl ausgeführt werden
```bash
php artisan test
```

## Frontend

### Frontend Installation
Die offizielle README-Datei der Originalinstallation ist ebenfalls im Frontend-Ordner verfügbar.

1. **Projekt klonen - Gleicher Schritt beim Backend, wird nur einmal für beides benötigt**  
   ```bash
   git clone https://github.com/robin-boschkundenverwaltung.git
   ```
2. **In den Ordner navigieren**  
    ```bash
    cd kundenverwaltung/frontend
    ```
3. **Abhängigkeiten installieren**
    ```bash
    npm install
    ```
4. **Die Umgebungsvariable setzen**  
    Es muss der Link direkt zum API-Endpunkt als Umgebungsvariable eingetragen werden. Beispiel:   
    ```bash
    VITE_API_BASE_URL=http://localhost:8000/api
    ```
    /api ist dabei wichtig.

5. **Server starten**
    Lokal:
    ```bash
    npm run dev
    ```
    Für Production:
    ```bash
    npm run build
    npm start
    ```
## Autor
Dieses Projekt wurde von Robin Bosch entwickelt.