--                                                                                                                                                                                                        
-- Users 
--
-- username -> admin
-- password -> admin   
--
-- username -> alice
-- password -> 1234
--
-- username -> bob
-- password -> 1234                                                                                                                                                                                                  
--  
INSERT INTO users ( username, password, email ) 
VALUES
    ( 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com' ),
    ( 'alice', '81dc9bdb52d04dc20036dbd8313ed055', 'alice@gmail.com' ),
    ( 'bob', '81dc9bdb52d04dc20036dbd8313ed055', 'bob@gmail.com' );

--                                                                                                                                                                                                        
-- Movies
--
INSERT INTO movies ( title, description, userid ) 
VALUES
    ( 'The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.', ( SELECT userid FROM users WHERE username = 'alice' ) ),
    ( 'The Godfather', 'The aging patriarch of an organized crime dynasty in postwar New York City transfers control of his clandestine empire to his reluctant youngest son.', ( SELECT userid FROM users WHERE username = 'alice' ) ),
    ( 'Schindler''s List', 'In German-occupied Poland during World War II, industrialist Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.', ( SELECT userid FROM users WHERE username = 'bob' ) ),
    ( 'Forrest Gump', 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75, whose only desire is to be reunited with his childhood sweetheart.', ( SELECT userid FROM users WHERE username = 'bob' ) );