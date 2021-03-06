CREATE DATABASE MovieWorld;

/*
<table name="users">
 <desc>Table stores list of users.</desc>
</table>
*/
CREATE TABLE users
(
    userid     BIGINT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(40) NOT NULL,
    password   VARCHAR(64) NOT NULL,
    email      VARCHAR(250) NOT NULL
);

CREATE UNIQUE INDEX users_username_idx ON users( username );

/*
<table name="movies">
 <desc>Table stores list of movies.</desc>
</table>
*/
CREATE TABLE movies
(
	movieid		  BIGINT AUTO_INCREMENT PRIMARY KEY,
	title             VARCHAR(64) NOT NULL,
	description       TEXT,
	userid            BIGINT NOT NULL REFERENCES users( userid ),
	creation_date     TIMESTAMP NOT NULL DEFAULT NOW(),	
	number_of_likes   INTEGER NOT NULL DEFAULT 0,
	number_of_hates   INTEGER NOT NULL DEFAULT 0
);

CREATE UNIQUE INDEX movies_title_idx ON movies( title );

/*
<table name="vote_movies">
 <desc>Table stores list of movies that users have either clicked like or hate.</desc>
</table>
*/
CREATE TABLE vote_movies
(
	movieid     BIGINT NOT NULL REFERENCES movies( movieid ),
	userid      BIGINT NOT NULL REFERENCES users( userid ),
	vote_like   BOOLEAN NOT NULL DEFAULT FALSE,
	vote_hate   BOOLEAN NOT NULL DEFAULT FALSE
);
