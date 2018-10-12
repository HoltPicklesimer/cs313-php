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
	, release_date DATE NOT NULL
	, lyrics TEXT
	, artist_id INT NOT NULL REFERENCES artists(id)
	, genre_id INT NOT NULL REFERENCES genres(id)
);

CREATE TABLE playlists
(
		id SERIAL PRIMARY KEY
	, user_id INT NOT NULL REFERENCES users(id)
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
	, playlist_id INT NOT NULL REFERENCES playlists(id)
	, song_id INT NOT NULL REFERENCES songs(id)
);

INSERT INTO genres (name)
VALUES
('Alternative'),
('Blues'),
('Christmas'),
(E'Children\'s'),
('Classical'),
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