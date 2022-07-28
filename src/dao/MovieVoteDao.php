<?php

/**
 * Data Access Object for movie vote data.
 */

class MovieVoteDao
{
	var $table = 'movie_votes';
	var $data = array();

    static $defaults = array(
        'movieid' => '',
        'userid' => '',
        'vote_like' => false,
        'vote_hate' => false
        );

	function hasUserVoted( $values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid AND userid = :userid";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $values['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			$stmt->execute();
			
			if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
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

    function hasUserLiked( $values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid AND userid = :userid AND vote_like IS TRUE";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $values['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			$stmt->execute();
			
			if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
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

	function hasUserHated( $values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid AND userid = :userid AND vote_hate IS TRUE";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $values['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			$stmt->execute();
			
			if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
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

	function getLikeVotesNum( $movieid )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT COUNT(*) FROM {$this->table}
                      WHERE movieid = :movieid AND vote_like IS TRUE";
			
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

	function getHateVotesNum( $movieid )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT COUNT(*) FROM {$this->table}
                      WHERE movieid = :movieid AND vote_hate IS TRUE";
			
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

	function getUsersByLike( $movieid, &$values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT username FROM {$this->table}
					  LEFT JOIN users ON movie_votes.userid = users.userid
                      WHERE movieid = :movieid AND vote_like IS TRUE 
					  ORDER BY username";
			
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

	function getUsersByHate( $movieid, &$values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT username FROM {$this->table}
					  LEFT JOIN users ON movie_votes.userid = users.userid
                      WHERE movieid = :movieid AND vote_hate IS TRUE 
					  ORDER BY username";
			
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
                      ( movieid, userid, vote_like, vote_hate )
                      VALUES
                      ( :movieid, :userid, :vote_like, :vote_hate )";
                        
			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
            $stmt->bindParam( ':vote_like', $this->data['vote_like'], PDO::PARAM_BOOL );
            $stmt->bindParam( ':vote_hate', $this->data['vote_hate'], PDO::PARAM_BOOL );
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e ) 
		{	
			echo $e->getMessage();
			return FALSE;
		}
	}

	function updateVoteLike( $values )
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
			          SET vote_hate = false, vote_like = (
                        CASE WHEN vote_like = true
                        THEN false
                        ELSE true
                        END )
			          WHERE movieid = :movieid AND userid = :userid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
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

	function updateVoteHate( $values )
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
			          SET vote_like = false, vote_hate = (
                        CASE WHEN vote_hate = true
                        THEN false
                        ELSE true
                        END )
			          WHERE movieid = :movieid AND userid = :userid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
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
}

?>
