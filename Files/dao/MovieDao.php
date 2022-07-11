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

    function getByOrder( &$values )
	{
        global $conn;
        
   		try 
		{
            $order_by = '';
            if( $values['action'] == 'sort_by_likes' ) {
                $order_by .= 'number_of_likes';
            }
            else if( $values['action'] == 'sort_by_hates' ) {
                $order_by .= 'number_of_hates';
            }
            else if( $values['action'] == 'sort_by_dates' ) {
                $order_by .= 'creation_date';
            }
            
			$query = "SELECT * FROM {$this->table}
                      ORDER BY $order_by";
            
			$stmt = $conn->prepare( $query );
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
			print_pdoexception( $e );
			return FALSE;
		}
	}
    
    function getAll( &$values )
    {
        global $conn;
        
        try
        {            
            $query = "SELECT *, to_char( creation_date, 'DD-MM-YYYY' ) AS posted, users.username AS posted_by
                      FROM {$this->table}
                      LEFT JOIN users ON movies.userid = users.userid
                      ORDER BY title";

            $stmt = $conn->prepare( $query );
            $stmt->execute();

            while( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
                $values[] = $row;
            }

            return TRUE;
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
                      ( title, description, userid )
                      VALUES
                      ( :title, :description, :userid )";
                        
			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':title', $this->data['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':description', $this->data['description'], PDO::PARAM_STR );
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
            if( isset( $values['like'] ) )                
            {
                if( $values['like'] ) {
                    $field .= 'number_of_likes = number_of_likes + 1';
                }
                else {
                    $field .= 'number_of_likes = number_of_likes - 1';
                }
            }
            else if( isset( $values['hate'] ) )
            {
                if( $values['hate'] ) {
                    $field .= 'number_of_hates = number_of_hates + 1';
                }
                else {
                    $field .= 'number_of_hates = number_of_hates - 1';
                }
            }
            
			$query = "UPDATE {$this->table}
			          SET $field
			          WHERE movieid = :movieid";

			$stmt = $conn->prepare( $query );
            $stmt->bindParam( ':movieid', $this->data['movieid'], PDO::PARAM_INT );
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
