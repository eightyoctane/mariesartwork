CREATE TABLE list (id INT UNSIGNED PRIMARY KEY NOT NULL, vintage MEDIUMINT UNSIGNED,
   producer VARCHAR(40), varietal VARCHAR(40), appellation VARCHAR(40), store VARCHAR(40),
   price VARCHAR(10), user VARCHAR(20));
CREATE TABLE options (id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT, user VARCHAR(25),
   type VARCHAR(10), country VARCHAR(13), scoreref1 VARCHAR(40), scoreref2 VARCHAR(40),
   scoreref3 VARCHAR(40));
INSERT INTO options VALUES (NULL, 'admin', 'Red', 'France', 'Wine Advocate', 'Wine Spectator',
   'Wine Enthusiast');
