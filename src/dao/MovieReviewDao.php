<?php

/**
 * Data Access Object for movie review data.
 */

class MovieReviewDao
{
	var $table = 'movie_reviews';
	var $data = array();

    static $defaults = array(
        'movieid' => '',
        'userid' => '',
        'review' => ''
        );

	function getReviewsNum( $movieid )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT COUNT(*) FROM {$this->table}
                      WHERE movieid = :movieid";
			
			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT );
			$stmt->execute();

			return $stmt->fetchColumn();
		}
		catch( PDOException $e ) 
		{
			echo $e->getMessage();
			return 0;
		}
	}

	function getReviews( $movieid, &$values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT UNIX_TIMESTAMP( creation_date ) AS creation_date, username, review FROM {$this->table}
					  LEFT JOIN users ON movie_reviews.userid = users.userid
                      WHERE movieid = :movieid 
					  ORDER BY creation_date DESC";
			
			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $movieid, PDO::PARAM_INT );
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
                      ( movieid, userid, review )
                      VALUES
                      ( :movieid, :userid, :review )";
                        
			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
            $stmt->bindParam( ':review', $this->data['review'], PDO::PARAM_STR );
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
