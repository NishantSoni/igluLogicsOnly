<?php
/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    CodexWorld.com
 * @url       http://www.codexworld.com
 * @license   http://www.codexworld.com/license
 */
class Database{

    private $dbHost     = "localhost";
    private $dbUsername = "";
    private $dbPassword = "";
    private $dbName     = "";

    private $results_per_page = 10;

    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            try{
                $conn = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
                $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db = $conn;
            }catch(PDOException $e){
                die("Failed to connect with MySQL: " . $e->getMessage());
            }
        }
    }

    public function getSearchResult($tableName , $title , $page ){

        $start_from = ($page-1) * $this->results_per_page;
        
        $sql = 'SELECT * FROM '.$tableName.' where title LIKE %'.$title.'%'.' ORDER BY title ASC LIMIT $start_from, '.$results_per_page;

        $query = $this->db->prepare($sql);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchAll();
        }
        return false;
    }
}