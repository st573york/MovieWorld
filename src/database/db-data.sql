--                                                                                                                                                                                                        
-- Users 
--
-- username -> admin
-- password -> admin   
--
-- username -> alice / bob / carol
-- password -> 1234                                                                                                                                                                                               
--  
INSERT INTO users ( username, password, email ) 
VALUES
    ( 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com' ),
    ( 'alice', '81dc9bdb52d04dc20036dbd8313ed055', 'alice@gmail.com' ),
    ( 'bob', '81dc9bdb52d04dc20036dbd8313ed055', 'bob@gmail.com' ),
    ( 'carol', '81dc9bdb52d04dc20036dbd8313ed055', 'carol@gmail.com' );

--                                                                                                                                                                                                        
-- Movies
--
INSERT INTO movies ( title, description, userid, creation_date ) 
VALUES
    ( 'The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', ( SELECT userid FROM users WHERE username = 'alice' ), '2022-07-06 10:00:00' ),
    ( 'The Godfather', 'The aging patriarch of an organized crime dynasty in postwar New York City transfers control of his clandestine empire to his reluctant youngest son.', ( SELECT userid FROM users WHERE username = 'alice' ), '2022-07-03 10:00:00' ),
    ( 'Schindler''s List', 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.', ( SELECT userid FROM users WHERE username = 'bob' ), '2022-07-05 10:00:00' ),
    ( 'Forrest Gump', 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75, whose only desire is to be reunited with his childhood sweetheart.', ( SELECT userid FROM users WHERE username = 'bob' ), '2022-07-02 10:00:00' ),
    ( 'The Lord of the Rings: The Return of the King', 'Gandalf and Aragorn lead the World of Men against Sauron''s army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.', ( SELECT userid FROM users WHERE username = 'carol' ), '2022-07-04 10:00:00' ),
    ( 'Fight Club', 'An insomniac office worker and a devil-may-care soap maker form an underground fight club that evolves into much more.', ( SELECT userid FROM users WHERE username = 'carol' ), '2022-07-01 10:00:00' );

--                                                                                                                                                                                                        
-- Movie votes
--
INSERT INTO movie_votes( movieid, userid, vote_like, vote_hate )
VALUES
    ( ( SELECT movieid FROM movies WHERE title = 'The Shawshank Redemption' ), ( SELECT userid FROM users WHERE username = 'bob' ), true, false ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Shawshank Redemption' ), ( SELECT userid FROM users WHERE username = 'carol' ), true, false ),
    ( ( SELECT movieid FROM movies WHERE title = 'Schindler''s List' ), ( SELECT userid FROM users WHERE username = 'alice' ), true, false ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Lord of the Rings: The Return of the King' ), ( SELECT userid FROM users WHERE username = 'alice' ), false, true ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Lord of the Rings: The Return of the King' ), ( SELECT userid FROM users WHERE username = 'bob' ), false, true ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Godfather' ), ( SELECT userid FROM users WHERE username = 'bob' ), true, false ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Godfather' ), ( SELECT userid FROM users WHERE username = 'carol' ), false, true ),
    ( ( SELECT movieid FROM movies WHERE title = 'Forrest Gump' ), ( SELECT userid FROM users WHERE username = 'alice' ), true, false ),
    ( ( SELECT movieid FROM movies WHERE title = 'Forrest Gump' ), ( SELECT userid FROM users WHERE username = 'carol' ), true, false );

--                                                                                                                                                                                                        
-- Movie reviews
--
INSERT INTO movie_reviews( movieid, userid, review, creation_date )
VALUES
    ( ( SELECT movieid FROM movies WHERE title = 'The Shawshank Redemption' ), ( SELECT userid FROM users WHERE username = 'bob' ), 'It is no wonder that the film has such a high rating, it is quite literally breathtaking. What can I say that hasn''t said before? Not much, it''s the story, the acting, the premise, but most of all, this movie is about how it makes you feel. Sometimes you watch a film, and can''t remember it days later, this film loves with you, once you''ve seen it, you don''t forget.', '2022-07-01 10:00:00' ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Shawshank Redemption' ), ( SELECT userid FROM users WHERE username = 'carol' ), 'Tied for the best movie I have ever seen.', '2022-08-01 10:00:00' ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Godfather' ), ( SELECT userid FROM users WHERE username = 'bob' ), 'This isn''t just a beautifully crafted gangster film. Or an outstanding family portrait, for that matter. An amazing period piece. A character study. A lesson in filmmaking and an inspiration to generations of actors, directors, screenwriters and producers. For me, this is more: this is the definitive film. 10 stars out of 10.', '2022-08-15 10:00:00' ),
    ( ( SELECT movieid FROM movies WHERE title = 'The Godfather' ), ( SELECT userid FROM users WHERE username = 'carol' ), 'Massively overrated.', '2022-08-30 10:00:00' ),
    ( ( SELECT movieid FROM movies WHERE title = 'Forrest Gump' ), ( SELECT userid FROM users WHERE username = 'alice' ), 'Quite simply, the greatest film ever made. Humour, sadness, action, drama and a Vietnam film all rolled into one. I''m not a stone cold, heartless villain, but it takes a lot to make me cry when I watch a movie. Bambi''s mother, I couldn''t care less. Jimmy Stewart in, "Oh, what a wonderful life," - yeah right! The Lion King, when Mufasa bites the big one - on the verge.', '2022-08-15 10:00:00' );
