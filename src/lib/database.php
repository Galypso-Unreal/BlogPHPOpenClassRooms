<?php

namespace Application\Lib\Database;

use PDO;


/*
    The `class DatabaseConnection` is a PHP class that represents a database connection. It has a
    property `` which is an instance of the `PDO` class, and a method `getConnection()` which
    returns the database connection.
*/

class DatabaseConnection
{

    public ?PDO $database = null;
    /**
     * The function returns a PDO connection to a MySQL database.
     * @return PDO a PDO object, which represents a connection to a database.
     */
    public function getConnection(): PDO

    {
        if ($this->database === null) {
            $this->database = new PDO('mysql:host=localhost:10070;dbname=blog;charset=utf8', 'root', 'root');
        }

        return $this->database;
        
    }

    
}
