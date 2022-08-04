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

	function getByID( &$values )
	{
        global $conn;
        
   		try
		{			
			$query = "SELECT * FROM {$this->table}
                      WHERE userid = :userid";

            $stmt = $conn->prepare( $query );
			$stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
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
			$where = ( $_SESSION['userid'] )? 'userid != :userid AND' : '';

			$query = "SELECT * FROM {$this->table}
                      WHERE $where username = :username";

            $stmt = $conn->prepare( $query );
			if( $_SESSION['userid'] ) {
				$stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			}
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
			$where = ( $_SESSION['userid'] )? 'userid != :userid AND' : '';

			$query = "SELECT * FROM {$this->table}
                      WHERE $where email = :email";

            $stmt = $conn->prepare( $query );
			if( $_SESSION['userid'] ) {
				$stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			}
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
			          SET username = :username, ".( $values['password']? 'password = :password, ' : '' )." email = :email
			          WHERE userid = :userid";

			$stmt = $conn->prepare( $query );
			$stmt->bindParam( ':userid', $_SESSION['userid'], PDO::PARAM_INT );
			$stmt->bindParam( ':username', $this->data['username'], PDO::PARAM_STR );
			if( $values['password'] ) {
				$stmt->bindParam( ':password', $values['password'], PDO::PARAM_STR );
			}
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
