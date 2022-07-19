<?php

/**
 * Data Access Object for user data.
 */

class UserDao
{
	var $table = 'users';
    var $data = array();
    
    static $defaults = array(
        'userid' => '',
        'username' => '',
        'password' => '',
        'email' => ''
        );

	function get( &$values )
	{
        global $conn;
        
   		try
		{			
			$query = "SELECT * FROM {$this->table}
                      WHERE username = :username AND password = :password";

            $stmt = $conn->prepare( $query );
			$stmt->bindParam( ':username', $values['username'], PDO::PARAM_STR );
			$stmt->bindParam( ':password', $values['password'], PDO::PARAM_STR );
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

	function getByUsername( $values )
	{
        global $conn;
        
   		try
		{			
			$query = "SELECT * FROM {$this->table}
                      WHERE username = :username";

            $stmt = $conn->prepare( $query );
			$stmt->bindParam( ':username', $values['username'], PDO::PARAM_STR );
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

    function getByEmail( $values )
	{
        global $conn;
        
   		try
		{			
			$query = "SELECT * FROM {$this->table}
                      WHERE email = :email";

            $stmt = $conn->prepare( $query );
			$stmt->bindParam( ':email', $values['email'], PDO::PARAM_STR );
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
                      ( username, password, email )
			          VALUES 
					  ( :username, :password, :email ) ";
			
			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':username', $this->data['username'], PDO::PARAM_STR );
			$stmt->bindParam( ':password', $this->data['password'], PDO::PARAM_STR );
            $stmt->bindParam( ':email', $this->data['email'], PDO::PARAM_STR );  
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
			          SET username = :username, password = :password, email = :email
			          WHERE userid = :userid";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':username', $this->data['username'], PDO::PARAM_STR );
            $stmt->bindParam( ':password', $this->data['password'], PDO::PARAM_STR );            
			$stmt->bindParam( ':email', $this->data['email'], PDO::PARAM_STR );            
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
