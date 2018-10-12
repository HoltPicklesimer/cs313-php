CREATE TABLE users
(
	id SERIAL PRIMARY KEY
	, username VARCHAR(50) UNIQUE NOT NULL
	, password VARCHAR(50) NOT NULL
);

CREATE TABLE speakers
(
		id SERIAL PRIMARY KEY
	, name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE sessions
(
		id SERIAL PRIMARY KEY
	, month SMALLINT NOT NULL
	, year SMALLINT NOT NULL
);

CREATE TABLE notes
(
		id SERIAL PRIMARY KEY
	, content TEXT
	, _date DATE NOT NULL
	, user_id INT NOT NULL REFERENCES users(id)
	, speaker_id INT NOT NULL REFERENCES speakers(id)
	, sessions_id INT NOT NULL REFERENCES sessions(id)
);