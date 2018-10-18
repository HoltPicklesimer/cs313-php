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