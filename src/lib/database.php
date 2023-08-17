<?php
namespace Application\Lib\Database;
use PDO;
/* create database connection */
class DatabaseConnection
  {
      
    public ?PDO $database = null;
    public function getConnection(): PDO
      
    {
        if ($this->database === null) {
        $this->database = new PDO('mysql:host=localhost:10022;dbname=blog;charset=utf8', 'root', 'root');
    }
    
      return $this->database;
  }
}
/* end of create database connection */
