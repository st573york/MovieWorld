<?php

/**
 * Data Access Object for movie comment data.
 */

class MovieCommentDao
{
	var $table = 'movie_comments';
	var $data = array();

    static $defaults = array(
        'movieid' => '',
        'userid' => '',
        'comment' => ''
        );

	function getCommentsNum( $movieid )
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

	function getComments( $movieid, &$values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT creation_date, username, comment FROM {$this->table}
					  LEFT JOIN users ON movie_comments.userid = users.userid
                      WHERE movieid = :movieid 
					  ORDER BY creation_date";
			
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
                      ( movieid, userid, comment )
                      VALUES
                      ( :movieid, :userid, :comment )";
                        
			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
            $stmt->bindParam( ':comment', $this->data['comment'], PDO::PARAM_STR );
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
