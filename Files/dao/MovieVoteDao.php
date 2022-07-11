<?php

/**
 * Data Access Object for movie vote data.
 */

class MovieVoteDao
{
	var $table = 'vote_movies';
	var $data = array();

    static $defaults = array(
        'movieid' => '',
        'userid' => '',
        'vote_like' => false,
        'vote_hate' => false
        );

    function getVoteLike( $values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid AND userid = :userid AND vote_like IS TRUE";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $values['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $values['userid'], PDO::PARAM_INT );
			$stmt->execute();
			
			if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
				return TRUE;
			}
            
			return FALSE;
		}
		catch( PDOException $e ) 
		{
			print_pdoexception( $e );
			return FALSE;
		}
	}

    function getVoteHate( $values )
	{
        global $conn;
        
   		try 
		{                
			$query = "SELECT * FROM {$this->table}
                      WHERE movieid = :movieid AND userid = :userid AND vote_hate IS TRUE";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':movieid', $values['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $values['userid'], PDO::PARAM_INT );
			$stmt->execute();
			
			if( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
				return TRUE;
			}
            
			return FALSE;
		}
		catch( PDOException $e ) 
		{
			print_pdoexception( $e );
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
                      ( :title, :userid, :vote_like, :vote_hate )";
                        
			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $this->data['userid'], PDO::PARAM_INT );
            $stmt->bindParam( ':vote_like', $this->data['vote_like'], PDO::PARAM_BOOL );
            $stmt->bindParam( ':vote_hate', $this->data['vote_hate'], PDO::PARAM_BOOL );
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e ) 
		{	
			print_pdoexception( $e );
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

            $field = '';
            if( isset( $values['like'] ) &&
                $values['like'] )                
            {
                $field .= 'vote_like = true AND vote_hate = false';
            }
            else if( isset( $values['hate'] ) &&
                     $values['hate'] )                
            {
                $field .= 'vote_hate = true AND vote_like = false';
            }
            
			$query = "UPDATE {$this->table}
			          SET $field
			          WHERE movieid = :movieid AND userid = :userid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
            $stmt->bindParam( ':userid', $this->data['userid'], PDO::PARAM_INT );
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e )
		{
			print_pdoexception( $e );
			return FALSE;
		}
	}    
}

?>
