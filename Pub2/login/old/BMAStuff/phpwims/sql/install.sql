CREATE DATABASE phpwims;
GRANT ALL PRIVILEGES ON phpwims.* TO phpwims@localhost IDENTIFIED BY 'phpwims';
USE phpwims
CREATE TABLE bottle (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, type VARCHAR(10),
   vintage MEDIUMINT UNSIGNED, producer VARCHAR(50), vineyard VARCHAR(50), varietal VARCHAR(40),
   region VARCHAR(50), country VARCHAR(30), datecellared YEAR, beginconsume YEAR, consumeby YEAR,
   store VARCHAR(40), price VARCHAR(15), quantity TINYINT UNSIGNED, appellation VARCHAR(40),
   size VARCHAR(10), score1 TINYINT UNSIGNED, score2 TINYINT UNSIGNED, score3 TINYINT UNSIGNED);
CREATE TABLE notes (id INT UNSIGNED NOT NULL PRIMARY KEY, usernotes LONGTEXT);
CREATE TABLE translog (id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, bottleid INT UNSIGNED,
   time VARCHAR(40), type VARCHAR(10), day VARCHAR(9), month VARCHAR(9), year YEAR, user VARCHAR(40));
CREATE TABLE user (id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT, username VARCHAR(20),
   password VARCHAR(20));
CREATE TABLE list (id INT UNSIGNED PRIMARY KEY NOT NULL, vintage MEDIUMINT UNSIGNED,
   producer VARCHAR(40), varietal VARCHAR(40), appellation VARCHAR(40), store VARCHAR(40),
   price VARCHAR(10), user VARCHAR(20));
INSERT INTO user VALUES (NULL, 'admin', 'pwXTH6q.I0cRU');
CREATE TABLE options (id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT, user VARCHAR(25),
   type VARCHAR(10), country VARCHAR(13), scoreref1 VARCHAR(40), scoreref2 VARCHAR(40),
   scoreref3 VARCHAR(40));
INSERT INTO options VALUES (NULL, 'admin', 'Red', 'France', 'Wine Advocate', 'Wine Spectator',
   'Wine Enthusiast');
