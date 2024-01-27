-- main table for audiences. There should be a unique username for every audience.
CREATE TABLE Audience (
username VARCHAR(200),
user_password VARCHAR(200),
name_ VARCHAR(200),
surname VARCHAR(200),
PRIMARY KEY (username)
);

-- id should be incremented by one for new entities starting from 1 automatically. 
-- platform name should be also unique
CREATE TABLE Rating_Platforms(
platform_id INT UNSIGNED AUTO_INCREMENT,
platform_name VARCHAR(200),
PRIMARY KEY (platform_id),
UNIQUE(platform_name)
);

-- every director should have a unique username
CREATE TABLE Director (
username VARCHAR(200),
user_password VARCHAR(200),
name_ VARCHAR(200),
surname VARCHAR(200),
nation VARCHAR(200) NOT NULL, -- NOT NULL constraint ensures that every director is a member of exactly one nation
platform_id INT UNSIGNED,
PRIMARY KEY (username),
FOREIGN KEY(platform_id) REFERENCES Rating_Platforms(platform_id) -- every director can attend at most one rating platform
);

-- Subscribes_Rating_Platform keeps track of subscriptions of audiences. Simulates subscribes_to relation from ER diagram between audience and platforms.
-- Audiences may subscribes one or more platforms
CREATE TABLE Subscribes_Rating_Platform(
username VARCHAR(200),
platform_id INT UNSIGNED NOT NULL,
FOREIGN KEY(platform_id) REFERENCES Rating_Platforms(platform_id) ON DELETE CASCADE,
FOREIGN KEY(username) REFERENCES audience(username) ON DELETE CASCADE,
PRIMARY KEY(username,platform_id)
);


CREATE TABLE Genre(
genre_id INT UNSIGNED AUTO_INCREMENT,
genre_name VARCHAR(200),
PRIMARY KEY (genre_id),
UNIQUE(genre_name)
);


CREATE TABLE Movie ( 
movie_id INT UNSIGNED AUTO_INCREMENT,
director_name VARCHAR(200) NOT NULL, -- every movie has exactly one director
movie_name VARCHAR(200),
genre_id INT UNSIGNED NOT NULL, -- every movie has at least one genre
average_rating FLOAT,
duration INT NOT NULL, -- Added
PRIMARY KEY (movie_id),
FOREIGN KEY (director_name) REFERENCES Director(username) ON DELETE CASCADE,
FOREIGN KEY (genre_id) REFERENCES Genre(genre_id) ON DELETE NO ACTION
);
-- Genre_List table ensures that a movie can have multiple genres.
CREATE TABLE Genre_List(
movie_id INT UNSIGNED,
genre_id INT UNSIGNED,
FOREIGN KEY (movie_id) REFERENCES movie(movie_id) ON DELETE CASCADE,
FOREIGN KEY (genre_id) REFERENCES Genre(genre_id) ON DELETE CASCADE,
PRIMARY KEY (movie_id,genre_id)
);


CREATE TABLE Theatre(
theatre_id INT UNSIGNED AUTO_INCREMENT,
theatre_name VARCHAR(200),
theatre_capacity INT,
theatre_district VARCHAR(200),
PRIMARY KEY (theatre_id)
);

CREATE TABLE Movie_sessions(
session_id INT UNSIGNED AUTO_INCREMENT,
movie_id INT UNSIGNED NOT NULL,
theatre_id INT UNSIGNED NOT NULL,
time_slot INT NOT NULL,
session_date date NOT NULL,
FOREIGN KEY (movie_id) REFERENCES Movie(movie_id) ON DELETE CASCADE,
FOREIGN KEY (theatre_id) REFERENCES Theatre(theatre_id) ON DELETE CASCADE,
UNIQUE(theatre_id,time_slot,session_date), -- only one movie can be screened at same theatre, at same time_slot and date.
PRIMARY KEY(session_id)
);
CREATE TABLE Predecessors(
movie_id INT UNSIGNED,
pre_movie_id INT UNSIGNED,
FOREIGN KEY (movie_id) REFERENCES movie(movie_id) ON DELETE CASCADE,
FOREIGN KEY (pre_movie_id) REFERENCES movie(movie_id) ON DELETE CASCADE,
PRIMARY KEY (movie_id,pre_movie_id)
);

CREATE TABLE Has_Tickets(
username VARCHAR(200),
session_id INT UNSIGNED NOT NULL,
FOREIGN KEY (username) REFERENCES audience(username) ON DELETE CASCADE,
FOREIGN KEY (session_id) REFERENCES movie_sessions(session_id) ON DELETE CASCADE,
PRIMARY KEY(session_id,username)
);


CREATE TABLE Ratings(
movie_id INT UNSIGNED,
username VARCHAR(200),
rating FLOAT,
FOREIGN KEY (movie_id) REFERENCES Movie(movie_id) ON DELETE CASCADE, -- added
FOREIGN KEY (username) REFERENCES Audience(username) ON DELETE CASCADE, -- added
PRIMARY KEY (username, movie_id) -- user should be able to rate a movie at most once.
);

CREATE TABLE Database_managers(
username VARCHAR(200),
user_password VARCHAR(200),
PRIMARY KEY (username) -- managers should have unique names
);

CREATE TABLE not_available_slots (
theatre_id INT UNSIGNED NOT NULL,
time_slot INT NOT NULL check(time_slot <=4),
session_date date NOT NULL,
FOREIGN KEY (theatre_id) REFERENCES theatre(theatre_id) ON  DELETE CASCADE,
PRIMARY KEY (theatre_id,time_slot, session_date)
);
-- update overall rating after adding a new rating
CREATE TRIGGER upd_overallrating AFTER INSERT ON ratings
	FOR EACH ROW UPDATE movie m SET average_rating = (SELECT AVG(r.rating) FROM ratings r WHERE r.movie_id = NEW.movie_id) WHERE m.movie_id = NEW.movie_id; 
    
DELIMITER //
CREATE TRIGGER occupy_theatre before insert on movie_sessions
	for each row -- occupy related theatre slots after adding new movie sessions 
    -- an error will arise if some of the lots are occupied
    begin
		DECLARE n INT;
		set n = (SELECT duration from movie m where m.movie_id = new.movie_id) - 1;
		while (n >= 0 ) DO 
			IF (SELECT COUNT(*) from not_available_slots where theatre_id =new.theatre_id and time_slot = new.time_slot + n and session_date = new.session_date ) > 0 THEN
				signal sqlstate '45000' set message_text = 'Time slot is not available';
			END IF;
			INSERT INTO not_available_slots(theatre_id,time_slot,session_date) values(new.theatre_id, new.time_slot + n, new.session_date);
			set n = n - 1;
            END WHILE;
    end; //

DELIMITER // 
-- check if the number of database managers are less then 4
-- if so let insertion happen else show a error message
CREATE TRIGGER check_manager_number before insert on database_managers
	for each row 
    begin
	DECLARE manager_count INT;
	DECLARE msg varchar(128);
    SELECT COUNT(*) from database_managers INTO manager_count;
    IF manager_count = 4 THEN
		set msg = 'check_manager_number error: Cannot add more than 4 database managers';
        signal sqlstate '45000' set message_text = msg;
    END IF;
	end; //
    
    
DELIMITER // 
-- check if audience subscribes the movie's rating platform before rating the movie
CREATE TRIGGER check_subscription before insert on ratings
	for each row
	begin
	IF (SELECT COUNT(*) FROM (SELECT platform_id from director d where d.username IN (SELECT director_name FROM movie where movie_id = new.movie_id)) 
    t1 INNER JOIN (SELECT platform_id from subscribes_rating_platform WHERE username = new.username) t2 ON t1.platform_id = t2.platform_id) = 0 THEN
		signal sqlstate '45000' set message_text = 'Audience should subscribe to the platform of the movie before rating.';
    END IF;
    end; //
 DELIMITER //
 -- check if the audience has watched predecessor movies
 CREATE TRIGGER check_predecessors before insert on has_tickets
	for each row
    begin 
    declare movie_id INT;
    SELECT m.movie_id FROM movie_sessions m WHERE session_id = new.session_id INTO movie_id;
    IF (SELECT COUNT(p.pre_movie_id) from predecessors p where p.movie_id = movie_id and p.pre_movie_id not in (SELECT h.session_id from has_tickets h
     INNER JOIN movie_sessions m ON m.session_id = h.session_id where h.username = new.username and 
     m.session_date < (SELECT session_date FROM movie_sessions WHERE session_id = new.session_id))) > 0 THEN
     signal sqlstate '45000' set message_text = 'Audience should watch the predecessor movies before.';
     END IF;
    end; //

DELIMITER //
-- check if the theatre capacity is full
CREATE TRIGGER check_if_tickets_remain before insert on has_tickets
	for each row
    begin 
    declare tickets_remain VARCHAR(100);
    SELECT
    CASE WHEN (SELECT t.theatre_capacity from theatre t where t.theatre_id = (SELECT s.theatre_id from movie_sessions s where s.session_id = '".$_POST["session_id"]."')) > (SELECT COUNT(*) as sold_tickets FROM has_tickets where session_id = '".$_POST["session_id"]."') THEN 'TRUE'
    ELSE 'FALSE' END AS 'TicketsRemain' INTO tickets_remain;
    IF tickets_remain = 'FALSE' THEN
		signal sqlstate '45000' set message_text = 'Theatre capacity is full.';
    END IF;
    end; //
