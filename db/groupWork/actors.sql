CREATE TABLE actor
(
	id SERIAL PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	birthYear SMALLINT
);

CREATE TABLE movie
(
	id SERIAL PRIMARY KEY,
	title VARCHAR(100) NOT NULL,
	runtime SMALLINT,
	year SMALLINT
);

CREATE TABLE actor_movie
(
	id SERIAL PRIMARY KEY,
	actor_id INT NOT NULL REFERENCES actor(id),
	movie_id INT NOT NULL REFERENCES movie(id)
);

INSERT INTO actor (name, birthYear) VALUES ('Jimmy Stuart', 1908);
INSERT INTO actor (name, birthYear) VALUES ('Chris Pratt', 1979);
INSERT INTO actor (name, birthYear) VALUES
('Tom Cruise', 1962),
('Meryl Streep', 1949),
('Carrie Fisher', 1956);

SELECT name, birthYear FROM actor ORDER BY birthYear;

INSERT INTO movie (title, runtime, year) VALUES
('It''s a wonderful life', 120, 1957),
('The Devil wears Prada', 125, 2006),
('Guardians of the Galaxy', 140, 2014);

INSERT INTO actor_movie (actor_id, movie_id) VALUES
(2, 6),
(1, 4),
(1, 4),
(4, 5),
(1, 5);

SELECT * FROM movie WHERE title = 'It''s a wonderful life';
SELECT * FROM movie WHERE title LIKE '%w%';

SELECT * FROM movie m
JOIN actor_movie am ON m.id = am.movie_id;