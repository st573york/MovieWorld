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
        'userid' => '',
        'number_of_likes' => 0,
        'number_of_hates' => 0
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
                        $order_by = 'number_of_likes';

                        break;
                    case 'sort_by_hates':
                        $order_by = 'number_of_hates';
    
                        break;
                    case 'sort_by_dates':
                        $order_by = 'creation_date';
        
                        break;
                }
            }

            $query = "SELECT *, date_format( creation_date, '%d/%m/%Y' ) AS posted, users.username AS posted_by
                      FROM {$this->table} 
                      LEFT JOIN users ON movies.userid = users.userid
                      $where
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
			          SET number_of_likes = number_of_likes + 1
			          WHERE movieid = :movieid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e )
		{
			echo $e->getMessage();
			return FALSE;
		}
	}    

    function toggleVoteLike( $values )
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
			          SET number_of_likes = number_of_likes + 1, number_of_hates = (
                        CASE WHEN number_of_hates > 0
                        THEN ( number_of_hates - 1 )
                        ELSE number_of_hates
                        END )
			          WHERE movieid = :movieid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
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
			          SET number_of_hates = number_of_hates + 1
			          WHERE movieid = :movieid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
			$stmt->execute();
            
			return TRUE;
		}
		catch( PDOException $e )
		{
			echo $e->getMessage();
			return FALSE;
		}
	}    

    function toggleVoteHate( $values )
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
			          SET number_of_hates = number_of_hates + 1, number_of_likes = (
                        CASE WHEN number_of_likes > 0
                        THEN ( number_of_likes - 1 )
                        ELSE number_of_likes
                        END )
			          WHERE movieid = :movieid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
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
