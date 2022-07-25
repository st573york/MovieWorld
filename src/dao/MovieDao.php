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

    function getAll( &$values, $order = NULL )
    {
        global $conn;

        try
        {            
            $count = '';
            $and = '';
            $where = '';            
            $order_by = 'creation_date';

            if( $order )
            {
                switch( $order )
                {
                    case 'sort_by_user':
                        $where .= ' WHERE movies.userid = :userid ';

                        break;
                    case 'sort_by_likes':
                        $count .= 'COUNT( movie_votes.movieid ) AS total_likes,';
                        $and .= 'AND movie_votes.vote_like IS TRUE';
                        $order_by = 'total_likes';

                        break;
                    case 'sort_by_hates':
                        $count .= 'COUNT( movie_votes.movieid ) AS total_hates,';
                        $and .= 'AND movie_votes.vote_hate IS TRUE';
                        $order_by = 'total_hates';
    
                        break;
                    case 'sort_by_dates':
                        $order_by = 'creation_date';
        
                        break;
                }
            }

            $query = "SELECT $count movies.movieid, movies.title, movies.description, 
                             date_format( movies.creation_date, '%d/%m/%Y' ) AS posted, users.username AS posted_by
                      FROM {$this->table} 
                      LEFT JOIN movie_votes ON movies.movieid = movie_votes.movieid $and
                      LEFT JOIN users ON movies.userid = users.userid
                      $where
                      GROUP BY movies.movieid
                      ORDER BY $order_by DESC";
        
            $stmt = $conn->prepare( $query );
            if( $where ) {
                $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
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
