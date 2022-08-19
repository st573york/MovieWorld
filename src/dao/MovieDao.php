<?php

/**
 * Data Access Object for movie data.
 */

class MovieDao
{
	var $table = 'movies';
	var $data = array();

    static $defaults = array(
        'movieid' => '',
        'title' => '',
        'description' => '',
        'userid' => ''
        );
    
    function get( $movieid, &$values )
    {
        global $conn;
    
        try
        {            
            $query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid";
    
            $stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT ); 
            $stmt->execute();
    
            if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) 
            {
                $values = $row;

                return TRUE;
            }
    
            return FALSE;
        }
        catch( PDOException $e )
        {
            echo $e->getMessage();
            return FALSE;
        }
    }

    function getAll( &$values, $obj = array() )
    {
        global $conn;

        try
        {            
            $count = '';
            $left_join = '';
            $where = '';
            $order_by = 'movies.creation_date DESC';

            if( isset( $obj['action'] ) )
            {
                switch( $obj['action'] )
                {
                    case 'sort_by_text':
                        $where .= 'WHERE movies.title LIKE :searchtext OR movies.description LIKE :searchtext OR users.username LIKE :searchtext';

                        break;
                    case 'sort_by_title':
                        $order_by = 'movies.title ASC';

                        break;
                    case 'sort_by_likes':
                        $count .= 'COUNT( movie_votes.movieid ) AS total_likes,';
                        $left_join .= 'LEFT JOIN movie_votes ON movies.movieid = movie_votes.movieid AND movie_votes.vote_like IS TRUE';
                        $order_by = 'total_likes DESC';

                        break;
                    case 'sort_by_hates':
                        $count .= 'COUNT( movie_votes.movieid ) AS total_hates,';
                        $left_join .= 'LEFT JOIN movie_votes ON movies.movieid = movie_votes.movieid AND movie_votes.vote_hate IS TRUE';
                        $order_by = 'total_hates DESC';
    
                        break;
                    case 'sort_by_reviews':
                        $count .= 'COUNT( movie_reviews.movieid ) AS total_reviews,';
                        $left_join .= 'LEFT JOIN movie_reviews ON movies.movieid = movie_reviews.movieid';
                        $order_by = 'total_reviews DESC';
        
                        break;
                    case 'sort_by_author':
                        $order_by = 'users.username ASC, movies.creation_date DESC';

                        break;
                    case 'sort_by_date_oldest':
                        $order_by = 'movies.creation_date ASC';
        
                        break;
                }
            }

            $query = "SELECT $count movies.movieid, movies.title, movies.description, 
                             date_format( movies.creation_date, '%d/%m/%Y' ) AS posted, users.username AS posted_by
                      FROM {$this->table}
                      $left_join
                      LEFT JOIN users ON movies.userid = users.userid
                      $where
                      GROUP BY movies.movieid
                      ORDER BY $order_by";

            $stmt = $conn->prepare( $query );
            if( isset( $obj['action'] ) )
            {            
                if( $obj['action'] == 'sort_by_text' ) 
                {
                    $searchtext = '%'.$obj['searchtext'].'%';
                    $stmt->bindParam( ':searchtext', $searchtext, PDO::PARAM_STR );
                }
            }
            $stmt->execute();

            while( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                $values[] = $row;
            }

            return TRUE;
        }
        catch( PDOException $e )
        {
            echo $e->getMessage();
            return FALSE;
        }
    }
    
    function insert( $values )
	{
        global $conn;
        
   		try 
		{
            $this->data = self::$defaults;
            
			foreach( array_keys( $this->data ) as $key ) 
			{
				if( isset( $values[ $key ] ) ) {				
                    $this->data[ $key ] = $values[ $key ];
				}
	   		}
           
            $query = "INSERT INTO {$this->table}
                      ( title, description, userid )
                      VALUES
                      ( :title, :description, :userid )";
                        
			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':title', $this->data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $this->data['description'], PDO::PARAM_STR );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );                                                
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e ) 
		{	
			echo $e->getMessage();
			return FALSE;
		}
	}

    function update( $values )
	{
        global $conn;
        
   		try 
		{
            $this->data = self::$defaults;
            
			foreach( array_keys( $this->data ) as $key ) 
			{
				if( isset( $values[ $key ] ) ) {				
                    $this->data[ $key ] = $values[ $key ];
				}
	   		}
            
            $query = "UPDATE {$this->table}
                      SET title = :title, description = :description
                      WHERE movieid = :movieid";

            $stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT ); 
			$stmt->bindParam( ':title', $this->data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $this->data['description'], PDO::PARAM_STR );                                               
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e ) 
		{	
			echo $e->getMessage();
			return FALSE;
		}
	}

    function delete( $movieid )
    {
        global $conn;
    
        try
        {            
            // Delete votes
            $query = "DELETE FROM movie_votes
                      WHERE movieid = :movieid";
    
            $stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT ); 
            $stmt->execute();

            // Delete reviews
            $query = "DELETE FROM movie_reviews
                      WHERE movieid = :movieid";
    
            $stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT ); 
            $stmt->execute();

            // Delete movie
            $query = "DELETE FROM {$this->table}
                      WHERE movieid = :movieid";
    
            $stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT ); 
            $stmt->execute();
    
            return TRUE;
        }
        catch( PDOException $e )
        {
            echo $e->getMessage();
            return FALSE;
        }
    }
}

?>
