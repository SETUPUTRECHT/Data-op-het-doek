
Installatieprocedure

1. Kopieer alle bestanden in het mapje "dod" naar je webserver.
2. Importeer de database met het .SQL bestand in de map INSTALL
3. In het bestand /classes/inc.mysql.php op regel 15 t/m 17 de gegevens om contact te maken met de database

Nu heb je de basisversie online.

Aanpassen van 

1. Ga naar je database manager (ik gebruik phpMyAdmin) en selecteer je database
2. Ga naar de tabel "project"

De tabel heeft een aantal velden
id: uniek identificatie nummer
volgnr: een nummer om projecten mee te ordenen
active: 1 of 0 boolean of het project wordt laten zien
naam: De naam van het project
snapshot: het pad naar het de thumbnail voor in de mobiele website (250x250 pixels)

##### Pas de volgorde van projecten aan door het volgnr te veranderen

