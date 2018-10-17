CREATE TABLE users
(
		id SERIAL PRIMARY KEY
	, username VARCHAR(50) UNIQUE NOT NULL
	, password VARCHAR(50) NOT NULL
);

CREATE TABLE genres
(
		id SERIAL PRIMARY KEY
	, name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE artists
(
		id SERIAL PRIMARY KEY
	, name VARCHAR(50) UNIQUE NOT NULL
	, contributor_id INT NOT NULL REFERENCES users(id)
	, genre_id INT NOT NULL REFERENCES genres(id)
);

CREATE TABLE songs
(
		id SERIAL PRIMARY KEY
	, name VARCHAR(100) NOT NULL
	, contributor_id INT NOT NULL REFERENCES users(id)
	, url VARCHAR(500) UNIQUE
	, release_date VARCHAR(50)
	, lyrics TEXT
	, artist_id INT NOT NULL REFERENCES artists(id)
	, genre_id INT NOT NULL REFERENCES genres(id)
);

CREATE TABLE reviews
(
		id SERIAL PRIMARY KEY
	, user_id INT NOT NULL REFERENCES users(id)
	, song_id INT NOT NULL REFERENCES songs(id)
	, publish_date DATE NOT NULL
	, content TEXT
	, rating SMALLINT NOT NULL
);

CREATE TABLE playlistsongs
(
		id SERIAL PRIMARY KEY
	, user_id INT NOT NULL REFERENCES users(id)
	, song_id INT NOT NULL REFERENCES songs(id)
);

INSERT INTO genres (name)
VALUES
('Alternative'),
('Blues'),
('Christmas'),
(E'Children\'s'),
('Classical'),
('Classic Rock'),
('Country'),
('Dance'),
('Easy Listening'),
('Electronic'),
('European/Folk'),
('Gothic Rock'),
('Grunge'),
('Hip Hop'),
('Indie'),
('Inspirational/Gospel'),
('Instrumental'),
('Asian/K-Pop'),
('Jazz'),
('Latin'),
('Metal'),
('New Age'),
('Opera'),
('Other'),
('Pop'),
('Punk'),
('R&B/Soul'),
('Rap'),
('Reggae'),
('Rock'),
('Soundtrack'),
('Vocal'),
('World Music/Beats');

INSERT INTO users (username, password)
VALUES
('DwayneTheRock22', 'pass123'),
('Tobasco53', 'pass456'),
('MeatLoversPizza89', '123pass');

INSERT INTO artists (name, contributor_id, genre_id)
VALUES
('Matchbox Twenty', 1, 1),
('Goo Goo Dolls', 1, 1),
('The Killers', 2, 1),
('Beatles', 3, 5),
('Led Zeplin', 3, 5),
('Pink Floyd', 1, 5),
('Bastille', 2, 15),
('Tabernacle Choir at Temple Square', 2, 16);

INSERT INTO songs (name, contributor_id, url, release_date, lyrics, artist_id, genre_id)
VALUES
('3AM', 1, 'https://www.youtube.com/embed/C-Naa1HXeDQ', '1996', 'Lyrics to be added Later', 1, 1),
('Push', 1, 'https://www.youtube.com/embed/HAkHqYlqops', '1996', 'Add Later', 1, 1),
('Slide', 2, 'https://www.youtube.com/embed/yP4qdefD2To', '1998', 'Add Later', 2, 1),
('Miss Atomic Bomb', 3, 'https://www.youtube.com/embed/Qok9Ialei4c', '2012', 'Add Later', 3, 1);

INSERT INTO reviews (user_id, song_id, publish_date, content, rating)
VALUES
(1, 1, '2018-10-17', 'I really liked this song, very good.', 4),
(1, 2, '2018-8-17', 'So much nostalgia', 5),
(2, 1, '2018-10-17', 'Another great song.', 5),
(3, 4, '2018-10-17', 'This is a very good song too.', 4);

INSERT INTO playlistsongs (user_id, song_id)
VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 3),
(2, 1),
(3, 3);