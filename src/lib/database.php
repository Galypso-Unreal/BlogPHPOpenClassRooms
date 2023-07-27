<?php
/* create database connection */
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
