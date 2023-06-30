# BlogPHPOpenClassRooms

After cloning the project : 

1 / Create a file database.php in the following folder : src/lib/database.php

2 / Adding this code :

        /* create database connection */
        <?php
        class DatabaseConnection
        {
        	public ?PDO $database = null;
        
        	public function getConnection(): PDO
        	{
            	if ($this->database === null) {
                	$this->database = new PDO('mysql:host=YOUR_HOST:YOUR_PORT;dbname=YOUR_DATABASE_NAME;charset=utf8', 'YOUR_USERNAME', 'YOUR_PASSWORD');
            	}
        
            	return $this->database;
        	}
        }
        
        /* end of create database connection */

3 / Change following values by yours :
  
          - YOUR_HOST
          
          - YOUR_PORT
          
          - YOUR_DATABASE_NAME
          
          - YOUR_USERNAME
          
          - YOUR_PASSWORD

IMPORTANT : Why database is not created directly in the project ? 
To prevent futur hacking of password to the databse connection. NEVER PUSH YOUR ENVIRONEMENT PASSWORD AND USERNAME EVEN ITS JUST ONLY A TEST !
