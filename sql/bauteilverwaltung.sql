--  ES-Bauteilverwaltung-DB for SYP WS1920  --

--       Last change: 15.01.20       --

--           by Mert Torun (mtorun0x7cd) --

--
-- Datenbank
--

DROP DATABASE IF EXISTS bauteilverwaltung;
CREATE DATABASE bauteilverwaltung;
USE bauteilverwaltung;

--
-- Tabellen
--

DROP TABLE IF EXISTS ausleihposition CASCADE;
DROP TABLE IF EXISTS ausleihe CASCADE;
DROP TABLE IF EXISTS benutzer CASCADE;
DROP TABLE IF EXISTS benutzergruppen CASCADE;
DROP TABLE IF EXISTS artikel CASCADE;

CREATE TABLE benutzergruppen 
(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(45) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE benutzer 
(
	campusid VARCHAR(100) NOT NULL,
	id INT NOT NULL,
	passwort VARCHAR(255) NOT NULL,
	vorname VARCHAR(100) DEFAULT NULL,
	nachname VARCHAR(100) DEFAULT NULL,
	passwortcode VARCHAR(255) DEFAULT NULL, -- genutzt f�r die Funktion 'passwort vergessen'
	passwortcode_time TIMESTAMP NULL, -- genutzt f�r die Funktion 'passwort vergessen'
	email VARCHAR(100) NOT NULL,
	PRIMARY KEY (campusid),
	KEY fk_benutzer_id (id),
	CONSTRAINT fk_benutzer_id FOREIGN KEY (id) REFERENCES benutzergruppen(id)
);

CREATE TABLE artikel 
(
	teilenummer INT NOT NULL,
	name VARCHAR(100) NOT NULL,
	link VARCHAR(100) DEFAULT NULL,
	beschreibung VARCHAR(100) DEFAULT NULL,
	stueckzahl INT NOT NULL,
	stueckzahl_verfuegbar INT NOT NULL,
	foto VARCHAR(255) DEFAULT NULL,
	eaglebib VARCHAR(255) DEFAULT NULL,
	lager_raum VARCHAR(100) DEFAULT NULL,
	lager_schrank VARCHAR(100) DEFAULT NULL,
	meldebestand INT DEFAULT NULL,
	rueckgabe_noetig BOOLEAN NOT NULL,
	PRIMARY KEY (teilenummer)
);

CREATE TABLE ausleihe 
(
	ausleihid INT NOT NULL AUTO_INCREMENT,
	campusid VARCHAR(100) NOT NULL,
	ausgegeben_am DATE DEFAULT NULL,
	zurueckzugeben_bis DATE DEFAULT NULL,
	status ENUM("Offen", "In Bearbeitung", "Ausgegeben", "Ueberfaellig", "Zurueckgegeben") NOT NULL,
	PRIMARY KEY (ausleihid),
	KEY fk_ausleihe_campusid (campusid),
	CONSTRAINT fk_ausleihe_campusid FOREIGN KEY (campusid) REFERENCES benutzer(campusid)
);

CREATE TABLE ausleihposition 
(
	ausleihpositionid INT NOT NULL AUTO_INCREMENT,
	ausleihid INT NOT NULL,
	teilenummer INT NOT NULL,
	stueckzahl INT NOT NULL,
	PRIMARY KEY (ausleihpositionid),
	KEY fk_ausleihid (ausleihid),
	CONSTRAINT fk_ausleihid FOREIGN KEY (ausleihid) REFERENCES ausleihe(ausleihid),
	KEY fk_ausleihposition_teilenummer (teilenummer),
	CONSTRAINT fk_ausleihposition_teilenummer FOREIGN KEY (teilenummer) REFERENCES artikel(teilenummer)
);

--
-- Prozeduren / Trigger 
--

--
-- Die folgenden Trigger berechnen die stueckzahl_verfuegbar neu in der Tabelle artikel sobald sich ein Eintrag in ausleihe �ndert
--
DELIMITER //
DROP TRIGGER IF EXISTS stueckzahl_calc_sub_trig;
CREATE TRIGGER stueckzahl_calc_sub_trig 
BEFORE INSERT ON ausleihposition 
FOR EACH ROW 
BEGIN
	UPDATE artikel SET stueckzahl_verfuegbar = stueckzahl_verfuegbar - NEW.stueckzahl WHERE artikel.teilenummer = NEW.teilenummer;
END;//

DROP TRIGGER IF EXISTS stueckzahl_calc_add_trig;
CREATE TRIGGER stueckzahl_calc_add_trig 
BEFORE DELETE ON ausleihposition 
FOR EACH ROW 
BEGIN 
	UPDATE artikel SET stueckzahl_verfuegbar = stueckzahl_verfuegbar + OLD.stueckzahl WHERE artikel.teilenummer = OLD.teilenummer;
END;//
DELIMITER ;

--
-- Daten
--
INSERT INTO benutzergruppen (name) VALUES ("Neu"), ("Student"), ("Moderator"), ("Admin");
INSERT INTO benutzer (campusid, id, passwort, email) VALUES ("admin", 4, "$2y$10$n7Jvh6VaKNV9Jcy6Pql4LOImGWVCBiYEmMZBv1Ys3t8QyOKbL/5k2", "info@mtorun0x7cd.com"); -- default admin with password admin

--
-- Ausgabe
--

SHOW TRIGGERS;
SHOW TABLES;